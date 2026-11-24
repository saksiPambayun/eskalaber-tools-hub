<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Payment method FK for fresh schema that created column without FK
        if (Schema::hasTable('fines') && Schema::hasTable('payment_methods') && Schema::hasColumn('fines', 'payment_method_id')) {
            try {
                Schema::table('fines', function (Blueprint $table) {
                    $table->foreign('payment_method_id')->references('id')->on('payment_methods')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // FK may already exist
            }
        }

        // Loans: add cancelled to status on MySQL existing DBs; sqlite uses string already in fresh schema.
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('loans')) {
            DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending','approved','rejected','borrowed','returned','late','cancelled') NOT NULL DEFAULT 'pending'");
        }

        // Fines: widen status and add new columns for existing DBs
        if (Schema::hasTable('fines')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE fines MODIFY COLUMN status VARCHAR(32) NOT NULL DEFAULT 'unpaid'");
            }

            Schema::table('fines', function (Blueprint $table) {
                if (!Schema::hasColumn('fines', 'payment_method_id')) {
                    $table->unsignedBigInteger('payment_method_id')->nullable()->after('status');
                }
                if (!Schema::hasColumn('fines', 'proof_image')) {
                    $table->string('proof_image')->nullable()->after('payment_reference');
                }
                if (!Schema::hasColumn('fines', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable()->after('proof_image');
                }
                if (!Schema::hasColumn('fines', 'reviewed_by')) {
                    $table->foreignId('reviewed_by')->nullable()->after('payment_date')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('fines', 'reviewed_at')) {
                    $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
                }
                if (!Schema::hasColumn('fines', 'review_note')) {
                    $table->text('review_note')->nullable()->after('reviewed_at');
                }
            });

            // Add FK if missing (MySQL existing DB)
            try {
                Schema::table('fines', function (Blueprint $table) {
                    $table->foreign('payment_method_id')->references('id')->on('payment_methods')->nullOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        // Non-destructive downgrade not fully implemented; columns kept.
    }
};
