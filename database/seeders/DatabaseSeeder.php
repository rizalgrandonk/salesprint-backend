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

    public $productImageOptions = [
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457417/salesprint/zvfimmc2nneexz4btfpu.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/zlxkmecml3yr4950ef1w.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/z4meu38cmjyjsf8mkqdk.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/yq4cs4leginztcsavrsy.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/yo7hzbqdmimi1suaawei.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/y8qlr6aslr4rh677ub2h.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457416/salesprint/x2cpadn2bmrbifeudgp1.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/wboziavdalny7nurh8mq.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/vml7q0dmiuofexodoptk.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457416/salesprint/tvufvbl5yrpbeo55ygqq.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/tr9a7fnqhnpriafkusy4.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/scrvwncskhq4em2dqfxx.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457416/salesprint/saiatpvjyrs3l42b8kmt.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/rgbnv2xoibybmzhjjpbo.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/r4awcvocabt2spl7290h.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/q9paelodmpvqvayqkauf.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/q86wnchsscjujx0vr3gf.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457416/salesprint/objjkqarxpz0tq0dr3e6.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/ndxbokmos2fanx8gwbep.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/lbyij8ox10khynhejlqu.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/kpumfqsscmxysxvhlr10.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/hg2ut2m6cqclfxrgm2xt.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/gzs31szwkmtoppvkiikl.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457413/salesprint/gwsr0psjaes6ozutdh7e.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457416/salesprint/gbw27wxi7h9wrjzrsrgm.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/eync24wbjskyozsi005i.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/eblpohq0nodvejpdcgym.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457412/salesprint/d9gnjkltib5owyukwbrd.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/chbcwcjtxamer6ebtz9d.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457415/salesprint/bgzqkrqq6dkmn7xvhzng.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706457414/salesprint/apxqspbmdocsfjlnbmui.jpg'
    ];

    public $userImageOptions = [
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460008/salesprint/xdm0lpiz7bbryzitxiac.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460008/salesprint/vq4qjgo5zsvqjkebigep.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460007/salesprint/r1sdztel9bbuoa8rlhmk.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460007/salesprint/cdomyp8npfswg3ovbbai.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460007/salesprint/qec9aoyqcjcqbg1tiuky.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460007/salesprint/p5jze4jzv4cya5violbo.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460007/salesprint/szg8kethm27bk3yb34sm.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460006/salesprint/iwzual4rznlnzonucamw.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460005/salesprint/kwabdpo4hkwlpakgwebr.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460005/salesprint/zfbkzcvrozagkeqxiqq5.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460005/salesprint/rpu4qw3l2rk7kzsfwegb.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460005/salesprint/cpkn1ew1aoc6785ygux6.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460005/salesprint/wkwtndiggkr0xd8xsz1u.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460003/salesprint/cbspssshbgzlaaomf9m4.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460003/salesprint/ohy9lfzuqkiufrbbknfc.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460003/salesprint/xegrckztrlejob0d0h5e.jpg'
    ];

    public $storeImageOptions = [
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460607/salesprint/fach1leshcctpqpaercl.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460607/salesprint/lmmjfk4lkqht44wq99ae.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460606/salesprint/yzaogipw4pi5d02cgzjy.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460606/salesprint/lkw7aeykwpduzf9o1kwe.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460606/salesprint/hp5pu3scrye2esqiynon.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460605/salesprint/yy8rhk3y1r7vale4cm39.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460605/salesprint/kazjysd7pobfodbdwidv.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460605/salesprint/hyxgmwhpvkkopbkyciex.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460604/salesprint/tyzkgiqp5qes9wlzsngn.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460604/salesprint/nlyhgcdoiy1gwpjqyquz.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460604/salesprint/vwxpkyblabm9ussxi6ks.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460604/salesprint/eyzdtr8iquinq7ixeaiv.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460603/salesprint/ylnkqlqebdktonfrx0ke.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460603/salesprint/dlexscdsox6odrnjlbqy.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460602/salesprint/ouxalwkrjazfpyog9ych.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460602/salesprint/p6g5fb0tkjto4swyulgi.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460602/salesprint/jczazoogwoqiczi8iznl.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460601/salesprint/rob8v0u9gbmuyo3m8mnw.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460601/salesprint/afugelycaraqlch3jzsl.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460601/salesprint/brze29zdcas5pshp9kkq.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460600/salesprint/wn2fksbrpbh9h59ppvlt.jpg',
        'http://res.cloudinary.com/grandonk-merch/image/upload/v1706460600/salesprint/tnzon6j3q6db07esieul.jpg'
    ];

    function createUsers(): array {
        $user1 = \App\Models\User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'username' => 'user666',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123239483123',
            'image' => fake()->randomElement($this->userImageOptions),
        ]);
        $user2 = \App\Models\User::create([
            'name' => 'Grandonk User',
            'email' => 'grandonkuser@gmail.com',
            'username' => 'grandonkuser666',
            'role' => 'user',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08242301323123',
            'image' => fake()->randomElement($this->userImageOptions),
        ]);
        $user3 = \App\Models\User::create([
            'name' => 'Seller',
            'email' => 'seller@gmail.com',
            'username' => 'seller666',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08154536315424',
            'image' => fake()->randomElement($this->userImageOptions),
        ]);
        $user4 = \App\Models\User::create([
            'name' => 'Grandonk Seller',
            'email' => 'grandonkseller@gmail.com',
            'username' => 'grandonkseller666',
            'role' => 'seller',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123191347123',
            'image' => fake()->randomElement($this->userImageOptions),
        ]);
        $user5 = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin666',
            'role' => 'admin',
            'password' => bcrypt('66666666'),
            'remember_token' => Str::random(10),
            'phone_number' => '08123171321243',
            'image' => fake()->randomElement($this->userImageOptions),
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
                'image' => fake()->randomElement($this->userImageOptions),
            ]);
            array_push($result['user'], $createdUser->id);
        }
        for ($i = 0; $i < 30; $i++) {
            $nameUser = fake()->firstName() . fake()->randomNumber(3, true);
            $createdUser = \App\Models\User::create([
                'name' => $nameUser,
                'email' => strtolower($nameUser) . '@gmail.com',
                'username' => strtolower($nameUser),
                'role' => 'seller',
                'password' => bcrypt('66666666'),
                'remember_token' => Str::random(10),
                'phone_number' => fake()->phoneNumber(),
                'image' => fake()->randomElement($this->userImageOptions),
            ]);
            array_push($result['seller'], $createdUser->id);
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
            $prodNames = $val;
            $createdCategory = \App\Models\Category::create([
                'name' => implode(" ", array_map(fn($val) => Str::ucfirst($val), explode(" ", $name))),
                'slug' => Str::slug($name),
                'image' => fake()->randomElement($this->productImageOptions)
            ]);

            for ($i = 0; $i < random_int(5, 15); $i++) {
                array_push(
                    $prodNames,
                    fake()->realTextBetween(28, 40)
                );
            }

            array_push(
                $createdIds,
                ['id' => $createdCategory->id, 'prodNames' => $prodNames]
            );

        }
        return $createdIds;
    }

    function createStores(array $userIds): array {
        $imgCat = fake()->randomElements($this->productImageOptions, 2);
        $store_categories = [
            [
                'name' => 'Produk Baru',
                'slug' => Str::slug('Produk Baru'),
                'image' => $imgCat[0]
            ],
            [
                'name' => 'Top Seller',
                'slug' => Str::slug('Top Seller'),
                'image' => $imgCat[1]
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
            'image' => fake()->randomElement($this->storeImageOptions),
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
            'image' => fake()->randomElement($this->storeImageOptions),
            'store_description' => fake()->paragraph(random_int(3, 5)),
            'user_id' => \App\Models\User::where('email', 'seller@gmail.com')->first()->id
        ]);

        $store_1->store_categories()->createMany($store_categories);
        $store_2->store_categories()->createMany($store_categories);

        $store_1->store_banners()->createMany([
            ['image' => fake()->randomElement($this->storeImageOptions)],
            ['image' => fake()->randomElement($this->storeImageOptions)],
        ]);
        $store_2->store_banners()->createMany([
            ['image' => fake()->randomElement($this->storeImageOptions)],
            ['image' => fake()->randomElement($this->storeImageOptions)],
        ]);

        $storeIds = [$store_1->id, $store_2->id];

        foreach (array_slice($userIds, 2) as $userId) {
            $name = fake()->streetName()
                . ' '
                . fake()->randomElement([
                    "Store", "Retail", "Outlet", "Market", "Boutique", "Shop"
                ])
                . ' '
                . fake()->randomNumber(2, true);

            $createdStore = \App\Models\Store::create([
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
                'image' => fake()->randomElement($this->storeImageOptions),
                'store_description' => fake()->paragraph(random_int(3, 5)),
                'user_id' => $userId
            ]);

            $createdStore->store_categories()->createMany($store_categories);

            $createdStore->store_banners()->createMany([
                ['image' => fake()->randomElement($this->storeImageOptions)],
                ['image' => fake()->randomElement($this->storeImageOptions)],
            ]);

            array_push($storeIds, $createdStore->id);
        }

        return $storeIds;
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

            $mainImageUrl = fake()->randomElement($this->productImageOptions);

            $prodImgOptions = array_filter($this->productImageOptions, function ($img) use ($mainImageUrl) {
                return $img !== $mainImageUrl;
            });

            $productImages = [[
                'image_url' => $mainImageUrl,
                'main_image' => true
            ]];

            foreach (fake()->randomElements($prodImgOptions, random_int(2, 4)) as $img) {
                array_push(
                    $productImages,
                    [
                        'image_url' => $img,
                        'main_image' => false
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
        $createdStoreIds = $this->createStores($createdUserIdByRole['seller']);
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
                    'order_status' => 'COMPLETED',
                    'shipping_status' => "DELIVERED",
                    'shipping_tracking_number' => fake()->isbn10(),
                    'shipping_courier' => fake()->randomElement(
                        ['jne', 'sicepat', 'jnt', 'pos', 'anteraja']
                    ),
                    'shipping_history' => '[
                        {
                          "date": "2024-01-17 10:42:00",
                          "desc": "PAKET DITERIMA OLEH [BU WASIH - (KEL) KELUARGA SERUMAH]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-17 08:18:00",
                          "desc": "PAKET DIBAWA [SIGESIT - M IRFAN ARIF]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-17 08:16:00",
                          "desc": "PAKET TELAH DI TERIMA DI MOJOKERTO [DAWAR]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-16 20:33:00",
                          "desc": "PAKET KELUAR DARI SIDOARJO [SURABAYA SORTATION]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-16 04:39:00",
                          "desc": "PAKET TELAH DI TERIMA DI SIDOARJO [SURABAYA SORTATION]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-16 03:38:00",
                          "desc": "PAKET KELUAR DARI SURABAYA [SURABAYA MARGOMULYO]",
                          "location": ""
                        },
                        {
                          "date": "2024-01-15 18:59:00",
                          "desc": "PAKET TELAH DI INPUT (MANIFESTED) DI SURABAYA [SURABAYA MARGOMULYO]",
                          "location": ""
                        }
                      ]',
                    'serial_order' => 'ORDER' . fake()->randomNumber(),
                    'transaction_id' => fake()->uuid(),
                    'payment_status' => 'settlement',
                    'status_code' => 200,
                    'payment_type' => 'bank_transfer',
                    'delivery_service' => 'JNE',
                    'delivery_address' => fake()->address(),
                    'delivery_cost' => 20000,
                    'user_id' => $userId,
                    'store_id' => $newProduct->store->id
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
