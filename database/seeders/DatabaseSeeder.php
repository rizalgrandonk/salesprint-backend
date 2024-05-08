<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Province;
use App\Models\Review;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Withdraw;
use Carbon\Carbon;
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
            $fakeFirstName = fake()->firstName();
            $fakeName = $fakeFirstName . " " . fake()->lastName();
            $fakeUserName = $fakeFirstName . fake()->randomNumber(3, true);
            $createdUser = \App\Models\User::create([
                'name' => $fakeName,
                'email' => strtolower($fakeUserName) . '@gmail.com',
                'username' => strtolower($fakeUserName),
                'role' => 'user',
                'password' => bcrypt('66666666'),
                'remember_token' => Str::random(10),
                'phone_number' => fake()->phoneNumber(),
                'image' => fake()->randomElement($this->userImageOptions),
            ]);
            array_push($result['user'], $createdUser->id);
        }
        for ($i = 0; $i < 8; $i++) {
            $fakeFirstName = fake()->firstName();
            $fakeName = $fakeFirstName . " " . fake()->lastName();
            $fakeUserName = $fakeFirstName . fake()->randomNumber(3, true);
            $createdUser = \App\Models\User::create([
                'name' => $fakeName,
                'email' => strtolower($fakeUserName) . '@gmail.com',
                'username' => strtolower($fakeUserName),
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
            'Electronics' => array(
                'QuantumGear Smartwatch',
                'TechFusion Wireless Earbuds',
                'InfinityLight LED Desk Lamp',
                'PowerPod Portable Charger',
                'StellarTech Laptop Stand',
                'PowerPlay Gaming Mouse',
                'StellarSound Bluetooth Speaker',
                'PowerPulse Massage Gun',
                'QuantumQuilt Weighted Blanket',
                'PowerGlide Portable Laptop Stand'
            ),
            'Home & Living' => array(
                'LuxeLife Velvet Throw Pillow',
                'UrbanChic Backpack',
                'AquaFlow Water Bottle',
                'ZenScape Meditation Cushion',
                'LuxeLoom Egyptian Cotton Towels',
                'UrbanGourmet Spice Rack',
                'ZenMist Aromatherapy Diffuser',
                'LuxeLounge Velvet Sofa Throw',
                'LuxeLinen Linen Bedding Set'
            ),
            'Health & Fitness' => array(
                'NovaFit Resistance Bands Set',
                'BioFresh Organic Laundry Detergent',
                'PowerFlex Yoga Mat',
                'ZenPet Calming Collar',
                'FitFlow Yoga Block Set',
                'BioFuel Plant-Based Protein Powder',
                'AquaCool Cooling Towel',
                'QuantumFlex Resistance Yoga Bands',
                'PowerBalance Fitness Resistance Bands'
            ),
            'Fashion & Accessories' => array(
                'TrendSetter Fashion Sunglasses',
                'LuxeLeather Passport Holder',
                'ZenZone Stress Relief Kit',
                'UrbanHarbor Cityscape Wall Art',
                'EcoGrip Eco-Friendly Phone Grip',
                'LuxeLounge Lounge Chair Cover',
                'ZenHaven Meditation Blanket',
                'UrbanUnity Puzzle Art Print',
                'LuxeLift Facial Massage Roller'
            ),
            'Home & Garden' => array(
                'NatureNest Bird Feeder',
                'UrbanScape Cityscape Puzzle',
                'EcoVibe Reusable Shopping Bags',
                'NatureNook Indoor Plant Kit',
                'FreshChef Herb Garden Kit',
                'BioBloom Seed Bomb Kit',
                'UrbanZen Desktop Mini Garden',
                'EcoPetal Plantable Seed Paper',
            ),
            'Beauty & Personal Care' => array(
                'StellarGlow Facial Serum',
                'LuxLash Faux Mink Eyelashes',
                'BioEpic Natural Skincare Set',
                'ZenNectar Honey Infuser',
                'BioBite Biodegradable Cutlery Set',
                'StellarSleep Silk Pillowcase',
                'BioGlow Natural Skincare Set',
                'FreshFlare Scented Candles',
                'BioBright Natural Teeth Whitening Kit'
            ),
            'Outdoor & Adventure' => array(
                'AquaSplash Waterproof Phone Case',
                'AquaAdventure Snorkel Set',
                'QuantumQuest Adventure Board Game',
                'AquaTrek Waterproof Hiking Boots',
                'NatureNest Squirrel-Proof Bird Feeder',
                'AquaFlex Water-Resistant Backpack',
                'AquaArmor Waterproof Phone Pouch',
                'AquaFit Swim Resistance Bands',
                'QuantumQuick Dry Towel Set',
                'AquaAdventure Waterproof Camping Lantern'
            ),
            'Kitchen & Dining' => array(
                'FreshBrew Coffee Grinder',
                'EcoEssentials Bamboo Kitchen Set',
                'QuantumBlend Smoothie Maker',
                'FreshFill Reusable Water Filter',
                'FreshFluff Eco-Friendly Dryer Balls',
                'QuantumQuench Collapsible Water Bottle'
            ),
            'Pets' => array(
                'EcoPaws Organic Pet Shampoo',
                'NatureNurture Plant-Based Baby Onesies'
            ),
            'Books & Stationery' => array(
                'UrbanVogue Fashion Sketchbook',
                'NatureNotes Eco-Friendly Notebook',
                'QuantumQuill Calligraphy Pen Set'
            )
        ];
        $createdIds = [];
        foreach ($category_names as $name => $val) {
            $prodNames = $val;
            $createdCategory = \App\Models\Category::create([
                'name' => implode(" ", array_map(fn($val) => Str::ucfirst($val), explode(" ", $name))),
                'slug' => Str::slug($name),
                'image' => fake()->randomElement($this->productImageOptions)
            ]);

            // for ($i = 0; $i < random_int(5, 15); $i++) {
            //     array_push(
            //         $prodNames,
            //         fake()->realTextBetween(28, 40)
            //     );
            // }

            array_push(
                $createdIds,
                ['id' => $createdCategory->id, 'prodNames' => $prodNames]
            );
        }
        return $createdIds;
    }

    function createStores(array $userIds, array $listCities): array {
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

        $store_1 = Store::create([
            'name' => 'Grandonk Merch',
            'slug' => Str::slug('Grandonk Merch'),
            'phone_number' => '0812345678987',
            'address' => fake()->streetAddress(),
            'province_id' => $listCities[143]['province_id'],
            'province' => $listCities[143]['province'],
            'city_id' => $listCities[143]['city_id'],
            'city' => $listCities[143]['city_name'],
            'postal_code' => $listCities[143]['postal_code'],
            'status' => 'approved',
            'image' => fake()->randomElement($this->storeImageOptions),
            'store_description' => fake()->paragraph(random_int(3, 5)),
            'user_id' => \App\Models\User::where('email', 'grandonkseller@gmail.com')->first()->id,
            'total_balance' => 0
        ]);

        $store_2 = Store::create([
            'name' => 'Upscale Store',
            'slug' => Str::slug('Upscale Store'),
            'phone_number' => '0898787653412',
            'address' => fake()->streetAddress(),
            'province_id' => $listCities[158]['province_id'],
            'province' => $listCities[158]['province'],
            'city_id' => $listCities[158]['city_id'],
            'city' => $listCities[158]['city_name'],
            'postal_code' => $listCities[158]['postal_code'],
            'status' => 'approved',
            'image' => fake()->randomElement($this->storeImageOptions),
            'store_description' => fake()->paragraph(random_int(3, 5)),
            'user_id' => \App\Models\User::where('email', 'seller@gmail.com')->first()->id,
            'total_balance' => 0
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

            $selectedCity = fake()->randomElement($listCities);

            $createdStore = Store::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'province_id' => $selectedCity['province_id'],
                'province' => $selectedCity['province'],
                'city_id' => $selectedCity['city_id'],
                'city' => $selectedCity['city_name'],
                'postal_code' => $selectedCity['postal_code'],
                'status' => fake()->randomElement(['on_review', 'approved', 'rejected']),
                'image' => fake()->randomElement($this->storeImageOptions),
                'store_description' => fake()->paragraph(random_int(3, 5)),
                'user_id' => $userId,
                'total_balance' => 0
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
            $selectedStore = Store::where("id", $seledctedStoreId)->first();

            $createDate = Carbon::create(2021, 1, random_int(1, 5), random_int(1, 24), 0, 0);

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
                'store_category_id' => \App\Models\StoreCategory::where('store_id', $selectedStore->id)->inRandomOrder()->first()->id,
                'created_at' => $createDate,
                'updated_at' => $createDate
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
        $res = Http::retry(3, 1000)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY', ''),
        ])->get(
                env(
                    'RAJAONGKIR_BASE_URL',
                    'https://api.rajaongkir.com/starter'
                ) . '/province'
            );

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

        Province::insert($dataToInsert);

        return $listProvince;
    }

    function createCities(string $provinceId) {
        $res = Http::retry(3, 1000)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY', ''),
        ])->get(
                env(
                    'RAJAONGKIR_BASE_URL',
                    'https://api.rajaongkir.com/starter'
                ) . '/city',
                [
                    'province' => $provinceId
                ]
            );

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

        City::insert($dataToInsert);

        return $listCity;
    }

    public function run(): void {
        $listProvince = $this->createProvince();

        echo "Success insert province list \n";

        $listCities = [];
        foreach ($listProvince as $province) {
            $createdCities = $this->createCities($province['province_id']);
            array_push($listCities, ...$createdCities);

            echo "Success insert cities list for province {$province['province_id']} {$province['province']} \n";
        }

        $createdUserIdByRole = $this->createUsers();
        $createdCategoryIds = $this->createCategories();
        $createdStoreIds = $this->createStores($createdUserIdByRole['seller'], $listCities);
        $createdVarTypeMap = $this->createVariantTypes();

        $createdProducts = [];
        foreach ($createdCategoryIds as $catsAndName) {
            $newProds = $this->createProducts($catsAndName['prodNames'], $catsAndName['id'], $createdStoreIds);

            array_push($createdProducts, ...$newProds);
        }
        /**
         *   storeId_month_year => revenue
         */
        $storeMonthlyRev = [];
        /**
         *   storeId_month_year => orderIds
         */
        $storeMonthlyOrderIds = [];

        foreach ($createdProducts as $index => $newProduct) {
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

            for (
                $i = 0;
                $i < Carbon::create($newProduct->created_at)->diffInMonths() + 1;
                $i++
            ) {

                $selectedUserIds = fake()->randomElements(
                    array_filter($createdUserIdByRole['user'], function ($userId) use ($newProduct) {
                        return ((int) $userId % 2 === 0) === ((int) $newProduct->id % 2 === 0);
                    }),
                    random_int(5, 10)
                );

                foreach ($selectedUserIds as $userId) {
                    $createdOrderDate = Carbon::create($newProduct->created_at)
                        ->addMonths($i)
                        ->setSeconds(Carbon::now()->second)
                        ->setMilliseconds(Carbon::now()->millisecond);

                    if ($createdOrderDate->isSameMonth(Carbon::now())) {
                        $createdOrderDate->setDay(
                            min(Carbon::now()->day, ($createdOrderDate->day + random_int(1, 20)))
                        );
                    } else {
                        $createdOrderDate->addDays(random_int(1, 20));
                    }

                    $selectedCity = fake()->randomElement($listCities);

                    $no = $index + 1;
                    $productCount = count($createdProducts);

                    echo "{$no} of {$productCount} {$newProduct->name} -> {$userId} {$createdOrderDate} {$selectedCity['city_name']} \n";

                    $selectedVar = fake()->randomElement($prodVars);
                    $quantity = random_int(1, 3);
                    $orderTotal = ((float) $selectedVar->price * $quantity) + 20000;

                    $serialOrder = $createdOrderDate->format("Ymd")
                        . Carbon::createMidnightDate(
                            $createdOrderDate->year,
                            $createdOrderDate->month,
                            $createdOrderDate->day
                        )->diffInMilliseconds($createdOrderDate);

                    $createdTransaction = Transaction::create([
                        'total' => $orderTotal,
                        'serial_order' => $serialOrder,
                        'transaction_id' => fake()->uuid(),
                        'payment_status' => 'settlement',
                        'status_code' => 200,
                        'status_message' => 'Success, pembayaran berhasil',
                        'payment_type' => fake()->randomElement(['bank_transfer', 'echannel', 'gopay', 'qris', 'cstore']),
                        'user_id' => $userId,
                        'created_at' => $createdOrderDate,
                        'updated_at' => $createdOrderDate
                    ]);

                    $newOrderData = [
                        'total' => $orderTotal,
                        'order_status' => 'COMPLETED',
                        'shipping_status' => "DELIVERED",
                        'shipping_tracking_number' => fake()->isbn10(),
                        'shipping_courier' => 'jne',
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
                        'delivery_service' => 'OKE',
                        'delivery_address' => fake()->address(),
                        'reciever_name' => fake()->name(),
                        'reciever_phone' => fake()->phoneNumber(),
                        'delivery_province_id' => $selectedCity['province_id'],
                        'delivery_province' => $selectedCity['province'],
                        'delivery_city_id' => $selectedCity['city_id'],
                        'delivery_city' => $selectedCity['city_name'],
                        'delivery_postal_code' => $selectedCity['postal_code'],
                        'delivery_cost' => 20000,
                        'is_withdrew' => !($createdOrderDate->isSameMonth(Carbon::now())),
                        'user_id' => $userId,
                        'store_id' => $newProduct->store->id,
                        'transaction_id' => $createdTransaction->id,
                        'order_number' => $serialOrder . $newProduct->store->id . $createdTransaction->id,
                        'created_at' => $createdOrderDate,
                        'updated_at' => Carbon::create($createdOrderDate)->addDays(5),
                        'paid_at' => Carbon::create($createdOrderDate)->addDays(1),
                        'accepted_at' => Carbon::create($createdOrderDate)->addDays(2),
                        'shipped_at' => Carbon::create($createdOrderDate)->addDays(3),
                        'delivered_at' => Carbon::create($createdOrderDate)->addDays(4),
                        'completed_at' => Carbon::create($createdOrderDate)->addDays(5),
                    ];

                    $createdOrder = Order::withoutEvents(function () use ($newOrderData) {
                        return Order::create($newOrderData);
                    });

                    $createdOrderItem = OrderItem::create([
                        'quantity' => $quantity,
                        'product_id' => $newProduct->id,
                        'product_variant_id' => $selectedVar->id,
                        'order_id' => $createdOrder->id,
                        'created_at' => $createdOrderDate,
                        'updated_at' => $createdOrderDate
                    ]);

                    $rating = random_int(3, 5);
                    $createdReview = Review::create([
                        'rating' => $rating,
                        'coment' => fake()->realText(),
                        'user_id' => $userId,
                        'product_id' => $newProduct->id,
                        'product_variant_id' => $selectedVar->id,
                        'order_item_id' => $createdOrderItem->id,
                        'created_at' => Carbon::create($createdOrderDate)->addDays(5),
                        'updated_at' => Carbon::create($createdOrderDate)->addDays(5)
                    ]);
                    array_push($ratings, $rating);

                    if ($createdOrderDate->isSameMonth(Carbon::now())) {
                        Store::where('id', $newProduct->store->id)
                            ->increment(
                                'total_balance', $orderTotal
                            );
                    } else {
                        if (isset(
                            $storeMonthlyRev
                            ["{$newProduct->store->id}_{$createdOrderDate->month}_{$createdOrderDate->year}"]
                        )) {
                            $storeMonthlyRev
                            ["{$newProduct->store->id}_{$createdOrderDate->month}_{$createdOrderDate->year}"] += $orderTotal;

                            array_push(
                                $storeMonthlyOrderIds
                                ["{$newProduct->store->id}_{$createdOrderDate->month}_{$createdOrderDate->year}"],
                                $createdOrder->id
                            );
                        } else {
                            $storeMonthlyRev
                            ["{$newProduct->store->id}_{$createdOrderDate->month}_{$createdOrderDate->year}"] = $orderTotal;

                            $storeMonthlyOrderIds
                            ["{$newProduct->store->id}_{$createdOrderDate->month}_{$createdOrderDate->year}"] = [$createdOrder->id];
                        }
                    }
                }
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

        foreach ($storeMonthlyRev as $key => $monthlyRev) {
            list($storeId, $month, $year) = explode('_', $key);
            $orderIds = $storeMonthlyOrderIds[$key];
            $countOrders = count($orderIds);

            $withdrawDate = Carbon::createFromDate($year, $month)->endOfMonth();

            echo "Withdraw Store {$storeId} -> {$withdrawDate}, order count {$countOrders} \n";

            $createdWithdraw = Withdraw::create([
                'total_amount' => $monthlyRev,
                'bank_code' => "014",
                'bank_name' => "BANK BCA",
                'bank_account_number' => fake()->creditCardNumber(),
                'bank_account_name' => fake()->name(),
                'status' => 'PAID',
                'store_id' => $storeId,
                'created_at' => $withdrawDate,
                'updated_at' => $withdrawDate,
            ]);

            Order::whereIn('id', $orderIds)->update(['withdraw_id' => $createdWithdraw->id]);
        }

    }
}
