<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\HumClient\Clients;
use App\Utils\HumClient\System\Image\Image;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));

        $res = $clients->Image()->list($user->group->name);
        $imageList = $res->data;

        return view('pages.images.index', [
            'imageList' => $imageList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $clients = new Clients(config("apiServerURL", "http://localhost:8080"));
        $res = $clients->Image()->get($user->group->name, $request->name);
        if ($res->code !== 404 || $res->data !== null) {
            dd("already exists");
        }

        $image = new Image([
            'meta' => [
                'id' => $request->name,
                'name' => $request->name,
                'group' => $user->group->name,
                'annotations' => [
                    'description' => $request->description,
                ],
            ],
        ]);

        $clients->image()->create($image);
        return redirect(route('images'));
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
}
