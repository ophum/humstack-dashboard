<?php

namespace App\Utils\HumClient\System\NodeNetwork;

use App\Utils\HumClient\Meta\Meta;

class NodeNetwork
{
    public Meta $meta;
    public NodeNetworkSpec $spec;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new NodeNetworkSpec($data['spec'] ?? []);
        $this->meta->apiType = "systemv0/network";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
