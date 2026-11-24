<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Fine details
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable(); // <-- TAMBAH: deskripsi denda
            $table->integer('late_days')->default(0); // <-- TAMBAH: jumlah hari telat
            
            // Status: unpaid -> pending_verification -> paid / rejected (can resubmit)
            $table->string('status', 32)->default('unpaid');

            // Payment / verification
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('payment_method')->nullable(); // snapshot nama metode
            $table->string('payment_reference')->nullable();
            $table->string('proof_image')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->date('payment_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_note')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'status']);
            $table->index(['loan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};