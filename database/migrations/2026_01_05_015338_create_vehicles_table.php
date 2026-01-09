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
            $table->unsignedInteger('capacity');
            $table->string('transmission');
            $table->string('motor_displacement');
            $table->string('color');
            $table->string('license_plate')->nullable();
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('available')->nullable()->default(true);
            $table->boolean('displayable')->nullable()->default(true);
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('cascade');
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
