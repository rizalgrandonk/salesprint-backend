<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    function createUsers(): void
    {
        \App\Models\User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123123123123',
            'address' => 'Surabaya',
            'province_id' => '11',
            'province' => 'Jawa Timur',
            'city_id' => '444',
            'city' => 'Kota Surabaya',
            'postal_code' => '66666',
            'image' => 'https://source.unsplash.com/random/?person user',
        ]);
        \App\Models\User::create([
            'name' => 'Grandonk User',
            'email' => 'grandonkuser@gmail.com',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123123123123',
            'address' => 'Sleman',
            'province_id' => '5',
            'province' => 'DI Yogyakarta',
            'city_id' => '419',
            'city' => 'Sleman',
            'postal_code' => '66666',
            'image' => 'https://source.unsplash.com/random/?musician',
        ]);
        \App\Models\User::create([
            'name' => 'Seller',
            'email' => 'seller@gmail.com',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123123123123',
            'address' => 'Jakarta Selatan',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '153',
            'city' => 'Jakarta Selatan',
            'postal_code' => '66666',
            'image' => 'https://source.unsplash.com/random/?seller person',
        ]);
        \App\Models\User::create([
            'name' => 'Grandonk Seller',
            'email' => 'grandonkseller@gmail.com',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123123123123',
            'address' => 'Jakarta Pusat',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '152',
            'city' => 'Jakarta Pusat',
            'postal_code' => '66666',
            'image' => 'https://source.unsplash.com/random/?metal fans',
        ]);
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123123123123',
            'address' => 'Surabaya',
            'province_id' => '11',
            'province' => 'Jawa Timur',
            'city_id' => '444',
            'city' => 'Kota Surabaya',
            'postal_code' => '66666',
            'image' => 'https://source.unsplash.com/random/?admin person',
        ]);
    }

    function createCategories(): void
    {
        $category_names = [
            'Electronics',
            'Clothing',
            'Home Decor',
            'Books',
            'Toys',
            'Furniture',
            'Jewelry',
            'Sports',
            'Beauty'
        ];
        foreach ($category_names as $name) {
            \App\Models\Category::create([
                'name' => implode(" ", array_map(fn ($val) => Str::ucfirst($val), explode(" ", $name))),
                'slug' => Str::slug($name),
                'image' => 'https://source.unsplash.com/random/?' . $name
            ]);
        }
    }

    function createStores(): array
    {
        $store_categories = [
            [
                'name' => 'New Product',
                'slug' => Str::slug('New Product'),
                'image' => 'https://source.unsplash.com/random/?new product'
            ],
            [
                'name' => 'Top Seller',
                'slug' => Str::slug('Top Seller'),
                'image' => 'https://source.unsplash.com/random/?top seller'
            ],
        ];

        $store_1 = \App\Models\Store::create([
            'name' => 'Grandonk Merch',
            'slug' => Str::slug('Grandonk Merch'),
            'address' => 'Jakarta Pusat',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '152',
            'city' => 'Jakarta Pusat',
            'postal_code' => '66666',
            'status' => 'approved',
            'image' => 'https://source.unsplash.com/random/?clothing store',
            'user_id' => \App\Models\User::where('email', 'grandonkseller@gmail.com')->first()->id
        ]);

        $store_2 = \App\Models\Store::create([
            'name' => 'Upscale Store',
            'slug' => Str::slug('Upscale Store'),
            'address' => 'Jakarta Selatan',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '153',
            'city' => 'Jakarta Selatan',
            'postal_code' => '66666',
            'status' => 'approved',
            'image' => 'https://source.unsplash.com/random/?sneakers store',
            'user_id' => \App\Models\User::where('email', 'seller@gmail.com')->first()->id
        ]);

        $store_1->store_categories()->createMany($store_categories);
        $store_2->store_categories()->createMany($store_categories);

        $store_1->store_banners()->createMany([
            ['image' => 'https://source.unsplash.com/random/?band merchandise'],
            ['image' => 'https://source.unsplash.com/random/?metal band'],
        ]);
        $store_2->store_banners()->createMany([
            ['image' => 'https://source.unsplash.com/random/?sneakers'],
            ['image' => 'https://source.unsplash.com/random/?shoes store'],
        ]);

        return [$store_1->id, $store_2->id];
    }

    public function run(): void
    {
        $this->createUsers();
        $this->createCategories();
        $createdStoreIds = $this->createStores();
    }
}
