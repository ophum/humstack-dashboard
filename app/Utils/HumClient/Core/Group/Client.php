<?php

namespace App\Utils\HumClient\Core\Group;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\Core\Group\Group;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($group)
    {
        $path = "$this->apiServerURL/api/v0/groups";
        if ($group !== "") {
            $path .= "/$group";
        }
        return $path;
    }

    public function get($groupID)
    {
        return Response::One(
            "group",
            Group::class,
            Http::get(
                $this->getPath($groupID),
            )->json()
        );
    }

    public function list()
    {
        return Response::Any(
            "groups",
            Group::class,
            Http::get(
                $this->getPath(""),
            )->json()
        );
    }

    public function create(Group $data)
    {
        return Response::One(
            "group",
            Group::class,
            Http::post(
                $this->getPath(""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(Group $data)
    {
        return Response::One(
            "group",
            Group::class,
            Http::put(
                $this->getPath($data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($groupID)
    {
        return Response::One(
            "group",
            Group::class,
            Http::delete(
                $this->getPath($groupID),
                [],
            )->json()
        );
    }
}
