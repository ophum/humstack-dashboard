<?php

namespace App\Utils\HumClient\Core\Group;

use Illuminate\Support\Facades\Http;

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
        return Http::get(
            $this->getPath($groupID),
        )->json();
    }

    public function list()
    {
        return Http::get(
            $this->getPath(""),
        )->json();
    }

    public function create(Group $data)
    {
        return Http::post(
            $this->getPath(""),
            $data->toArray(),
        )->json();
    }

    public function update(Group $data)
    {
        return Http::put(
            $this->getPath($data->meta->id),
            $data->toArray(),
        )->json();
    }

    public function delete($groupID)
    {
        return Http::delete(
            $this->getPath($groupID),
            [],
        )->json();
    }
}
