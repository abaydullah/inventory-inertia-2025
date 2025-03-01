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
        Schema::create('product_sells', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class)->constrained();
            $table->foreignIdFor(\App\Models\InvoiceProduct::class)->constrained();
            $table->double('buy_price')->default(0);
            $table->double('sell_price')->default(0);
            $table->double('discount')->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount_amount')->default(0);
            $table->double('total_buy_price')->default(0);
            $table->double('total_sell_price')->default(0);
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('unit_size')->nullable();
            $table->unsignedInteger('qty')->default(0);
            $table->unsignedInteger('user_id')->index();
            $table->softDeletes();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sells');
    }
};
