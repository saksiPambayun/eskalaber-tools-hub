<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tools', 'type_id')) {
            Schema::table('tools', function (Blueprint $table) {
                $table->dropForeign(['type_id']);
            });

            Schema::table('tools', function (Blueprint $table) {
                $table->dropColumn('type_id');
            });
        }

        Schema::dropIfExists('types');
    }

    public function down(): void
    {
        if (! Schema::hasTable('types')) {
            Schema::create('types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('tools', 'type_id')) {
            Schema::table('tools', function (Blueprint $table) {
                $table->foreignId('type_id')->nullable()->after('category_id')->constrained();
            });
        }
    }
};
