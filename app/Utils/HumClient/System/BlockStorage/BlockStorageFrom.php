<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageFrom
{
    public $type = "";
    public BlockStorageFromBaseImage $baseImage;

    public function __construct($data)
    {
        $this->type = $data['type'] ?? "";
        $this->baseImage = new BlockStorageFromBaseImage($data['baseImage'] ?? []);
    }
}
