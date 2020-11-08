<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorageFromHTTP
{
    public $url = "";

    public function __construct($data)
    {
        $this->url = $data['url'] ?? "";
    }
}
