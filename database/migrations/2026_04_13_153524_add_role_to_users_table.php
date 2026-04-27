<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'dosen', 'mahasiswa', 'asdos'])
                    ->default('mahasiswa')
                    ->after('email');
            }

            if (! Schema::hasColumn('users', 'nidn')) {
                $table->string('nidn')->nullable()->after('role');
            }

            if (! Schema::hasColumn('users', 'nim')) {
                $table->string('nim')->nullable()->after('nidn');
            }

            if (! Schema::hasColumn('users', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable()->after('nim');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('users', 'dosen_id')) {
                $dropColumns[] = 'dosen_id';
            }

            if (Schema::hasColumn('users', 'nim')) {
                $dropColumns[] = 'nim';
            }

            if (Schema::hasColumn('users', 'nidn')) {
                $dropColumns[] = 'nidn';
            }

            if (Schema::hasColumn('users', 'role')) {
                $dropColumns[] = 'role';
            }

            if (! empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};