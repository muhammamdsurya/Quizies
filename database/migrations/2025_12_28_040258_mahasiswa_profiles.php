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
         Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Data mahasiswa
            $table->string('nim')->unique();
            $table->foreignId('prodi_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->year('tahun_masuk')->nullable();
            $table->date('tanggal_masuk')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Satu user hanya satu profil mahasiswa
            $table->unique('user_id');
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
