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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->double('subtotal')->default(0);
            $table->double('discount')->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount_amount')->default(0);
            $table->double('total')->default(0);
            $table->double('payment')->default(0);
            $table->double('due')->default(0);
            $table->unsignedInteger('group_id')->nullable();
            $table->foreignId('customer_id')->constrained();
            $table->date('date');
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
        Schema::dropIfExists('invoices');
    }
};
