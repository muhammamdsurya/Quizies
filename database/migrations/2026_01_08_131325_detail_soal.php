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
    // Relasi ke header
    $table->foreignId('soals_id')->constrained('soals')->cascadeOnDelete();

    $table->integer('nomor_soal');
    $table->text('pertanyaan');

    // Kolom PG (nullable)
    $table->text('opsi_a')->nullable();
    $table->text('opsi_b')->nullable();
    $table->text('opsi_c')->nullable();
    $table->text('opsi_d')->nullable();
    $table->string('kunci_jawaban', 1)->nullable();

    // Kolom Esai (nullable)
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
