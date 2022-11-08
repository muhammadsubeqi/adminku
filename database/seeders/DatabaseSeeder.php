<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'email' => 'user@gmail.com',
                'password' => Hash::make('user1234'),
                'role' => 'user'
            ]
        ]);

        DB::table('data_users')->insert([
            [
                'users_id' => '1',
                'name' => 'admin'
            ],
            [
                'users_id' => '2',
                'name' => 'user'
            ]
        ]);
    }
}
