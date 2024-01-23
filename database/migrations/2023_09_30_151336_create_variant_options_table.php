<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('variant_options', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_type_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete("cascade");
            $table->foreign('variant_type_id')->references('id')->on('variant_types');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('variant_options');
    }
};
