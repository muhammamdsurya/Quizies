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
        Schema::create('jawaban_mahasiswas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ujian_attempt_id')->constrained('ujian_attempts')->cascadeOnDelete();
    $table->foreignId('detail_soal_id')->constrained('detail_soal')->cascadeOnDelete();

    // Gunakan text agar bisa menampung jawaban esai yang panjang
    $table->text('jawaban')->nullable(); 

    // Untuk auto-grading PG
    $table->boolean('is_benar')->nullable(); 

    // Untuk penilaian manual Esai
    $table->integer('nilai_esai')->nullable(); 
    $table->text('catatan_dosen')->nullable();

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
