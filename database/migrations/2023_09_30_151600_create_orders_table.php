<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('total', 10, 2);
            $table->enum('order_status', [
                'UNPAID', 'PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED', 'CANCELED'
            ]);
            $table->string('shipping_deadline')->nullable();
            $table->string('recieve_deadline')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->string('shipping_tracking_number')->nullable();
            $table->string('shipping_status');
            $table->string('shipping_courier');
            $table->json('shipping_history')->nullable();
            $table->string('reciever_name');
            $table->string('reciever_phone');
            $table->string('delivery_address');
            $table->string('delivery_province_id');
            $table->string('delivery_city_id');
            $table->string('delivery_province');
            $table->string('delivery_city');
            $table->string('delivery_postal_code');
            $table->string('delivery_service');
            $table->float('delivery_cost', 10, 2);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();

            $table->foreign('user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->nullOnDelete();
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
