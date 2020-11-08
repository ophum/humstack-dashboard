<?php

namespace App\Utils\HumClient\System\NodeNetwork;

use App\Utils\HumClient\Meta\Meta;

class NodeNetworkSpec
{
    public $id = "";
    public $ipv4CIDR = "";

    public function __construct($data)
    {
        $this->id = $data['id'] ?? "";
        $this->ipv4CIDR = $data['ipv4CIDR'] ?? "";
    }
}
