<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    public function run()
    {
        \DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin',
            'role' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'password' =>Hash::make('admin')
        ]);

        \DB::table('locations')->insert([
            'name' => 'Main Counter',
        ]);

    }
}
