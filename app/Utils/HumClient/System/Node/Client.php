<?php

namespace App\Utils\HumClient\System\Node;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\System\Node\Node;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($nodeID)
    {
        $path = "$this->apiServerURL/api/v0/nodes";
        if ($nodeID !== "") {
            $path .= "/$nodeID";
        }
        return $path;
    }

    public function get($nodeID)
    {
        return Response::One(
            "node",
            Node::class,
            Http::get(
                $this->getPath($nodeID),
            )->json()
        );
    }

    public function list()
    {
        return Response::Any(
            "nodes",
            Node::class,
            Http::get(
                $this->getPath(""),
            )->json()
        );
    }

    public function create(Node $data)
    {
        return Response::One(
            "node",
            Node::class,
            Http::post(
                $this->getPath($data->meta->group, $data->meta->namespace, ""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(Node $data)
    {
        return Response::One(
            "node",
            Node::class,
            Http::put(
                $this->getPath($data->meta->group, $data->meta->namespace, $data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($nodeID)
    {
        return Response::One(
            "node",
            Node::class,
            Http::delete(
                $this->getPath($nodeID),
                [],
            )->json()
        );
    }
}
