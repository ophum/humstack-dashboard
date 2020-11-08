<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nodes = [
            [
                'name' => 'developvbox',
                'limit_vcpus' => '24',
                'limit_memory' => '128G',
            ],
            [
                'name' => 'node01',
                'limit_vcpus' => '24',
                'limit_memory' => '128G',
            ],
        ];

        foreach ($nodes as $n) {
            (new \App\Models\Node($n))->save();
        }
    }
}
