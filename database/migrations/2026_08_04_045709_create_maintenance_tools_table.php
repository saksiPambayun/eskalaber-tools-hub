<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->enum('status', ['broken', 'maintenance'])->default('broken');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_tools');
    }
};
