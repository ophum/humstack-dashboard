<?php

namespace App\Utils\HumClient\System\ImageEntity;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\System\ImageEntity\ImageEntity;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($groupID, $imageentityID)
    {
        $path = "$this->apiServerURL/api/v0/groups/$groupID/imageentities";
        if ($imageentityID !== "") {
            $path .= "/$imageentityID";
        }
        return $path;
    }

    public function get($groupID, $imageentityID)
    {
        return Response::One(
            "imageentity",
            ImageEntity::class,
            Http::get(
                $this->getPath($groupID, $imageentityID),
            )->json()
        );
    }

    public function list($groupID)
    {
        return Response::Any(
            "imageentities",
            ImageEntity::class,
            Http::get(
                $this->getPath($groupID, ""),
            )->json()
        );
    }

    public function create(ImageEntity $data)
    {
        return Response::One(
            "imageentity",
            ImageEntity::class,
            Http::post(
                $this->getPath($data->meta->group, ""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(ImageEntity $data)
    {
        return Response::One(
            "imageentity",
            ImageEntity::class,
            Http::put(
                $this->getPath($data->meta->group, $data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($groupID, $imageentityID)
    {
        return Response::One(
            "imageentity",
            ImageEntity::class,
            Http::delete(
                $this->getPath($groupID, $imageentityID),
                [],
            )->json()
        );
    }
}
