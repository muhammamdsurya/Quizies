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
    $table->foreignId('ujian_attempt_id')->constrained()->cascadeOnDelete();
    $table->foreignId('detail_soal_id')->constrained('detail_soal');
    $table->text('jawaban'); // Menyimpan pilihan 'a', 'b', dll atau teks esai
    $table->boolean('is_benar')->nullable(); // Untuk auto-grading PG
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
