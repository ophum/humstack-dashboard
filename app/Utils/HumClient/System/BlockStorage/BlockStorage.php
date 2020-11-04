<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorage
{
    public Meta $meta;
    public BlockStorageSpec $spec;

    public function __construct()
    {
        $this->meta = new Meta();
        $this->spec = new BlockStorageSpec();
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
