<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->unsignedBigInteger('order_id');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete("cascade");
            $table->foreign('product_variant_id')
                ->nullable()
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
