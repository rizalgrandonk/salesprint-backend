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
        Schema::create('product_variant_variant_option', function (Blueprint $table) {
            $table->id();
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants');
            $table->foreign('variant_option_id')
                ->references('id')
                ->on('variant_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_variant_option');
    }
};
