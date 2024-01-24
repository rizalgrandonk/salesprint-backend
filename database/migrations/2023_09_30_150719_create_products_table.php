<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('slug_with_store')->unique();
            $table->longText('description');
            $table->float('price', 10, 2);
            $table->integer('stok');
            $table->string('sku');
            $table->float('average_rating');
            $table->integer('weight');
            $table->integer('length');
            $table->integer('width');
            $table->integer('height');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('store_category_id')->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete("cascade");
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete("cascade");
            $table->foreign('store_category_id')
                ->nullable()
                ->references('id')
                ->on('store_categories')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
