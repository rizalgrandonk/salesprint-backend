<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */

    function createUsers(): array {
        $user1 = \App\Models\User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'username' => 'user666',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123239483123',
            'image' => 'https://source.unsplash.com/random/?person%20user',
        ]);
        $user2 = \App\Models\User::create([
            'name' => 'Grandonk User',
            'email' => 'grandonkuser@gmail.com',
            'username' => 'grandonkuser666',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08242301323123',
            'image' => 'https://source.unsplash.com/random/?musician',
        ]);
        $user3 = \App\Models\User::create([
            'name' => 'Seller',
            'email' => 'seller@gmail.com',
            'username' => 'seller666',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08154536315424',
            'image' => 'https://source.unsplash.com/random/?seller%20person',
        ]);
        $user4 = \App\Models\User::create([
            'name' => 'Grandonk Seller',
            'email' => 'grandonkseller@gmail.com',
            'username' => 'grandonkseller666',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123191347123',
            'image' => 'https://source.unsplash.com/random/?metal%20fans',
        ]);
        $user5 = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin666',
            'role' => 'admin',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123171321243',
            'image' => 'https://source.unsplash.com/random/?admin%20person',
        ]);

        $result = [
            'admin' => [$user5->id],
            'seller' => [$user3->id, $user4->id],
            'user' => [$user1->id, $user2->id],
        ];

        for ($i = 0; $i < 100; $i++) {
            $nameUser = fake()->firstName() . fake()->randomNumber(3, true);
            $createdUser = \App\Models\User::create([
                'name' => $nameUser,
                'email' => strtolower($nameUser) . '@gmail.com',
                'username' => strtolower($nameUser),
                'role' => 'user',
                'password' => bcrypt('66666666'),
                'remember_token' => Str::random(10),
                'phone_number' => fake()->phoneNumber(),
                'image' => 'https://source.unsplash.com/random/?person' . fake()->randomLetter(),
            ]);
            array_push($result['user'], $createdUser->id);
        }

        return $result;
    }

    function createCategories(): array {
        $category_names = [
            'Elektronik' => [
                'High-End Smartphone',
                'Notebook Murah'
            ],
            'Pakaian' => [
                'Kaos',
                'Celana Panjang'
            ],
            'Dekorasi' => [
                'Lampu Meja',
                'Poster Band'
            ],
            'Buku' => [
                'Buku Fiksi',
                'Komik'
            ],
            'Mainan' => [
                'Gaming CD',
                'Diecast'
            ],
            'Furniture' => [
                'Meja Kayu',
                'Sofa'
            ],
            'Olahraga' => [
                'Bola Sepak',
                'Jersey Sepak Bola'
            ],
            'Kecantikan' => [
                'Set Parfum',
                'Kalung Liontin'
            ],
            'Sepatu' => [
                'Sneakers',
                'Sepatu Sepak Bola'
            ],
        ];
        $createdIds = [];
        foreach ($category_names as $name => $val) {
            $createdCategory = \App\Models\Category::create([
                'name' => implode(" ", array_map(fn($val) => Str::ucfirst($val), explode(" ", $name))),
                'slug' => Str::slug($name),
                'image' => 'https://source.unsplash.com/random/?' . urlencode($name)
            ]);

            array_push($createdIds, ['id' => $createdCategory->id, 'prodNames' => $val]);
        }
        return $createdIds;
    }

    function createStores(): array {
        $store_categories = [
            [
                'name' => 'Produk Baru',
                'slug' => Str::slug('Produk Baru'),
                'image' => 'https://source.unsplash.com/random/?new%20product'
            ],
            [
                'name' => 'Top Seller',
                'slug' => Str::slug('Top Seller'),
                'image' => 'https://source.unsplash.com/random/?top%20seller'
            ],
        ];

        $store_1 = \App\Models\Store::create([
            'name' => 'Grandonk Merch',
            'slug' => Str::slug('Grandonk Merch'),
            'phone_number' => '0812345678987',
            'address' => 'Jakarta Pusat',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '152',
            'city' => 'Jakarta Pusat',
            'postal_code' => '66666',
            'status' => 'approved',
            'image' => 'https://source.unsplash.com/random/?clothing%20store',
            'store_description' => fake()->paragraph(random_int(3, 5)),
            'user_id' => \App\Models\User::where('email', 'grandonkseller@gmail.com')->first()->id
        ]);

        $store_2 = \App\Models\Store::create([
            'name' => 'Upscale Store',
            'slug' => Str::slug('Upscale Store'),
            'phone_number' => '0898787653412',
            'address' => 'Jakarta Selatan',
            'province_id' => '6',
            'province' => 'DKI Jakarta',
            'city_id' => '153',
            'city' => 'Jakarta Selatan',
            'postal_code' => '66666',
            'status' => 'approved',
            'image' => 'https://source.unsplash.com/random/?sneakers%20store',
            'store_description' => fake()->paragraph(random_int(3, 5)),
            'user_id' => \App\Models\User::where('email', 'seller@gmail.com')->first()->id
        ]);

        $store_1->store_categories()->createMany($store_categories);
        $store_2->store_categories()->createMany($store_categories);

        $store_1->store_banners()->createMany([
            ['image' => 'https://source.unsplash.com/random/?band%20merchandise'],
            ['image' => 'https://source.unsplash.com/random/?metal%20band'],
        ]);
        $store_2->store_banners()->createMany([
            ['image' => 'https://source.unsplash.com/random/?sneakers'],
            ['image' => 'https://source.unsplash.com/random/?shoes%20store'],
        ]);

        for ($i = 0; $i < 30; $i++) {
            $name = fake()->name();
            \App\Models\Store::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'province_id' => '6',
                'province' => 'DKI Jakarta',
                'city_id' => '153',
                'city' => 'Jakarta Selatan',
                'postal_code' => '66666',
                'status' => fake()->randomElement(['on_review', 'approved', 'rejected']),
                'image' => 'https://source.unsplash.com/random/?' . fake()->word(),
                'store_description' => fake()->paragraph(random_int(3, 5)),
                'user_id' => \App\Models\User::where('email', 'seller@gmail.com')->first()->id
            ]);
        }

        return [$store_1->id, $store_2->id];
    }

    function createVariantTypes(): array {
        $optionMap = [
            'Ukuran' => ['S', 'M', 'L', 'XL'],
            'Warna' => ['Hitam', 'Putih', 'Abu - abu'],
        ];
        $types = [
            'Ukuran',
            'Warna',
        ];
        $createdIds = [];
        foreach ($types as $name) {
            $created = \App\Models\VariantType::create([
                'name' => $name
            ]);

            array_push($createdIds, ['id' => $created->id, 'options' => $optionMap[$name]]);
        }
        return $createdIds;
    }

    function generateProductDescription(): string {
        $description = '';

        for ($i = 0; $i < random_int(2, 3); $i++) {
            $description = $description
                . '<p>'
                . fake()->realTextBetween(130, 250)
                . '</p>';
        }

        $description = $description . '<ul>';
        for ($i = 0; $i < random_int(3, 6); $i++) {
            $description = $description
                . '<li>'
                . fake()->realTextBetween(28, 40)
                . '</li>';
        }
        $description = $description . '</ul>';

        for ($i = 0; $i < random_int(1, 3); $i++) {
            $description = $description
                . '<p>'
                . fake()->realTextBetween(130, 250)
                . '</p>';
        }

        return $description;
    }

    function createProducts($prodNames, $categoryId, $storeIds): array {
        $createdProducts = [];
        foreach ($prodNames as $name) {
            $seledctedCatId = $categoryId;
            $seledctedStoreId = fake()->randomElement($storeIds);
            $selectedStore = \App\Models\Store::where("id", $seledctedStoreId)->first();

            $createdProduct = \App\Models\Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'slug_with_store' => $selectedStore->slug . "/" . Str::slug($name),
                'description' => $this->generateProductDescription(),
                'price' => random_int(100, 3000) * 1000,
                'stok' => random_int(20, 150),
                'sku' => fake()->word(),
                'average_rating' => fake()->randomFloat(1, 1, 5),
                'weight' => random_int(800, 1000),
                'length' => random_int(10, 50),
                'width' => random_int(10, 50),
                'height' => random_int(10, 50),
                'category_id' => $seledctedCatId,
                'store_id' => $selectedStore->id,
                'store_category_id' => \App\Models\StoreCategory::where('store_id', $selectedStore->id)->inRandomOrder()->first()->id
            ]);

            $productImages = [];
            for ($i = 0; $i < random_int(3, 4); $i++) {
                array_push(
                    $productImages,
                    [
                        'image_url' => 'https://source.unsplash.com/random/?' . urlencode($name . ' ' . fake()->word()),
                        'main_image' => $i === 0
                    ]
                );
            }

            $createdProduct->product_images()->createMany($productImages);

            array_push($createdProducts, $createdProduct);
        }
        return $createdProducts;
    }

    function cartesianProduct($inputArray, $currentIndex = 0, $currentCombination = []) {
        if ($currentIndex == count($inputArray)) {
            return [$currentCombination];
        }

        $result = [];

        foreach ($inputArray[$currentIndex] as $item) {
            $newCombination = $currentCombination;
            $newCombination[] = $item;

            $result = array_merge(
                $result,
                $this->cartesianProduct($inputArray, $currentIndex + 1, $newCombination)
            );
        }

        return $result;
    }

    function createProvince() {
        $res = Http::withHeaders([
            'key' => 'b8993e20a6ece73dd669b63deece88f3',
        ])->get('https://api.rajaongkir.com/starter/province');

        if ($res->failed()) {
            throw $res->error();
        }

        $data = $res->json();
        $listProvince = $data['rajaongkir']['results'];

        $dataToInsert = array_map(function ($prov) {
            return [
                ...$prov,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $listProvince);

        \App\Models\Province::insert($dataToInsert);

        return $listProvince;
    }

    function createCities(string $provinceId) {
        $res = Http::withHeaders([
            'key' => 'b8993e20a6ece73dd669b63deece88f3',
        ])->get('https://api.rajaongkir.com/starter/city', [
                    'province' => $provinceId
                ]);

        if ($res->failed()) {
            throw $res->error();
        }

        $data = $res->json();
        $listCity = $data['rajaongkir']['results'];

        $dataToInsert = array_map(function ($city) {
            return [
                ...$city,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $listCity);

        \App\Models\City::insert($dataToInsert);

        return;
    }

    public function run(): void {
        $createdUserIdByRole = $this->createUsers();
        $createdCategoryIds = $this->createCategories();
        $createdStoreIds = $this->createStores();
        $createdVarTypeMap = $this->createVariantTypes();

        $createdProducts = [];
        foreach ($createdCategoryIds as $catsAndName) {
            $newProds = $this->createProducts($catsAndName['prodNames'], $catsAndName['id'], $createdStoreIds);

            array_push($createdProducts, ...$newProds);
        }

        foreach ($createdProducts as $newProduct) {
            $prodOpts = [];
            foreach (array_slice($createdVarTypeMap, random_int(0, 1)) as $val) {
                $prodTypeOpts = $newProduct->variant_options()->createMany(
                    array_map(fn($option) => [
                        'value' => $option, 'variant_type_id' => $val['id']
                    ], $val['options'])
                );

                $prodTypeOptIds = [];
                foreach ($prodTypeOpts as $opt) {
                    array_push($prodTypeOptIds, ($opt->id));
                }
                array_push($prodOpts, $prodTypeOptIds);
            }

            $optCombination = $this->cartesianProduct($prodOpts);

            $prodVars = [];
            foreach ($optCombination as $optIds) {
                $prodVar = \App\Models\ProductVariant::create([
                    'price' => $newProduct->price,
                    'stok' => random_int(10, 60),
                    'sku' => fake()->word(),
                    'product_id' => $newProduct->id
                ]);
                $prodVar->variant_options()->attach($optIds);
                array_push($prodVars, $prodVar);
            }

            $ratings = [];
            foreach (fake()->randomElements($createdUserIdByRole['user'], random_int(2, 50)) as $userId) {
                $selectedVar = fake()->randomElement($prodVars);
                $quantity = random_int(1, 3);
                $orderTotal = ((float) $selectedVar->price * $quantity) + 20000;
                $createdOrder = Order::create([
                    'total' => $orderTotal,
                    'serial_order' => 'ORDER' . fake()->randomNumber(),
                    'transaction_id' => fake()->uuid(),
                    'status' => 'settlement',
                    'status_code' => 200,
                    'payment_type' => 'bank_transfer',
                    'delivery_service' => 'JNE',
                    'delivery_address' => fake()->address(),
                    'delivery_cost' => 20000,
                    'user_id' => $userId,
                ]);

                $createdOrderItem = OrderItem::create([
                    'quantity' => $quantity,
                    'product_id' => $newProduct->id,
                    'product_variant_id' => $selectedVar->id,
                    'order_id' => $createdOrder->id,
                ]);

                $rating = random_int(3, 5);
                $createdReview = Review::create([
                    'rating' => $rating,
                    'coment' => fake()->realText(),
                    'user_id' => $userId,
                    'product_id' => $newProduct->id,
                    'product_variant_id' => $selectedVar->id,
                ]);
                array_push($ratings, $rating);
            }

            $resultStok = 0;
            foreach ($prodVars as $prodVar) {
                $resultStok += $prodVar->stok;
            }

            $resultAverageRating = round((float) (array_sum($ratings) / count($ratings)), 2);

            $newProduct->update([
                'stok' => $resultStok,
                'average_rating' => $resultAverageRating
            ]);
        }

        $listProvince = $this->createProvince();

        foreach ($listProvince as $province) {
            $this->createCities($province['province_id']);
        }
    }
}
