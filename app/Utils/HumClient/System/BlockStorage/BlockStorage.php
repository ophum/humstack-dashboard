<?php

namespace App\Utils\HumClient\System\BlockStorage;

use App\Utils\HumClient\Meta\Meta;

class BlockStorage
{
    public Meta $meta;
    public BlockStorageSpec $spec;
    public BlockStorageStatus $status;

    public function __construct($data)
    {
        $this->meta = new Meta($data['meta'] ?? []);
        $this->spec = new BlockStorageSpec($data['spec'] ?? []);
        $this->status = new BlockStorageStatus($data['status'] ?? []);
        $this->meta->apiType = "systemv0/blockstorage";
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
