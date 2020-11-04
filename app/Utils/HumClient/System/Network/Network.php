<?php

namespace App\Utils\HumClient\System\Network;

use App\Utils\HumClient\Meta\Meta;

class NetworkSpec
{
    public $id = "";
    public $ipv4CIDR = "";
}

class Network
{
    public Meta $meta;
    public NetworkSpec $spec;

    public function __construct()
    {
        $this->meta = new Meta();
        $this->spec = new NetworkSpec();
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
