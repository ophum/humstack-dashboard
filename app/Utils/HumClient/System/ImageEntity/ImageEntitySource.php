<?php

namespace App\Utils\HumClient\System\ImageEntity;

use App\Utils\HumClient\Meta\Meta;

class ImageEntitySource
{
    public $namespace;
    public $blockStorageID;

    public function __construct($data)
    {
        $this->namespace = $data['namespace'] ?? null;
        $this->blockStorageID = $data['blockStorageID'];
    }
}
