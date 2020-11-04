<?php

namespace App\Utils\HumClient;

use App\Utils\HumClient\Core;
use App\Utils\HumClient\System;

class Clients
{
    private Core\NS\Client $nsClient;
    private Core\Group\Client $groupClient;
    private System\Network\Client $netClient;
    private System\BlockStorage\Client $bsClient;
    private System\VirtualMachine\Client $vmClient;

    public function __construct($apiServerURL)
    {
        $this->nsClient = new Core\NS\Client($apiServerURL);
        $this->groupClient = new Core\Group\Client($apiServerURL);
        $this->netClient = new System\Network\Client($apiServerURL);
        $this->bsClient = new System\BlockStorage\Client($apiServerURL);
        $this->vmClient = new System\VirtualMachine\Client($apiServerURL);
    }

    public function Namespace()
    {
        return $this->nsClient;
    }

    public function Group()
    {
        return $this->groupClient;
    }

    public function Network()
    {
        return $this->netClient;
    }

    public function BlockStorage()
    {
        return $this->bsClient;
    }

    public function VirtualMachine()
    {
        return $this->vmClient;
    }
}
