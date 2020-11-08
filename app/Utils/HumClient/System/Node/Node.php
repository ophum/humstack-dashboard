<?php

namespace App\Utils\HumClient\System\Node;

use App\Utils\HumClient\Meta\Meta;

class Node
{
    public Meta $meta;
    public NodeSpec $spec;
    public NodeStatus $status;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new NodeSpec($data['spec'] ?? []);
        $this->status = new NodeStatus($data['status'] ?? []);
        $this->meta->apiType = "systemv0/node";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
