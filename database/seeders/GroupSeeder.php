<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [
                'name' => 'group1',
            ],
            [
                'name' => 'group2',
            ]
        ];


        foreach($groups as $group) {
            (new \App\Models\Group($group))->save();
        }
    }
}
