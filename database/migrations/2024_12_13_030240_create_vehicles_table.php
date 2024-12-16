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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_company_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('owner_type', ['company', 'rental']);
            $table->enum('vehicle_type', ['passenger', 'cargo']);
            $table->string('license_plate', 20);
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->integer('year');
            $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid']);
            $table->enum('status', ['available', 'maintenance']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
