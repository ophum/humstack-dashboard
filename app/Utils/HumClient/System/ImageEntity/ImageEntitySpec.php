<?php

namespace App\Utils\HumClient\System\ImageEntity;

use App\Utils\HumClient\Meta\Meta;

class ImageEntitySpec
{
    public $type;
    public $hash;
    public ImageEntitySource $source;

    public function __construct($data)
    {
        $this->type = $data['type'] ?? null;
        $this->hash = $data['hash'] ?? null;
        $this->source = new ImageEntitySource($data['source'] ?? []);
    }
}
