<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->float('total_amount', 15, 2);
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');
            $table->enum('status', ['PENDING', 'PAID', 'DENIED']);
            $table->string('denied_reason')->nullable();
            $table->string('receipt')->nullable();
            $table->unsignedBigInteger('store_id');


            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('withdraws');
    }
};
