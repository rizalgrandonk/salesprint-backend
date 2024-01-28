<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductImageRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeleteProductImageRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Store;
use App\Models\VariantOption;
use App\Models\VariantType;
use Illuminate\Http\Request;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $products = Product::paramQuery($request->query())
            ->get();

        if (!$products) {
            return $this->responseFailed("Not Found", 404, "Products not found");
        }

        return $this->responseSuccess($products);
    }
    public function paginated(Request $request) {
        $products = Product::getDataTable($request->query());

        if (!$products) {
            return $this->responseFailed("Not Found", 404, "Products not found");
        }

        return $this->responseSuccess($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function store_products(Request $request, string $store_slug) {
        $store = Store::where("slug", $store_slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }
        $products = Product::where("store_id", $store->id)
            ->paramQuery($request->query())
            ->get();

        if (!$products) {
            return $this->responseFailed("Not Found", 404, "Products not found");
        }

        return $this->responseSuccess($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated_store_products(Request $request, string $store_slug) {
        $store = Store::where("slug", $store_slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }
        $products = Product::where("store_id", $store->id)->getDataTable($request->query());

        if (!$products) {
            return $this->responseFailed("Not Found", 404, "Products not found");
        }

        return $this->responseSuccess($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        CreateProductRequest $request,
        string $store_slug
    ) {
        $store = Store::where("slug", $store_slug)
            ->first();

        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        $userStore = Store::where("user_id", auth()->user()->id)
            ->first();

        if ($store->id !== $userStore->id) {
            return $this->responseFailed("Store not belongs to current user", 401, "Unauthorize");
        }

        $validatedData = $request->validated();

        $sumVarData = [
            'sku' => $validatedData['variant_combinations'][0]['sku'],
            'price' => (float) $validatedData['variant_combinations'][0]['price'],
            'stok' => 0
        ];
        foreach ($validatedData['variant_combinations'] as $dataComb) {
            $sumVarData['stok'] = $sumVarData['stok'] + ((int) $dataComb['stok']);
        }

        $newProduct = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'slug_with_store' => $validatedData['slug_with_store'],
            'description' => $validatedData['description'],
            'price' => $sumVarData['price'],
            'stok' => $sumVarData['stok'],
            'sku' => $sumVarData['sku'],
            'average_rating' => 0,
            'weight' => $validatedData['weight'],
            'length' => $validatedData['length'],
            'width' => $validatedData['width'],
            'height' => $validatedData['height'],
            'category_id' => $validatedData['category_id'],
            'store_id' => $store->id,
            'store_category_id' => isset($validatedData['store_category_id']) ? $validatedData['store_category_id'] : null
        ]);

        $variants = [];
        if (isset($validatedData['variants'])) {
            foreach ($validatedData['variants'] as $dataVariant) {
                $existingVariantType = VariantType::where(
                    "name", $dataVariant['variant_type']
                )->first();
                $selectedVariantType = $existingVariantType
                    ? $existingVariantType
                    : VariantType::create(['name' => $dataVariant['variant_type']]);

                foreach ($dataVariant['variant_options'] as $dataOption) {
                    $newVariantOption = VariantOption::create([
                        'value' => $dataOption,
                        'product_id' => $newProduct->id,
                        'variant_type_id' => $selectedVariantType->id
                    ]);

                    array_push($variants, [
                        'variant_type' => $selectedVariantType->name,
                        'variant_type_id' => $selectedVariantType->id,
                        'variant_option' => $newVariantOption->value,
                        'variant_option_id' => $newVariantOption->id,
                    ]);
                }
            }

            foreach ($validatedData['variant_combinations'] as $dataCombination) {
                $newProductVariant = ProductVariant::create([
                    'price' => (float) $dataCombination['price'],
                    'stok' => (int) $dataCombination['stok'],
                    'sku' => $dataCombination['sku'],
                    'product_id' => $newProduct->id
                ]);
                foreach ($variants as $varData) {
                    if (
                        isset($dataCombination[$varData['variant_type']]) && $dataCombination[$varData['variant_type']] === $varData['variant_option']
                    ) {
                        $newProductVariant->variant_options()->attach($varData['variant_option_id']);
                    }
                }
            }
        }

        return $this->responseSuccess($newProduct, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateProductRequest $request,
        string $store_slug,
        string $product_slug
    ) {
        $store = Store::where("slug", $store_slug)
            ->first();

        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        $userStore = Store::where("user_id", auth()->user()->id)
            ->first();

        if ($store->id !== $userStore->id) {
            return $this->responseFailed("Store not belongs to current user", 401, "Unauthorize");
        }

        $product = Product::with(['product_variants', 'variant_options'])
            ->where("slug_with_store", $store_slug . "/" . $product_slug)
            ->first();
        if (!$product) {
            return $this->responseFailed(
                "Product not Found",
                404,
                "Product not found"
            );
        }

        $validatedData = $request->validated();

        if (
            isset($validatedData["slug_with_store"])
            && $validatedData['slug_with_store'] !== $product->slug_with_store
        ) {
            $validSlug = $request->validate([
                'slug_with_store' => 'unique:products'
            ]);
            if (isset($validSlug["slug_with_store"])) {
                $validatedData['slug_with_store'] = $validSlug['slug_with_store'];
            }
        }

        $sumVarData = [
            'sku' => $validatedData['variant_combinations'][0]['sku'],
            'price' => (float) $validatedData['variant_combinations'][0]['price'],
            'stok' => 0
        ];
        foreach ($validatedData['variant_combinations'] as $dataComb) {
            $sumVarData['stok'] = $sumVarData['stok'] + ((int) $dataComb['stok']);
        }

        $product->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'slug_with_store' => $validatedData['slug_with_store'],
            'description' => $validatedData['description'],
            'price' => $sumVarData['price'],
            'stok' => $sumVarData['stok'],
            'sku' => $sumVarData['sku'],
            'average_rating' => 0,
            'weight' => $validatedData['weight'],
            'length' => $validatedData['length'],
            'width' => $validatedData['width'],
            'height' => $validatedData['height'],
            'category_id' => $validatedData['category_id'],
            'store_id' => $store->id,
            'store_category_id' => isset($validatedData['store_category_id']) ? $validatedData['store_category_id'] : null
        ]);

        if ($product->variant_options && count($product->variant_options) > 0) {
            $varOptIds = $product->variant_options->map(function ($item) {
                return $item->id;
            });
            ProductVariant::destroy($varOptIds);
        }
        if ($product->product_variants && count($product->product_variants) > 0) {
            $prodVarIds = $product->product_variants->map(function ($item) {
                return $item->id;
            });
            ProductVariant::destroy($prodVarIds);
        }

        $variants = [];
        if (isset($validatedData['variants'])) {
            foreach ($validatedData['variants'] as $dataVariant) {
                $existingVariantType = VariantType::where(
                    "name", $dataVariant['variant_type']
                )->first();
                $selectedVariantType = $existingVariantType
                    ? $existingVariantType
                    : VariantType::create(['name' => $dataVariant['variant_type']]);

                foreach ($dataVariant['variant_options'] as $dataOption) {
                    $newVariantOption = VariantOption::create([
                        'value' => $dataOption,
                        'product_id' => $product->id,
                        'variant_type_id' => $selectedVariantType->id
                    ]);

                    array_push($variants, [
                        'variant_type' => $selectedVariantType->name,
                        'variant_type_id' => $selectedVariantType->id,
                        'variant_option' => $newVariantOption->value,
                        'variant_option_id' => $newVariantOption->id,
                    ]);
                }
            }

            foreach ($validatedData['variant_combinations'] as $dataCombination) {
                $newProductVariant = ProductVariant::create([
                    'price' => (float) $dataCombination['price'],
                    'stok' => (int) $dataCombination['stok'],
                    'sku' => $dataCombination['sku'],
                    'product_id' => $product->id
                ]);
                foreach ($variants as $varData) {
                    if (
                        isset($dataCombination[$varData['variant_type']]) && $dataCombination[$varData['variant_type']] === $varData['variant_option']
                    ) {
                        $newProductVariant->variant_options()->attach($varData['variant_option_id']);
                    }
                }
            }
        }

        return $this->responseSuccess($product, 201);
    }

    public function store_images(
        CreateProductImageRequest $request,
        string $store_slug,
        string $product_slug
    ) {
        $store = Store::where("slug", $store_slug)->first();
        if (!$store) {
            return $this->responseFailed(
                "Store not Found",
                404,
                "Store not found"
            );
        }

        $userStore = Store::where("user_id", auth()->user()->id)
            ->first();

        if ($store->id !== $userStore->id) {
            return $this->responseFailed(
                "Store not belongs to current user",
                401,
                "Unauthorize"
            );
        }

        $product = Product::with(['product_images'])
            ->where("slug_with_store", $store_slug . "/" . $product_slug)
            ->first();
        if (!$product) {
            return $this->responseFailed(
                "Product not Found",
                404,
                "Product not found"
            );
        }

        $validatedData = $request->validated();

        if ($request->input("images") && $request->file("images")) {
            $validatedData['images'] = [
                ...$request->input("images"),
                ...$request->file("images")
            ];
        }


        if ($product->product_images && count($product->product_images) > 0) {
            foreach ($product->product_images as $existedImage) {
                if (
                    $existedImage->image_url !== $validatedData['main_image']
                    && !in_array($existedImage->image_url, $validatedData['images'])
                ) {
                    $existedImage->delete();
                }
            }
        }

        $mainImage = $this->upload_create_image(
            $product->id,
            $validatedData['main_image'],
            true
        );
        if (!$mainImage) {
            return $this->responseFailed(
                "Main image not Found",
                400,
                "Main image not found"
            );
        }


        $images = [];
        foreach ($validatedData['images'] as $image) {
            $image = $this->upload_create_image(
                $product->id,
                $image
            );
            array_push($images, $image);
        }

        return $this->responseSuccess([$mainImage, ...$images], 201);
    }

    public function destroy_image(
        DeleteProductImageRequest $request,
        string $store_slug,
        string $product_slug
    ) {
        $store = Store::where("slug", $store_slug)->first();
        if (!$store) {
            return $this->responseFailed(
                "Store not Found",
                404,
                "Store not found"
            );
        }

        $userStore = Store::where("user_id", auth()->user()->id)
            ->first();

        if ($store->id !== $userStore->id) {
            return $this->responseFailed(
                "Store not belongs to current user",
                401,
                "Unauthorize"
            );
        }

        $product = Product::with(['product_images'])
            ->where("slug_with_store", $store_slug . "/" . $product_slug)
            ->first();
        if (!$product) {
            return $this->responseFailed(
                "Product not Found",
                404,
                "Product not found"
            );
        }

        $validatedData = $request->validated();

        $imageUrl = $validatedData['image_url'];

        $productImage = ProductImage::where('product_id', $product->id)
            ->where('image_url', $imageUrl)
            ->first();
        if (!$productImage) {
            return $this->responseFailed(
                "Product image not Found",
                404,
                "Product image not found"
            );
        }

        if (!str_contains($imageUrl, "unsplash")) {
            CloudinaryStorage::delete($imageUrl);
        }

        $productImage->delete();

        return $this->responseSuccess(['id' => $productImage->id], 200, "Data deleted");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $store_slug, string $product_slug) {
        $product = Product::where(
            "slug_with_store",
            $store_slug . "/" . $product_slug
        )
            ->paramQuery($request->query())
            ->first();
        if (!$product) {
            return $this->responseFailed("Not Found", 404, "Product not found");
        }

        return $this->responseSuccess($product);
    }

    public function paginated_product_reviews(Request $request, string $store_slug, string $product_slug) {
        $product = Product::where(
            "slug_with_store",
            $store_slug . "/" . $product_slug
        )
            ->first();
        if (!$product) {
            return $this->responseFailed("Not Found", 404, "Product not found");
        }

        $reviews = Review::where('product_id', $product->id)->getDataTable($request->query());

        if (!$reviews) {
            return $this->responseFailed("Not Found", 404, "Reviews not found");
        }

        return $this->responseSuccess($reviews);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        string $store_slug,
        string $product_slug
    ) {
        $store = Store::where("slug", $store_slug)->first();
        if (!$store) {
            return $this->responseFailed(
                "Store not Found",
                404,
                "Store not found"
            );
        }

        $userStore = Store::where("user_id", auth()->user()->id)
            ->first();

        if ($store->id !== $userStore->id) {
            return $this->responseFailed(
                "Store not belongs to current user",
                401,
                "Unauthorize"
            );
        }

        $product = Product::where("slug_with_store", $store_slug . "/" . $product_slug)
            ->first();
        if (!$product) {
            return $this->responseFailed(
                "Product not Found",
                404,
                "Product not found"
            );
        }

        $product->delete();

        return $this->responseSuccess(['id' => $product->id], 200, "Data deleted");
    }

    function upload_create_image(string $productId, $image, $isMainImage = false) {
        if (!$image) {
            return null;
        }

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $result = CloudinaryStorage::upload(
                $image->getRealPath(),
                $image->getClientOriginalName()
            );

            $newImage = ProductImage::create([
                'image_url' => $result,
                'main_image' => $isMainImage,
                'product_id' => $productId
            ]);

            return $newImage;
        }

        $existingImage = ProductImage::where('product_id', $productId)
            ->where('main_image', $isMainImage)
            ->where('image_url', $image)
            ->first();
        if ($existingImage) {
            return $existingImage;
        }

        $newImage = ProductImage::create([
            'image_url' => $image,
            'main_image' => $isMainImage,
            'product_id' => $productId
        ]);

        return $newImage;

    }
}
