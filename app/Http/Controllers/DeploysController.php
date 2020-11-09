<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Storage;
use App\Models\Team;
use App\Models\Node;
use App\Utils\HumClient\System\BlockStorage\BlockStorage;
use App\Utils\HumClient\Core\Network\Network;
use App\Utils\HumClient\System\VirtualMachine\VirtualMachine;
use App\Utils\HumClient\Core\NS\NS;
use App\Utils\HumClient\Core\Group\Group;
use App\Utils\HumClient\System\ImageEntity\ImageEntity;
use App\Utils\HumClient\Clients;

class DeploysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Problem $problem)
    {
        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));
        $group = $problem->group->name;
        $ns = $problem->name;

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
        $problem->deployedTeams()->attach($team->id, [
            'node_id' => $request->node_id,
            'status' => '未展開',
        ]);
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
        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));

        $vmList = [];
        $bsList = [];
        $netList = [];

        foreach ($problem->machines as $m) {
            $res = $clients->VirtualMachine()->get(
                $problem->group->name,
                $problem->name,
                $this->getDeployName($m->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $vmList[] = $res->data;
        }

        foreach ($problem->storages as $s) {
            $res = $clients->BlockStorage()->get(
                $problem->group->name,
                $problem->name,
                $this->getDeployName($s->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $bsList[$s->id] = $res->data;
        }

        foreach ($problem->networks as $n) {
            $res = $clients->Network()->get(
                $problem->group->name,
                $problem->name,
                $this->getDeployName($n->name, $team, $problem)
            );
            if ($res->data === null) {
                continue;
            }

            $netList[] = $res->data;
        }

        return view('pages.problems.deploys.show', [
            'problem' => $problem,
            'team' => $team,
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

        $problem->deployedTeams()->updateExistingPivot(
            $team->id,
            [
                'status' => "削除中",
            ],
        );

        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));

        foreach ($problem->machines as $m) {
            $deployedName = $this->getDeployName($m->name, $team, $problem);
            $res = $clients->VirtualMachine()->get($problem->group->name, $problem->name, $deployedName);
            $vm = $res->data;
            if ($vm === null) {
                continue;
            }
            $vm->meta->deleteState = "Delete";
            $clients->VirtualMachine()->update($vm);
        }

        foreach ($problem->networks as $n) {
            $deployedName = $this->getDeployName($n->name, $team, $problem);
            $res = $clients->Network()->get($problem->group->name, $problem->name, $deployedName);
            $net = $res->data;
            if ($net === null) {
                continue;
            }
            $net->meta->deleteState = "Delete";
            $clients->Network()->update($net);
        }

        foreach ($problem->storages as $s) {
            $deployedName = $this->getDeployName($s->name, $team, $problem);
            $res = $clients->BlockStorage()->get($problem->group->name, $problem->name, $deployedName);
            $bs = $res->data;
            if ($net === null) {
                continue;
            }
            $bs->meta->deleteState = "Delete";
            $clients->BlockStorage()->update($bs);
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

        $problem->deployedTeams()->updateExistingPivot(
            $team->id,
            [
                'status' => "展開中",
            ],
        );

        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));

        $group = $clients->Group()->get($problem->group->name);
        if ($group->code == 404 || $group->data === null) {
            $clients->Group()->create(new Group([
                'meta' => [
                    'id' => 'group1',
                    'name' => 'group1',
                ]
            ]));
        }
        $ns = $clients->Namespace()->get($problem->group->name, $problem->name);
        if ($ns->code == 404 || $ns->data === null) {
            $clients->Namespace()->create(new NS([
                'meta' => [
                    'id' => $problem->name,
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


        // TODO: humstackへのリクエスト

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    private function getDeployBlockStoragesData(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();
        $nodeName = $setting->pivot->node->name;

        $data = [];
        foreach ($problem->storages as $s) {
            $name = $team->id_prefix . '_' . $problem->name . '_' . $s->name;
            $data[] = new BlockStorage([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->name,
                    'annotations' => [
                        'blockstoragev0/type' => 'Local',
                        'blockstoragev0/node_name' => $nodeName,
                    ],
                ],
                'spec' => [
                    'limitSize' => $s->size,
                    'requestSize' => "1",
                    'from' => [
                        'type' => "BaseImage",
                    //    'type' => "HTTP",
                        'http' => [
                            'url' => "http://192.168.20.2:8082/focal-server-cloudimg-amd64.img",
                        ],
                        'baseImage' => [
                            'imageName' => $s->image_name,
                            'tag' => $s->image_tag,
                        ]
                    ]
                ]
            ]);

            // image名とtag名をimage_tag_idから取得する
        }

        return $data;
    }

    private function getDeployNetworksData(Problem $problem, Team $team)
    {
        $setting = $problem->deployedTeams()->where('team_id', $team->id)->first();

        $data = [];
        foreach ($problem->networks as $n) {
            $name = $team->id_prefix . '_' . $problem->name . '_' . $n->name;
            $data[] = new Network([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->name,
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
            $name = $team->id_prefix . '_' . $problem->name . '_' . $vm->name;
            $bsIDs = [];
            foreach ($vm->attachedStorages as $s) {
                $bsIDs[] = $team->id_prefix . '_' . $problem->name . '_' . $s->name;
            }

            $nics = [];
            foreach ($vm->attachedNics as $n) {
                $networkID = $team->id_prefix . '_' . $problem->name . '_' . $n->name;
                $nics[] = [
                    'networkID' => $networkID,
                    'ipv4Address' => $n->pivot->ipv4_address,
                    'nameservers' => [
                        '8.8.8.8',
                    ],
                    'defaultGateway' => $n->pivot->default_gateway,
                ];
            }

            $loginUsers = [];
            $data[] = new VirtualMachine([
                'meta' => [
                    'id' => $name,
                    'name' => $name,
                    'group' => $problem->group->name,
                    'namespace' => $problem->name,
                    'annotations' => [
                        'virtualmachinev0/node_name' => $nodeName,
                    ],
                ],
                'spec' => [
                    'requestVcpus' => $vm->vcpus,
                    'requestMemory' => $vm->memory,
                    'limitVcpus' => '1',
                    'limitMemory' => $vm->memory,
                    'blockStorageIDs' => $bsIDs,
                    'nics' => $nics,
                    'actionState' => 'PowerOn',
                    'loginUsers' => $loginUsers,
                ],
            ]);
        }

        return $data;
    }

    public function showBlockStorage(Problem $problem, Team $team, Storage $storage)
    {
        $user = auth()->user();
        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));

        $res = $clients->Image()->list($user->group->name);
        $imageList = $res->data;


        return view('pages.problems.deploys.storages.show', [
            'problem' => $problem,
            'team' => $team,
            'storage' => $storage,
            'imageList' => $imageList,
        ]);
    }

    public function toImageBlockStorage(Request $request, Problem $problem, Team $team, Storage $storage)
    {
        $deployedName = $this->getDeployName($storage->name, $team, $problem);

        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));
        $imageID = $request->image_id;
        $tag = $request->tag;
        $res = $clients->Image()->get($problem->group->name, $imageID);
        if ($res->code == 404 || $res === null) {
            dd("not exists");
        }

        $image = $res->data;

        // TODO: image entityを作成する

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
                    'namespace' => $problem->name,
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
            'team' => $problem,
        ]));
    }
    private function getDeployName($name, Team $team, Problem $problem)
    {
        return $team->id_prefix . '_' . $problem->name . '_' . $name;
    }
}
