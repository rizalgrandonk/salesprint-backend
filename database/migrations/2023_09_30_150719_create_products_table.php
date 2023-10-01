<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->float('price');
            $table->integer('stok');
            $table->float('average_rating');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('store_category_id')->nullable();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('store_category_id')->nullable()
            ->references('id')->on('store_categories');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
