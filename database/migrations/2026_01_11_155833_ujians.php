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
        Schema::create('ujians', function (Blueprint $table) {
    $table->id();
    $table->string('judul_ujian'); // Contoh: Ujian Tengah Semester Gasal
    $table->foreignId('user_id')->constrained('users'); // Dosen pembuat ujian
    $table->foreignId('soals_id')->constrained('soals')->onDelete('cascade'); // Tambahkan ini; // Mengambil paket soal
    $table->dateTime('waktu_mulai');
    $table->dateTime('waktu_selesai');
    $table->integer('durasi_menit');
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
