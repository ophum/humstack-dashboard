<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageFromBaseImage
{
    public $imageName = "";
    public $tag = "";

    public function __construct($data)
    {
        $this->imageName = $data['imageName'] ?? "";
        $this->tag = $data["tag"] ?? "";
    }
}
