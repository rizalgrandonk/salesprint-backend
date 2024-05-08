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
            $table->string('order_number')->unique();
            $table->float('total', 10, 2);
            $table->enum('order_status', [
                'UNPAID', 'PAID', 'PROCESSED', 'SHIPPED', 'DELIVERED', 'COMPLETED', 'CANCELED'
            ]);

            $table->integer('shipping_days_estimate')->nullable();
            $table->dateTime('accept_deadline')->nullable();
            $table->dateTime('shipping_deadline')->nullable();
            $table->dateTime('deliver_deadline')->nullable();
            $table->dateTime('recieve_deadline')->nullable();

            $table->dateTime('paid_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('canceled_at')->nullable();

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
            $table->boolean('is_withdrew');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('withdraw_id')->nullable();

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
            $table->foreign('withdraw_id')
                ->references('id')
                ->on('withdraws')
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
