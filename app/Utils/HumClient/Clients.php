<?php

namespace App\Utils\HumClient;

use App\Utils\HumClient\Core;
use App\Utils\HumClient\System;

class Clients
{
    private Core\NS\Client $nsClient;
    private Core\Group\Client $groupClient;
    private Core\Network\Client $netClient;
    private System\NodeNetwork\Client $nodeNetClient;
    private System\BlockStorage\Client $bsClient;
    private System\VirtualMachine\Client $vmClient;
    private System\Node\Client $nodeClient;
    private System\Image\Client $imageClient;

    public function __construct($apiServerURL)
    {
        $this->nsClient = new Core\NS\Client($apiServerURL);
        $this->groupClient = new Core\Group\Client($apiServerURL);
        $this->netClient = new Core\Network\Client($apiServerURL);
        $this->nodeNetClient = new System\NodeNetwork\Client($apiServerURL);
        $this->bsClient = new System\BlockStorage\Client($apiServerURL);
        $this->vmClient = new System\VirtualMachine\Client($apiServerURL);
        $this->nodeClient = new System\Node\Client($apiServerURL);
        $this->imageClient = new System\Image\Client($apiServerURL);
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

    public function NodeNetwork()
    {
        return $this->nodeNetClient;
    }

    public function BlockStorage()
    {
        return $this->bsClient;
    }

    public function VirtualMachine()
    {
        return $this->vmClient;
    }

    public function Node()
    {
        return $this->nodeClient;
    }

    public function Image()
    {
        return $this->imageClient;
    }
}
