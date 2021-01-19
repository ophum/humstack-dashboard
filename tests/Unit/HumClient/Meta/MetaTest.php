<?php

namespace Tests\Unit\HumClient\Meta;

use App\Utils\HumClient\Meta\Meta;
use Tests\TestCase;

class MetaTest extends TestCase
{
    /**
     * @dataProvider metaProvider
     */
    public function testMetaEmptyAnnotations(array $data) {
        $meta = new Meta($data);

        $this->assertNull($meta->annotations);
    }

    public function metaProvider() {
        return [
            'null' => [
                [
                    'annotations' => null,
                ],
            ],
            'empty' => [
                [
                    'annotations' => [],
                ]
            ],
        ];
    }
}
