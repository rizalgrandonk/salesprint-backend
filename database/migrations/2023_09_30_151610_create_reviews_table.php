<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->longText('coment');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();

            $table->foreign('user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete("cascade");
            $table->foreign('product_variant_id')
                ->nullable()
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
