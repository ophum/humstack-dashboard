<?php

namespace App\Http\Controllers;

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

        foreach ($problem->deployedTeams as $team) {
            $isAllRunning = true;
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

                if ($vm->status->state !== 'Running') {
                    $isAllRunning = false;
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
    public function destroy($id)
    {
        //
    }
}
