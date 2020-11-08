<?php

namespace App\Utils\HumClient\System\Image;

use App\Utils\HumClient\Meta\Meta;

class ImageSpec
{
    public $entityMap = null;

    public function __construct($data)
    {
        $this->entityMap = $data['entityMap'] ?? null;
    }
}
