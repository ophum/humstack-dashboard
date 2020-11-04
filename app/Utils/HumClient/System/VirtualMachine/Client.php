<?php

namespace App\Utils\HumClient\System\VirtualMachine;

use Illuminate\Support\Facades\Http;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($groupID, $nsID, $vmID)
    {
        $path = "$this->apiServerURL/api/v0/groups/$groupID/namespaces/$nsID/virtualmachines";
        if ($vmID !== "") {
            $path .= "/$vmID";
        }
        return $path;
    }

    public function get($groupID, $nsID, $vmID)
    {
        return Http::get(
            $this->getPath($groupID, $nsID, $vmID),
        )->json();
    }

    public function list($groupID, $nsID)
    {
        return Http::get(
            $this->getPath($groupID, $nsID, ""),
        )->json();
    }

    public function create(VirtualMachine $data)
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

    public function delete($groupID, $nsID, $vmID)
    {
        return Http::delete(
            $this->getPath($groupID, $nsID, $vmID),
            [],
        )->json();
    }
}
