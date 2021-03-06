<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Network;

class NetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Problem $problem)
    {
        return view('pages.problems.networks.create', [
            'problem' => $problem,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Problem $problem)
    {
        $isExists = $problem->networks()->where('name', $request->name)->exists();
        if ($isExists) {
            dd("network `". $request->name . "` is already exists.");
        }

        $data = $request->all();
        
        if ($request->require_gateway === "require") {
            $data['require_gateway'] = true;
        }else {
            $data['require_gateway'] = false;
        }
        $network = new Network($data);
        $network->problem_id = $problem->id;
        $network->save();

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem, Network $network)
    {
        return view('pages.problems.networks.update', [
            'problem' => $problem,
            'network' => $network,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem, Network $network)
    {
        if ($network->name != $request->name) {
            $isExists = $problem->networks()->where('name', $request->name)->exists();
            if ($isExists) {
                dd("network `". $request->name ."` is already exists.");
            }
        }

        $data = $request->all();
        if ($request->require_gateway === "require") {
            $data['require_gateway'] = true;
        }else {
            $data['require_gateway'] = false;
        }
        $network->fill($data);
        $network->save();
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
    public function destroy(Problem $problem, Network $network)
    {
        $attachedMachines = $network->machines()->get();
        foreach ($attachedMachines as $m) {
            $m->attachedNics()->detach($network->id);
        }
        $network->delete();

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }
}
