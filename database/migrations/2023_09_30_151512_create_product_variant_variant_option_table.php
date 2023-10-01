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
            $table->foreignId('product_variant_id')
                ->constarined()
                ->nullOnDelete();
            $table->foreignId('variant_option_id')
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
        Schema::dropIfExists('product_variant_variant_option');
    }
};
