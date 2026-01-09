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
        $table->foreignId('user_id')->constrained('users');

        // Cukup tulis begini, dia otomatis akan berada di bawah user_id
        $table->string('nama_soal')->nullable();

        $table->string('jenis_soal');
        $table->string('tipe_soal');
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
        $table->dropColumn('nama_soal');
    });
    }
};
