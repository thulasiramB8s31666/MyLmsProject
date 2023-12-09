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
        Schema::create('offline__classes', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id');
            $table->string('institute_course_id');
            $table->string('offline_class_id')->unique();
            $table->string('batch_id');
            $table->string('Staff_id');
            $table->foreign('Staff_id')->references('Staff_id')->on('staff');
            $table->foreign('institute_course_id')->references('institute_course_id')->on('institute_courses');
            $table->foreign('batch_id')->references('batch_id')->on('institute_course_batches');           
            $table->foreign('institute_id')->references('institute_id')->on('institute_course_batches');
            $table->enum('is_deleted', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline__classes');
    }
};
