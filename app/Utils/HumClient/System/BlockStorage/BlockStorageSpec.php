<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageSpec
{
    public $requestSize = "";
    public $limitSize = "";
    public BlockStorageFrom $from;

    public function __construct()
    {
        $this->from = new BlockStorageFrom();
    }
}
