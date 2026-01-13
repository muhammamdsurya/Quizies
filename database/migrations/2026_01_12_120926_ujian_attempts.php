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
        Schema::create('ujian_attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Mahasiswa
    $table->foreignId('ujian_id')->constrained('ujians')->cascadeOnDelete();
    $table->dateTime('mulai_pada');
    $table->dateTime('selesai_pada')->nullable(); // Terisi jika sudah klik submit
    $table->integer('skor_akhir')->nullable();
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
