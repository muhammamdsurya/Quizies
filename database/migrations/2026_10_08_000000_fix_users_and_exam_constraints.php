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
        // Kolom ini dipakai oleh cast User & halaman profil, tapi hilang dari migrasi users.
        if (! Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        // Satu mahasiswa hanya boleh punya satu attempt per ujian.
        Schema::table('ujian_attempts', function (Blueprint $table) {
            $table->unique(['user_id', 'ujian_id']);
        });

        // Satu jawaban per soal per attempt (updateOrCreate tidak aman dari race condition).
        Schema::table('jawaban_mahasiswas', function (Blueprint $table) {
            $table->unique(['ujian_attempt_id', 'detail_soal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_mahasiswas', function (Blueprint $table) {
            $table->dropUnique(['ujian_attempt_id', 'detail_soal_id']);
        });

        Schema::table('ujian_attempts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'ujian_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
};
