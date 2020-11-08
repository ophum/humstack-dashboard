<?php

namespace App\Utils\HumClient\System\Node;

use App\Utils\HumClient\Meta\Meta;

class NodeSpec
{
    public $address;
    public $limitVcpus;
    public $limitMemory;

    public function __construct($data)
    {
        $this->address = $data['address'] ?? "";
        $this->limitVcpus = $data['limitVcpus'] ?? "";
        $this->limitMemory = $data['limitMemory'] ?? "";
    }
}
