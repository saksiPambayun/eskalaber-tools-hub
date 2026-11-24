<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fine_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rate_per_day')->default(10000);
            $table->timestamps();
        });

        Schema::create('fine_setting_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fine_setting_id')->constrained('fine_settings')->cascadeOnDelete();
            $table->unsignedInteger('old_rate');
            $table->unsignedInteger('new_rate');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fine_setting_histories');
        Schema::dropIfExists('fine_settings');
    }
};
