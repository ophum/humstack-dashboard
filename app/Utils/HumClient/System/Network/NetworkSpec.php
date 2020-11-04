<?php

namespace App\Utils\HumClient\System\Network;

use App\Utils\HumClient\Meta\Meta;

class NetworkSpec
{
    public $id = "";
    public $ipv4CIDR = "";

    public function __construct($data)
    {
        $this->id = $data['id'] ?? "";
        $this->ipv4CIDR = $data['ipv4CIDR'] ?? "";
    }
}
