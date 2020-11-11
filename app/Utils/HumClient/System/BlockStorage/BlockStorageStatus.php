<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageStatus
{
    public $state = "";

    public function __construct($data)
    {
        $this->state = $data['state'] ?? "";
    }
}
