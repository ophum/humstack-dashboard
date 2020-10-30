<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Machine;
use App\Models\Network;

class MachinesController extends Controller
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
        return view('pages.problems.machines.create', [
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
        $machine = new Machine($request->all());
        $machine->problem_id = $problem->id;
        $machine->save();

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

    public function nic(Problem $problem, Machine $machine)
    {
        return view('pages.problems.machines.attach_nic', [
            'problem' => $problem,
            'machine' => $machine,
        ]);
    }

    public function nicAttach(Request $request, Problem $problem, Machine $machine)
    {
        $network = Network::find($request->network_id);
        if ($network === null) {
            dd('error network not found');
        }
        $machine->attachedNics()->attach($network->id, [
            'ipv4_address' => $request->ipv4_address,
            'default_gateway' => $request->default_gateway,
            'order' => $request->order,
        ]);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }
}
