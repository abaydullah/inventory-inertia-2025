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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('staff_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('designation_id')->nullable();
            $table->string('staff_type')->nullable();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->default('Bangladesh');
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nid')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile')->nullable();
            $table->string('educational_qualification')->nullable();
            $table->string('experience')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('exemption_date')->nullable();
            $table->string('email')->nullable();
            $table->string('machine_user_id')->nullable();
            $table->dateTime('office_start')->nullable();
            $table->dateTime('office_end')->nullable();
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
        Schema::dropIfExists('staff');
    }
};
