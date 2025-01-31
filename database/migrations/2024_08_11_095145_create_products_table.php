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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->double('buy_price')->default(0);
            $table->double('sell_price')->default(0);
            $table->string('barcode')->nullable();
            $table->unsignedInteger('warning_stock')->default(0);
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('opening_stock')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('products');
    }
};
