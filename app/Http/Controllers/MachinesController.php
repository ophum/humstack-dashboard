<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Machine;
use App\Models\Storage;
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
    public function edit(Problem $problem, Machine $machine)
    {
        return view('pages.problems.machines.update', [
            'problem' => $problem,
            'machine' => $machine,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem, Machine $machine)
    {
        $machine->fill($request->all());
        $machine->save();
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
    public function destroy(Problem $problem, Machine $machine)
    {
        // nicとstorageをすべてdetach
        $machine->attachedNics()->detach();
        $machine->attachedStorages()->detach();
        $machine->delete();
        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
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

    public function nicDetach(Problem $problem, Machine $machine, Network $network)
    {
        $machine->attachedNics()->detach($network->id);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }

    public function storage(Problem $problem, Machine $machine)
    {
        return view('pages.problems.machines.attach_storage', [
            'problem' => $problem,
            'machine' => $machine,
        ]);
    }

    public function storageAttach(Request $request, Problem $problem, Machine $machine)
    {
        $storage = Storage::find($request->storage_id);
        if ($storage === null) {
            dd('error storage not found');
        }
        $machine->attachedStorages()->attach($storage->id, [
            'order' => $request->order,
        ]);

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }
    
    public function storageDetach(Problem $problem, Machine $machine, Storage $storage)
    {
        $machine->attachedStorages()->detach($storage->id);
        
        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }
}
