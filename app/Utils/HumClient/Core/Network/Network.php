<?php

namespace App\Utils\HumClient\Core\Network;

use App\Utils\HumClient\Meta\Meta;

class Network
{
    public Meta $meta;
    public NetworkSpec $spec;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new NetworkSpec($data['spec'] ?? []);
        $this->meta->apiType = "corev0/network";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
