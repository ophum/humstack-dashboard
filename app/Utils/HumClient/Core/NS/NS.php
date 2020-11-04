<?php

namespace App\Utils\HumClient\Core\NS;

use \App\Utils\HumClient\Meta\Meta;

class NS
{
    public Meta $meta;

    public function __construct()
    {
        $this->meta = new Meta();
        $this->meta->apiType = "corev0/namespace";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
