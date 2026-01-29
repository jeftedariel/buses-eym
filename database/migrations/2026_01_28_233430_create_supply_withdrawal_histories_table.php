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
        Schema::create('supply_withdrawal_histories', function (Blueprint $table) {
            $table->id();
            $table->string('notes')->nullable();
            $table->integer('quantity');
            $table->foreignId('supply_id')->constrained('supplies')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->datetime('withdrawn_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_withdrawal_histories');
    }
};
