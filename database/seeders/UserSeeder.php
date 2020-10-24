<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'test',
                'email' => 'test@test.test',
                'password' => \Hash::make('testtest'),
                'group_id' => 1,
            ],
        ];

        foreach ($users as $user) {
            (new \App\Models\User($user))->save();
        }
    }
}
