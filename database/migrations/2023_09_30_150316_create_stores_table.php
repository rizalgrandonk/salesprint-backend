<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('phone_number');
            $table->string('address');
            $table->string('city');
            $table->string('city_id');
            $table->string('province');
            $table->string('province_id');
            $table->string('postal_code');
            $table->enum('status', ['approved', 'on_review', 'rejected']);
            $table->string('image')->nullable();
            $table->longText('store_description')->nullable();
            $table->float('total_balance', 15, 2);
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('stores');
    }
};
