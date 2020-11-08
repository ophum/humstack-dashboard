<?php

namespace App\Utils\HumClient\System\Node;

use App\Utils\HumClient\Meta\Meta;

class NodeStatus
{
    public $state;
    public $requestedVcpus;
    public $requestedMemory;

    public function fill($data)
    {
        $this->state = $data['state'] ?? "";
        $this->requestedVcpus = $data['requestedVcpus'] ?? "";
        $this->requestedMemory = $data['requestedMemory'] ?? "";
    }
}
