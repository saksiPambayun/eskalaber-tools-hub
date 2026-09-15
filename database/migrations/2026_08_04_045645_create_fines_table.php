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
            
            // Status
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid'); // <-- TAMBAH: cancelled
            
            // Payment
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // <-- TAMBAH: method pembayaran (cash, transfer, midtrans)
            $table->string('payment_reference')->nullable(); // <-- TAMBAH: referensi pembayaran (MIDTRANS order_id)
            
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