<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'mahasiswa', 'asdos'])->default('mahasiswa')->after('email');
            $table->string('nidn')->nullable()->after('role');
            $table->string('nim')->nullable()->after('nidn');
            $table->foreignId('dosen_id')->nullable()->after('nim')->constrained('dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['role', 'nidn', 'nim', 'dosen_id']);
        });
    }
};