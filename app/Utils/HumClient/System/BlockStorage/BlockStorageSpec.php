<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageSpec
{
    public $requestSize = "";
    public $limitSize = "";
    public BlockStorageFrom $from;

    public function __construct($data)
    {
        $this->requestSize = $data['requestSize'] ?? "";
        $this->limitSize = $data['limitSize'] ?? "";
        $this->from = new BlockStorageFrom($data['from'] ?? []);
    }
}
