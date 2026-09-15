<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('tool_id')->constrained();
            $table->date('loan_date');
            $table->date('return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'late'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
