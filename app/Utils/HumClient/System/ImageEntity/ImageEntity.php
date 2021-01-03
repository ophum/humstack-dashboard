<?php

namespace App\Utils\HumClient\System\ImageEntity;

use App\Utils\HumClient\Meta\Meta;

class ImageEntity
{
    public Meta $meta;
    public ImageEntitySpec $spec;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new ImageEntitySpec($data['spec'] ?? []);
        $this->status = new ImageEntityStatus($data['status'] ?? []);
        $this->meta->apiType = "systemv0/imageentity";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
