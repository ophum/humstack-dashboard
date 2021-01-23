<?php

namespace App\Utils\HumClient\Meta;

class Meta
{
    public $id;
    public $name;
    public $group;
    public $namespace;
    public $annotations = null;
    public $deleteState = "";
    public $apiType;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? "";
        $this->name = $data['name'] ?? "";
        $this->group = $data['group'] ?? "";
        $this->namespace = $data['namespace'] ?? "";
        if (empty($data['annotations'])) {
            $this->annotations = null;
        } else {
            $this->annotations = $data['annotations'];
        }
        $this->deleteState = $data['deleteState'] ?? "";
        $this->apiType = $data['apiType'] ?? "";
    }
}
