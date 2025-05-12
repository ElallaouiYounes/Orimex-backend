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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->string('employee_id');
            $table->foreign('employee_id')->references('id')->on('team')->onDelete('cascade');

            $table->string('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('logistics')->onDelete('cascade');

            $table->dateTime('start_date');
            $table->dateTime('est arrival');

            $table->string('route_from');
            $table->string('route_current');
            $table->string('route_to');
            
            $table->enum('status', ['pending', 'in-transit', 'delivered', 'failed'])->default('pending');
            $table->string('contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
