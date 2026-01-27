<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->dateTime('assigned_at');
            $table->dateTime('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['tool_id', 'returned_at']);
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_assignments');
    }
};
