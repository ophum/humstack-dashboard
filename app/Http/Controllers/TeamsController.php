<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::get();

        return view('pages.teams.index', [
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
        return view('pages.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                "required",
                "unique:teams",
                "max:255'",
            ],
            'id_prefix' => [
                "required",
                "unique:teams",
                "max:255",
            ],
            'vlan_prefix' => [
                "required",
                "unique:teams",
                "integer",
                "min:1",
                "max:99",
            ],
        ]);

        $team = new Team($request->all());
        $team->group_id = auth()->user()->group->id;
        $team->save();
        return redirect(route('teams.show', [
            'team' => $team,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view('pages.teams.show', [
            'team' => $team,
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
    public function delete(Team $team)
    {
        // 全ての問題でdeploy settingのstatusが未展開かどうかを調べる
        $isDeletable = true;
        foreach ($team->deployedProblems as $problem) {
            if ($problem->pivot->status != "未展開") {
                $isDeletable = false;
                break;
            }
        }

        if (!$isDeletable) {
            dd('please destroy resources');
        }

        $team->delete();

        return redirect(route('teams.index'));
    }
}
