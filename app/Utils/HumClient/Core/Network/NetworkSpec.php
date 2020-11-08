<?php

namespace App\Utils\HumClient\Core\Network;

use App\Utils\HumClient\Meta\Meta;
use App\Utils\HumClient\System\NodeNetwork\NodeNetwork;

class NetworkSpec
{
    public $id = "";
    public $ipv4CIDoR = "";
    public NodeNetwork $template;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? "";
        $this->ipv4CIDR = $data['ipv4CIDR'] ?? "";
        $this->template = new NodeNetwork($data['template'] ?? []);
    }
}
