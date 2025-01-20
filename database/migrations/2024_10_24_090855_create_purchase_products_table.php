<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('purchase_id')->constrained();
            $table->double('buy_price');
            $table->double('sell_price');
            $table->double('discount')->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount_amount')->default(0);
            $table->unsignedBigInteger('qty')->index();
            $table->double('total_buy_price');
            $table->double('total_sell_price');
            $table->unsignedInteger('user_id')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_products');
    }
};
