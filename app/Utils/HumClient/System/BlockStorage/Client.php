<?php

namespace App\Utils\HumClient\System\BlockStorage;

use Illuminate\Support\Facades\Http;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($groupID, $nsID, $bsID)
    {
        $path = "$this->apiServerURL/api/v0/groups/$groupID/namespaces/$nsID/blockstorages";
        if ($bsID !== "") {
            $path .= "/$bsID";
        }
        return $path;
    }

    public function get($groupID, $nsID, $bsID)
    {
        return Http::get(
            $this->getPath($groupID, $nsID, $bsID),
        )->json();
    }

    public function list($groupID, $nsID)
    {
        return Http::get(
            $this->getPath($groupID, $nsID, ""),
        )->json();
    }

    public function create(BlockStorage $data)
    {
        return Http::post(
            $this->getPath($data->meta->group, $data->meta->namespace, ""),
            $data->toArray(),
        )->json();
    }

    public function update(NS $data)
    {
        return Http::put(
            $this->getPath($data->meta->group, $data->meta->namespace, $data->meta->id),
            $data->toArray(),
        )->json();
    }

    public function delete($groupID, $nsID, $bsID)
    {
        return Http::delete(
            $this->getPath($groupID, $nsID, $bsID),
            [],
        )->json();
    }
}
