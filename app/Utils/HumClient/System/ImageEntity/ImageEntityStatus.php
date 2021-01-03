<?php

namespace App\Utils\HumClient\System\ImageEntity;

class ImageEntityStatus
{
    public $state;

    public function __construct($data)
    {
        $this->state = $data['state'] ?? null;
    }
}
