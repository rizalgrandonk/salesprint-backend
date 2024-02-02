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
                'UNPAID', 'PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'
            ]);
            $table->string('shipping_tracking_number')->nullable();
            $table->string('shipping_status');
            $table->string('shipping_courier');
            $table->json('shipping_history');
            $table->string('serial_order');
            $table->string('transaction_id');
            $table->string('payment_status');
            $table->string('status_code');
            $table->string('payment_type');
            $table->string('payment_code')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('delivery_address');
            $table->string('delivery_service');
            $table->float('delivery_cost', 10, 2);
            $table->string('receipt_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();

            $table->foreign('user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
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
