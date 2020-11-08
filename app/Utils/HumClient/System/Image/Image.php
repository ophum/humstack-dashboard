<?php

namespace App\Utils\HumClient\System\Image;

use App\Utils\HumClient\Meta\Meta;

class Image
{
    public Meta $meta;
    public ImageSpec $spec;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new ImageSpec($data['spec'] ?? []);
        $this->meta->apiType = "systemv0/image";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
