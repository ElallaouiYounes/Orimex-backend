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
        Schema::create('logistics', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('license_plate');
            $table->string('type');
            $table->integer('capacity');
            $table->string('current_location');
            $table->enum('status', ['available', 'in-transit', 'under-maintenance'])->default('available');
            $table->string('driver');
            $table->timestamp('last_maintenance');
            $table->timestamp('next_maintenance');
            $table->string('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics');
    }
};
