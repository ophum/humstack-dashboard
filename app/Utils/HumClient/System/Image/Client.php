<?php

namespace App\Utils\HumClient\System\Image;

use Illuminate\Support\Facades\Http;
use App\Utils\HumClient\Meta\Response;
use App\Utils\HumClient\System\Image\Image;

class Client
{
    private $apiServerURL = "";
    public function __construct($apiServerURL)
    {
        $this->apiServerURL = $apiServerURL;
    }

    private function getPath($groupID, $imageID)
    {
        $path = "$this->apiServerURL/api/v0/groups/$groupID/images";
        if ($imageID !== "") {
            $path .= "/$imageID";
        }
        return $path;
    }

    public function get($groupID, $imageID)
    {
        return Response::One(
            "image",
            Image::class,
            Http::get(
                $this->getPath($groupID, $imageID),
            )->json()
        );
    }

    public function list($groupID)
    {
        return Response::Any(
            "images",
            Image::class,
            Http::get(
                $this->getPath($groupID, ""),
            )->json()
        );
    }

    public function create(Image $data)
    {
        return Response::One(
            "image",
            Image::class,
            Http::post(
                $this->getPath($data->meta->group, ""),
                $data->toArray(),
            )->json()
        );
    }

    public function update(Image $data)
    {
        return Response::One(
            "image",
            Image::class,
            Http::put(
                $this->getPath($data->meta->group, $data->meta->id),
                $data->toArray(),
            )->json()
        );
    }

    public function delete($groupID, $imageID)
    {
        return Response::One(
            "image",
            Image::class,
            Http::delete(
                $this->getPath($groupID, $imageID),
                [],
            )->json()
        );
    }
}
