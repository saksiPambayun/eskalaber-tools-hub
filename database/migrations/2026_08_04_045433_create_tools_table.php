<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('type_id')->constrained();
            $table->foreignId('place_id')->constrained();
            $table->integer('stock')->default(0);
            $table->enum('status', ['available', 'borrowed', 'broken', 'maintenance'])->default('available');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
