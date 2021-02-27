<?php

namespace App\Http\Controllers;

use App\Models\Network;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Utils\HumClient\Clients;

class ProblemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $group = $user->group;
        $problems = $group->problems;
        $teams = $group->teams;
        return view('pages.problems.index', [
            'problems' => $problems,
            'teams' => $teams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.problems.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $problem = new Problem($request->all());
        $problem->group_id = auth()->user()->group->id;
        $problem->save();

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
    public function show(Problem $problem)
    {
        $problem->load([
            'machines',
            'storages',
            'networks',
            'group.teams'
        ]);

        $vms = [];
        $bss = [];
        $nets = [];

        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $runningVMCountMap = [];
        $powerOffVMCountMap = [];
        $activeBSCountMap = [];
        $netCountMap = [];
        foreach ($problem->deployedTeams as $team) {
            $isAllRunning = true;
            $runningVMCountMap[$team->name] = 0;
            $powerOffVMCountMap[$team->name] = 0;
            $activeBSCountMap[$team->name] = 0;
            $netCountMap[$team->name] = 0;
            $vmCount = 0;
            foreach ($problem->machines as $m) {
                $res = $clients->VirtualMachine()->get(
                    $problem->group->name,
                    $problem->code,
                    \App\Utils\Tools::getDeployName($m->name, $team, $problem)
                );
                $vm = $res->data;
                if ($vm === null) {
                    continue;
                }

                if ($vm->status->state === 'Running') {
                    $runningVMCountMap[$team->name]++;
                }else {
                    $isAllRunning = false;

                    if ($vm->spec->actionState === 'PowerOff') {
                        $powerOffVMCountMap[$team->name]++;
                    }
                }
                $vmCount++;
            }

            $bsCount = 0;
            foreach ($problem->storages as $s) {
                $res = $clients->BlockStorage()->get(
                    $problem->group->name,
                    $problem->code,
                    \App\Utils\Tools::getDeployName($s->name, $team, $problem)
                );
                $bs = $res->data;
                if ($bs === null) {
                    continue;
                }

                if ($bs->status->state === 'Active' || $bs->status->state === 'Used') {
                    $activeBSCountMap[$team->name]++;
                }

                $bsCount++;
            }

            $netCount = 0;
            foreach ($problem->networks as $n) {
                $res = $clients->Network()->get(
                    $problem->group->name,
                    $problem->code,
                    \App\Utils\Tools::getDeployName($n->name, $team, $problem)
                );
                $net = $res->data;
                if ($net === null) {
                    continue;
                }

                $netCount++;
                $netCountMap[$team->name]++;
            }

            // VMが動いている=BS、NETも動いている
            if ($team->pivot->status === '展開中' && $isAllRunning) {
                $problem->deployedTeams()->updateExistingPivot(
                    $team->id,
                    [
                        'status' => "展開済",
                    ],
                );
            }

            // 削除中かつvm, bs, netがなければ未展開にする
            if ($team->pivot->status === '削除中' && $vmCount == 0 && $bsCount == 0 && $netCount == 0) {
                $problem->deployedTeams()->updateExistingPivot(
                    $team->id,
                    [
                        'status' => "未展開",
                    ],
                );
            }
        }

        return view('pages.problems.show', [
            'problem' => $problem,
            'runningVMCountMap' => $runningVMCountMap,
            'powerOffVMCountMap' => $powerOffVMCountMap,
            'activeBSCountMap' => $activeBSCountMap,
            'netCountMap' => $netCountMap,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem)
    {
        return view('pages.problems.update', [
            'problem' => $problem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem)
    {
        $problem->name = $request->name;
        $problem->author = $request->author;
        $problem->save();

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
    public function delete(Problem $problem)
    {
        $isDeletable = true;
        foreach ($problem->deployedTeams as $team) {
            if ($team->pivot->status != "未展開") {
                $isDeletable = false;
                break;
            }
        }

        if (!$isDeletable) {
            dd("please destroy resources");
        }


        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        $clients->Namespace()->delete($problem->group->name, $problem->code);

        $problem->delete();

        return redirect(route('problems.index'));
    }

    public function communicatingVMList()
    {
        $networks = Network::where('require_gateway', true)->with(['machines', 'problem'])->get();
        $vmList = [];
        foreach($networks as $net) {
            foreach($net->machines as $machine) {
                foreach($machine->attachedNics as $nic) {
                    $vmList[$net->problem->id][$machine->name][$nic->name] = $nic->pivot->ipv4_address;
                }
            }
        }
        
        return view('pages.problems.communicatingVMList', [
            'vmList' => $vmList,
        ]);
    }
    
    public function communicatingVMListCSV()
    {
        $vmList = $this->getCommunicatingVMList();
        
        return view('pages.problems.communicatingVMList', [
            'vmList' => $vmList,
        ]);
    }

    private function getCommunicatingVMList()
    {
        dd(Network::where('require_gateway', true)
            ->join('problems', 'networks.problem_id', '=', 'problems.id')
            ->join('attached_nics', 'networks.id', '=', 'attached_nics.network_id')
            ->join('machines', 'attached_nics.machine_id', '=', 'machines.id')
            ->orderBy('problems.id', 'asc')
            ->orderBy('machines.name', 'asc')
            ->orderBy('networks.name', 'asc')
            ->orderBy('attached_nics.ipv4_address', 'asc')
            ->get([
                "problems.id as pid",
                "machines.name as vm_name",
                "networks.name as net_name",
                "attached_nics.ipv4_address as ipv4_address",
            ])->toArray());

        $networks = Network::where('require_gateway', true)->with(['machines', 'problem'])->get();
        $vmList = [];
        foreach($networks as $net) {
            foreach($net->machines as $machine) {
                foreach($machine->attachedNics as $nic) {
                    $vmList[$net->problem->id][$machine->name][$nic->name] = $nic->pivot->ipv4_address;
                }
            }
        }
        return $vmList;
    }
}
