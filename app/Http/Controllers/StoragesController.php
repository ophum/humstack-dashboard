<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Storage;
use App\Utils\HumClient\Clients;

class StoragesController extends Controller
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
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        $res = $clients->Image()->List($problem->group->name);

        $imageList = $res->data;
        return view('pages.problems.storages.create', [
            'problem' => $problem,
            'imageList' => $imageList,
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
        $storage = new Storage($request->all());
        $storage->problem_id = $problem->id;
        $storage->save();

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
    public function edit(Problem $problem, Storage $storage)
    {
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        $res = $clients->Image()->List($problem->group->name);

        $imageList = $res->data;
        return view('pages.problems.storages.update', [
            'problem' => $problem,
            'storage' => $storage,
            'imageList' => $imageList,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem, Storage $storage)
    {
        $storage->fill($request->all());
        $storage->save();
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
    public function destroy(Problem $problem, Storage $storage)
    {
        $attachedMachine = $storage->machines()->first();
        if ($attachedMachine !== null) {
            $attachedMachine->attachedStorages()->detach($storage->id);
        }
        $storage->delete();

        return redirect(route('problems.show', [
            'problem' => $problem,
        ]));
    }
}
