<?php

namespace App\Utils\HumClient\System\ImageEntity;

use App\Utils\HumClient\Meta\Meta;

class ImageEntitySource
{
    public $type;
    public $namespace;
    public $blockStorageID;
    public $imageName;
    public $imageTag;

    public function __construct($data)
    {
        $this->type = $data['type'] ?? null;
        $this->namespace = $data['namespace'] ?? null;
        $this->blockStorageID = $data['blockStorageID'] ?? null;
        $this->imageName = $data['imageName'] ?? null;
        $this->imageTag = $data['imageTag'] ?? null;
    }
}
