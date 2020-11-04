<?php

namespace App\Utils\HumClient\System\Network;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\System\Network\Network;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($groupID, $nsID, $netID)
    {
        $path = "$this->apiServerURL/api/v0/groups/$groupID/namespaces/$nsID/networks";
        if ($netID !== "") {
            $path .= "/$netID";
        }
        return $path;
    }

    public function get($groupID, $nsID, $netID)
    {
        return Response::One(
            "network",
            Network::class,
            Http::get(
                $this->getPath($groupID, $nsID, $netID),
            )->json()
        );
    }

    public function list($groupID, $nsID)
    {
        return Response::Any(
            "networks",
            Network::class,
            Http::get(
                $this->getPath($groupID, $nsID, ""),
            )->json()
        );
    }

    public function create(Network $data)
    {
        return Response::One(
            "network",
            Network::class,
            Http::post(
                $this->getPath($data->meta->group, $data->meta->namespace, ""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(Network $data)
    {
        return Response::One(
            "network",
            Network::class,
            Http::put(
                $this->getPath($data->meta->group, $data->meta->namespace, $data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($groupID, $nsID, $netID)
    {
        return Response::One(
            "network",
            Network::class,
            Http::delete(
                $this->getPath($groupID, $nsID, $netID),
                [],
            )->json()
        );
    }
}
