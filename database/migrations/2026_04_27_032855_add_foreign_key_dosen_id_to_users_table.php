<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dosen') && Schema::hasColumn('users', 'dosen_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('dosen_id')
                    ->references('id')
                    ->on('dosen')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['dosen_id']);
            } catch (\Throwable $e) {
            }
        });
    }
};