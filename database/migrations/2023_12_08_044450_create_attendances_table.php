<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id');
            $table->foreign('institute_id')->references('institute_id')->on('institutes');
            $table->string('staff_id');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->enum('type', ['teaching', 'non-teaching'])->default(null);
            $table->enum('is_present', ['yes', 'no']);
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->enum('is_deleted', ['yes', 'no'])->default('no');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
