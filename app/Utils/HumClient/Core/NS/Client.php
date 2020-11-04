<?php

namespace App\Utils\HumClient\Core\NS;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\Core\NS\NS;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($group, $ns)
    {
        $path = "$this->apiServerURL/api/v0/groups/$group/namespaces";
        if ($ns !== "") {
            $path .= "/$ns";
        }
        return $path;
    }

    public function get($groupID, $namespaceID)
    {
        return Response::One(
            "namespace",
            NS::class,
            Http::get(
                $this->getPath($groupID, $namespaceID),
            )->json()
        );
    }

    public function list($groupID)
    {
        return Response::Any(
            "namespaces",
            NS::class,
            Http::get(
                $this->getPath($groupID, ""),
            )->json()
        );
    }

    public function create(NS $data)
    {
        return Response::One(
            "namespace",
            NS::class,
            Http::post(
                $this->getPath($data->meta->group, ""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(NS $data)
    {
        return Response::One(
            "namespace",
            NS::class,
            Http::put(
                $this->getPath($data->meta->group, $data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($groupID, $namespaceID)
    {
        return Response::One(
            "namespace",
            NS::class,
            Http::delete(
                $this->getPath($groupID, $namespaceID),
                [],
            )->json()
        );
    }
}
