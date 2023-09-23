<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => bcrypt('66666666')
        ]);
        $seller = User::create([
            'name' => 'Seller',
            'email' => 'seller@gmail.com',
            'role' => 'seller',
            'password' => bcrypt('66666666')
        ]);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('66666666')
        ]);

        // JWTAuth::fromUser($user);
        // JWTAuth::fromUser($seller);
        // JWTAuth::fromUser($admin);
    }
}
