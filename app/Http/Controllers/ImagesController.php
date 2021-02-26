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
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

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
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
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
        $user = auth()->user();
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $res = $clients->Image()->get($user->group->name, $id);
        $image = $res->data;

        $entityStatusMap = [];
        foreach($image->spec->entityMap ?? [] as $tag => $entityID) {
            $res = $clients->ImageEntity()->get($user->group->name, $entityID);
            $entityStatusMap[$tag] = $res->data->status->state ?? "";
        }

        return view('pages.images.show', [
            'image' => $image,
            'entityStatusMap' => $entityStatusMap,
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

    public function untag(Request $request, $imageName) {
        $tag = $request->tag;
      
        $user = auth()->user();
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));

        $res = $clients->Image()->get($user->group->name, $imageName);
        $image = $res->data;  
        if (isset($image->spec->entityMap[$tag])) {
            $entityID = $image->spec->entityMap[$tag];
            unset($image->spec->entityMap[$tag]);
            if (count($image->spec->entityMap) == 0) {
                $image->spec->entityMap = null;
            }
            $res = $clients->Image()->update($image);

            $entity = $clients->ImageEntity()->get($user->group->name, $entityID)->data;
            $entity->meta->deleteState = "Delete";
            $clients->ImageEntity()->update($entity);
        }

        return redirect(route('images.show', [
            'imageName' => $imageName,
        ]));
    }
}
