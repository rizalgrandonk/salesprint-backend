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

            $table->foreignId('category_id')->constarined()->nullOnDelete();
            $table->foreignId('store_id')->constarined()->nullOnDelete();
            $table->foreignId('store_category_id')
                ->nullable()
                ->constarined()
                ->nullOnDelete();
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
