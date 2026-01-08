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
       Schema::create('soals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('setting_soal_id')->constrained('setting_soals');
    $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs');
    $table->foreignId('user_id')->constrained('users'); // Dosen yang buat
    $table->string('jenis_soal'); // uts, uas, kuis
    $table->string('tipe_soal');  // pg, esai
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
