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
        Schema::create('detail_soal', function (Blueprint $table) {
    $table->id();
    $table->foreignId('soals_id')->constrained('soals')->cascadeOnDelete();
    $table->integer('nomor_soal');
    $table->text('pertanyaan');

    // Tambahkan ini: untuk membedakan logika tampilan & penilaian
   $table->string('tipe_soal');

    // Kolom PG (nullable)
    $table->text('opsi_a')->nullable();
    $table->text('opsi_b')->nullable();
    $table->text('opsi_c')->nullable();
    $table->text('opsi_d')->nullable();
    $table->string('kunci_jawaban', 1)->nullable();

    // Kolom Esai (Seringkali esai butuh bobot nilai berbeda)
    $table->text('petunjuk_esai')->nullable();
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
