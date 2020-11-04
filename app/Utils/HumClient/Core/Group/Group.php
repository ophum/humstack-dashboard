<?php

namespace App\Utils\HumClient\Core\Group;

use \App\Utils\HumClient\Meta\Meta;

class Group
{
    public Meta $meta;

    public function __construct()
    {
        $this->meta = new Meta();
        $this->meta->apiType = "corev0/group";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
