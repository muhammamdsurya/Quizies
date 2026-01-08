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
        Schema::create('setting_soals', function (Blueprint $table) {
        $table->id();
        $table->string('tahun_akademik'); // Contoh: 2025/2026
        $table->json('jenis_soal_options'); // Menyimpan ['uts', 'uas', 'kuis']
        $table->json('tipe_soal_options');  // Menyimpan ['pg', 'esai']
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
