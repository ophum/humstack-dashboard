<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Storage;
use App\Models\Machine;
use App\Models\Team;
use App\Models\Node;
use App\Utils\HumClient\System\BlockStorage\BlockStorage;
use App\Utils\HumClient\Core\Network\Network;
use App\Utils\HumClient\System\VirtualMachine\VirtualMachine;
use App\Utils\HumClient\Core\NS\NS;
use App\Utils\HumClient\Core\Group\Group;
use App\Utils\HumClient\System\ImageEntity\ImageEntity;
use App\Utils\HumClient\Clients;
use App\Utils\Tools;

class DeploysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Problem $problem)
    {
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        $group = $problem->group->name;
        $ns = $problem->code;

        $bsList = $clients->BlockStorage()->list($group, $ns);
        $netList = $clients->Network()->list($group, $ns);
        $vmList = $clients->VirtualMachine()->list($group, $ns);

        return view('pages.problems.deploys.index', [
            'problem' => $problem,
            'bsList' => $bsList->data,
            'netList' => $netList->data,
            'vmList' => $vmList->data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Problem $problem, Team $team)
    {
        $nodes = Node::get();
        return view('pages.problems.deploys.create', [
            'problem' => $problem,
            'team' => $team,
            'nodes' => $nodes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Problem $problem, Team $team)
    {
        if ($request->storage_type != "Local" && $request->storage_type != "Ceph") {
            abort(400, "invalid storage_type");
        }

        $problem->deployedTeams()->attach($team->id, [
            'node_id' => $request->node_id,
            'storage_type' => $request->storage_type,
            'status' => '未展開',
        ]);
        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function bulk(Problem $problem)
    {
        $teams = Team::get();
        $nodes = Node::get();
        $settings = $problem->deployedTeams;
        $settingMap = [];
        foreach($settings as $t) {
            $settingMap[$t->name] = $t->pivot;
        }
        return view('pages.problems.deploys.bulk', [
            'problem' => $problem,
            'teams' => $teams,
            'nodes' => $nodes,
            'settingMap' => $settingMap,
        ]);
    }

    public function bulkSetDeploySettings(Request $request, Problem $problem)
    {
        $teams = Team::get();
        $node = Node::find($request->node_id);
        $storageType = $request->storage_type;
        if ($storageType !== "Ceph" && $storageType !== "Local") {
            dd("invalid storage type");
        }

        foreach($teams as $team) {
            $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
            if ($setting !== null) {
                if ($setting->pivot->status === "未展開") {
                    if ($node === null) {
                        $problem->deployedTeams()->detach($team->id);
                    }else {
                        $problem->deployedTeams()->updateExistingPivot(
                            $team->id,
                            [
                                'node_id' => $node->id,
                                'storage_type' => $storageType,
                            ],
                        );
                    }
                }
            }else {
                if ($node !== null) {
                    $problem->deployedTeams()->attach($team->id, [
                        'node_id' => $node->id,
                        'storage_type' => $storageType,
                        'status' => '未展開',
                    ]);
                }
            }
        }

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function bulkStore(Request $request, Problem $problem)
    {
        for($i = 0; $i < count($request->teams); $i++) {
            $team = Team::find($request->teams[$i]);
            $node = Node::find($request->node_id[$i]);
            $storageType = $request->storage_type[$i];
            if ($team === null) {
                dd("invalid team");
            }

            if ($storageType !== "Ceph" && $storageType !== "Local") {
                dd("invalid storage type");
            }

            $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
            if ($setting !== null) {
                if ($setting->pivot->status === "未展開") {
                    if ($node === null) {
                        $problem->deployedTeams()->detach($team->id);
                    }else {
                        $problem->deployedTeams()->updateExistingPivot(
                            $team->id,
                            [
                                'node_id' => $node->id,
                                'storage_type' => $storageType,
                            ],
                        );
                    }
                }
            }else {
                if ($node !== null) {
                    $problem->deployedTeams()->attach($team->id, [
                        'node_id' => $node->id,
                        'storage_type' => $storageType,
                        'status' => '未展開',
                    ]);
                }
            }

        }
        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Problem $problem, Team $team)
    {
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $nodeID = $problem->deployedTeams()->where('team_id', $team->id)->first()->pivot->node->name;
        $res = $clients->Node()->get($nodeID);
        if ($res->data === null) {
            dd("node is not found.");
        }
        $node = $res->data;

        $vmList = [];
        $bsList = [];
        $netList = [];

        foreach ($problem->machines as $m) {
            $res = $clients->VirtualMachine()->get(
                $problem->group->name,
                $problem->code,
                Tools::getDeployName($m->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $vmList[$m->id] = $res->data;
        }

        foreach ($problem->storages as $s) {
            $res = $clients->BlockStorage()->get(
                $problem->group->name,
                $problem->code,
                Tools::getDeployName($s->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $bsList[$s->id] = $res->data;
        }

        foreach ($problem->networks as $n) {
            $res = $clients->Network()->get(
                $problem->group->name,
                $problem->code,
                Tools::getDeployName($n->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $netList[] = $res->data;
        }

        return view('pages.problems.deploys.show', [
            'problem' => $problem,
            'team' => $team,
            'node' => $node,
            'vmList' => $vmList,
            'bsList' => $bsList,
            'netList' => $netList,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function deleteDeploySetting(Problem $problem, Team $team)
    {
        $problem->deployedTeams()->detach($team->id);
        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function multiDestroy(Request $request, Problem $problem) {
        $teamIDs = $request->teamIDs;
        foreach ($teamIDs as $id) {
            $team = $problem->deployedTeams()->where('team_id', $id)->first();
            if ($team === null) {
                continue;
            }

            if ($team->pivot->status !== "展開済" &&
                $team->pivot->status !== "展開中" &&
                $team->pivot->status !== "削除中") {
                continue;
            }

            $this->_destroy($problem, $team);
        }

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
        if ($setting === null) {
            dd('deploy setting is not found');
        }
        if ($setting->pivot->status !== "展開済" &&
            $setting->pivot->status !== "展開中" &&
            $setting->pivot->status !== "削除中") {
            return redirect(route('problems.show', [
                'problem' => $problem,
            ]));
        }

        $this->_destroy($problem, $team);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    private function _destroy(Problem $problem, Team $team) {
        $problem->deployedTeams()->updateExistingPivot(
            $team->id,
            [
                'status' => "削除中",
            ],
        );

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        foreach ($problem->machines as $m) {
            $deployedName = Tools::getDeployName($m->name, $team, $problem);
            $res = $clients->VirtualMachine()->get($problem->group->name, $problem->code, $deployedName);
            $vm = $res->data;
            if ($vm === null) {
                continue;
            }
            $vm->meta->deleteState = "Delete";
            $clients->VirtualMachine()->update($vm);
        }

        foreach ($problem->networks as $n) {
            $deployedName = Tools::getDeployName($n->name, $team, $problem);
            $res = $clients->Network()->get($problem->group->name, $problem->code, $deployedName);
            $net = $res->data;
            if ($net === null) {
                continue;
            }
            $net->meta->deleteState = "Delete";
            $clients->Network()->update($net);
        }

        foreach ($problem->storages as $s) {
            $deployedName = Tools::getDeployName($s->name, $team, $problem);
            $res = $clients->BlockStorage()->get($problem->group->name, $problem->code, $deployedName);
            $bs = $res->data;
            if ($bs === null) {
                continue;
            }
            $bs->meta->deleteState = "Delete";
            $clients->BlockStorage()->update($bs);
        }

    }

    public function multiDeploy(Request $request, Problem $problem)
    {
        $teamIDs = $request->teamIDs;
        foreach ($teamIDs as $id) {
            $team = $problem->deployedTeams()->where('team_id', $id)->first();
            if ($team === null) {
                continue;
            }

            if ($team->pivot->status != '未展開') {
                continue;
            }

            $this->_deploy($problem, $team);
        }

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function deploy(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
        if ($setting === null) {
            dd('deploy setting is not found');
        }

        if ($setting->pivot->status !== "未展開") {
            return redirect(route('problems.show', [
                'problem' => $problem,
            ]));
        }

        $this->_deploy($problem, $team);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    private function _deploy(Problem $problem, Team $team)
    {
        $problem->deployedTeams()->updateExistingPivot(
            $team->id,
            [
                'status' => "展開中",
            ],
        );

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $group = $clients->Group()->get($problem->group->name);
        if ($group->code == 404 || $group->data === null) {
            $clients->Group()->create(new Group([
                'meta' => [
                    'id' => $problem->group->name,
                    'name' => $problem->group->name,
                ]
            ]));
        }
        $ns = $clients->Namespace()->get($problem->group->name, $problem->code);
        if ($ns->code == 404 || $ns->data === null) {
            $clients->Namespace()->create(new NS([
                'meta' => [
                    'id' => $problem->code,
                    'name' => $problem->name,
                    'group' => $problem->group->name,
                ],
            ]));
        }

        $bsDataList = $this->getDeployBlockStoragesData($problem, $team);
        foreach ($bsDataList as $bs) {
            $clients->BlockStorage()->create($bs);
        }

        $netDataList = $this->getDeployNetworksData($problem, $team);
        foreach ($netDataList as $net) {
            $clients->Network()->create($net);
        }

        $vmDataList = $this->getDeployVirtualMachinesData($problem, $team);
        foreach ($vmDataList as $vm) {
            $clients->VirtualMachine()->create($vm);
        }
    }

    public function powerOnVirtualMachines(Problem $problem, Team $team) {
        $this->_powerOnVirtualMachines($problem, $team);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function multiPowerOnVirtualMachines(Request $request, Problem $problem) {
        $teamIDs = $request->teamIDs;
        foreach ($teamIDs as $id) {
            $team = $problem->deployedTeams()->where('team_id', $id)->first();
            if ($team === null) {
                continue;
            }

            if ($team->pivot->status != '展開中') {
                continue;
            }

            $this->_powerOnVirtualMachines($problem, $team);
        }

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    private function _powerOnVirtualMachines(Problem $problem, Team $team) {
        $vmDataList = $this->getDeployVirtualMachinesData($problem, $team);
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        foreach($vmDataList as $vm) {
            $res = $clients->VirtualMachine()->get($vm->meta->group, $vm->meta->namespace, $vm->meta->id);
            $storedVm = $res->data;
            if ($storedVm && $storedVm->spec->actionState != "PowerOn") {
                $storedVm->spec->actionState = "PowerOn";
                $clients->VirtualMachine()->update($storedVm);
            }
        }
    }

    private function getDeployBlockStoragesData(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
        $nodeName = $setting->pivot->node->name;

        $data = [];
        foreach ($problem->storages as $s) {
            $name = Tools::getDeployName($s->name, $team, $problem);
            $data[] = new BlockStorage([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->code,
                    'annotations' => [
                        'blockstoragev0/type' => $setting->pivot->storage_type,
                        'blockstoragev0/node_name' => $nodeName,
                    ],
                ],
                'spec' => [
                    'limitSize' => $s->size . 'G',
                    'requestSize' => "1",
                    'from' => [
                        'type' => $s->from_type,
                        'http' => [
                            'url' => $s->from_type == "HTTP" ? $s->url : "",
                        ],
                        'baseImage' => [
                            'imageName' => $s->from_type == "BaseImage" ? $s->image_name : "",
                            'tag' => $s->from_type == "BaseImage" ? $s->image_tag : "",
                        ]
                    ]
                ]
            ]);
        }

        return $data;
    }

    private function getDeployNetworksData(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();

        $data = [];
        foreach ($problem->networks as $n) {
            $name = Tools::getDeployName($n->name, $team, $problem);
            $data[] = new Network([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->code,
                    'annotations' => [
                        'require-gateway' => $n->require_gateway ? "true" : "false",
                    ],
                ],
                'spec' => [
                    'template' => [
                        'meta' => [
                            'annotations' => [
                                'nodenetworkv0/network_type' => "VLAN",
                            ],
                        ],
                        'spec' => [
                            'id' => sprintf("%d%02d", $team->vlan_prefix, $n->vlan_id),
                            'ipv4CIDR' => $n->ipv4_cidr,
                        ]
                    ],
                ],
            ]);
        }

        return $data;
    }

    private function getDeployVirtualMachinesData(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();

        $nodeName = $setting->pivot->node->name;
        $data = [];
        foreach ($problem->machines as $vm) {
            $name = Tools::getDeployName($vm->name, $team, $problem);
            $bsIDs = [];
            foreach ($vm->attachedStorages as $s) {
                $bsIDs[] = Tools::getDeployname($s->name, $team, $problem);
            }

            $nics = [];
            foreach ($vm->attachedNics as $n) {
                $networkID = Tools::getDeployName($n->name, $team, $problem);
		$nameservers = [];
		if ($n->pivot->nameserver !== "") {
			$nameservers = [
				$n->pivot->nameserver,
			];
		}
                $nics[] = [
                    'networkID' => $networkID,
                    'ipv4Address' => $n->pivot->ipv4_address,
                    'nameservers' => $nameservers,
                    'defaultGateway' => $n->pivot->default_gateway,
                ];
            }


            $loginUsers = [];

            $username = config('humstack.defaultLoginUsername');
            $publicKey = config('humstack.defaultLoginUserPublicKey');
            if ($username != "") {
                $loginUsers[] = [
                    'username' => $username,
                    'sshAuthorizedKeys' => [
                        $publicKey,
                    ],
                ];
            }

            $data[] = new VirtualMachine([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->code,
                    'annotations' => [
                        'virtualmachinev0/node_name' => $nodeName,
                        'virtualmachinev0/arch' => $vm->arch,
                    ],
                ],
                'spec' => [
                    'hostname' => $vm->hostname,
                    'requestVcpus' => '1',
                    'requestMemory' => $vm->memory,
                    'limitVcpus' => $vm->vcpus,
                    'limitMemory' => $vm->memory,
                    'blockStorageIDs' => $bsIDs,
                    'nics' => $nics,
                    'actionState' => 'PowerOff',
                    'loginUsers' => $loginUsers,
                ],
            ]);
        }

        return $data;
    }

    public function showBlockStorage(Problem $problem, Team $team, Storage $storage)
    {
        $user = auth()->user();
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $res = $clients->Image()->list($user->group->name);
        $imageList = $res->data;


        return view('pages.problems.deploys.storages.show', [
            'problem' => $problem,
            'team' => $team,
            'storage' => $storage,
            'imageList' => $imageList,
        ]);
    }

    // for ictsc
    public function toProdImageFromImage(Request $request, Problem $problem)
    {
        $nodes = Node::get();
        if ($nodes->count() === 0) {
            return redirect(route('problems.show', [
                'problem' => $problem,
            ]));
        }
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        foreach($problem->storages as $storage) {
            if($storage->from_type !== "BaseImage") {
                continue;
            }

            $imageName = $storage->image_name;
            $res = $clients->Image()->get($problem->group->name, $imageName);
            if ($res->code == 404 || $res === null) {
                dd("not exists");
            }

            $image = $res->data;
            $imageTag = $storage->image_tag;
            $prodTag = $imageTag . '-prod';

            $imageEntity = new ImageEntity([
                'meta' => [
                    'id' => \uniqid(),
                    'name' => $prodTag,
                    'group' => $problem->group->name,
                    'annotations' => [
                        'created_at' => date("Y-m-d H:i:s"),
                        'imageentityv0/node_name' => $nodes->random()->name,
                    ],
                ],
                'spec' => [
                    'type' => 'Ceph',
                    'source' => [
                        'type' => "Image",
                        'imageName' => $imageName,
                        'imageTag' => $imageTag,
                    ],
                ],
            ]);

            $clients->ImageEntity()->create($imageEntity);
            if (isset($image->spec->entityMap[$prodTag])) {
                dd("tag already used");
            }

            $image->spec->entityMap[$prodTag] = $imageEntity->meta->id;

            $clients->Image()->update($image);


            $storage->image_tag = $prodTag;
            $storage->save();
        }

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function toImageBlockStorage(Request $request, Problem $problem, Team $team, Storage $storage)
    {
        $deployedName = Tools::getDeployName($storage->name, $team, $problem);

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        $imageID = $request->image_id;
        $tag = $request->tag;
        $res = $clients->Image()->get($problem->group->name, $imageID);
        if ($res->code == 404 || $res === null) {
            dd("not exists");
        }

        $image = $res->data;

        $imageEntity = new ImageEntity([
            'meta' => [
                'id' => \uniqid(),
                'name' => $deployedName,
                'group' => $problem->group->name,
                'annotations' => [
                    'created_at' => date("Y-m-d H:i:s"),
                ],
            ],
            'spec' => [
                'source' => [
                    'namespace' => $problem->code,
                    'blockStorageID' => $deployedName,
                ],
            ],
        ]);

        $clients->ImageEntity()->create($imageEntity);

        if (isset($image->spec->entityMap[$tag])) {
            dd("tag already used");
        }

        $image->spec->entityMap[$tag] = $imageEntity->meta->id;

        $clients->Image()->update($image);

        return redirect(route('problems.deploys.show', [
            'problem' => $problem,
            'team' => $team,
        ]));
    }

    public function setIgnoreVM(Problem $problem, Team $team, Machine $machine)
    {
        $deployedName = Tools::getDeployName($machine->name, $team, $problem);

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $res = $clients->VirtualMachine()->get($problem->group->name, $problem->code, $deployedName);
        if ($res->code == 404 || $res === null) {
            dd("not exists");
        }

        $vm = $res->data;

        $vm->meta->annotations['virtualmachinev0/ignore'] = 'true';
        $vm->meta->annotations['pre-state'] = $vm->status->state;
        $vm->status->state = "";

        $clients->VirtualMachine()->update($vm);

        return redirect(route('problems.deploys.show', [
            'problem' => $problem,
            'team' => $team,
        ]));
    }

    public function unsetIgnoreVM(Problem $problem, Team $team, Machine $machine)
    {
        $deployedName = Tools::getDeployName($machine->name, $team, $problem);

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $res = $clients->VirtualMachine()->get($problem->group->name, $problem->code, $deployedName);
        if ($res->code == 404 || $res === null) {
            dd("not exists");
        }

        $vm = $res->data;

        $vm->status->state = $vm->meta->annotations['pre-state'] ?? "";
        unset($vm->meta->annotations['virtualmachinev0/ignore']);
        unset($vm->meta->annotations['pre-state']);

        $clients->VirtualMachine()->update($vm);

        return redirect(route('problems.deploys.show', [
            'problem' => $problem,
            'team' => $team,
        ]));
    }
}
