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
            $table->date('tanggal_masuk')->nullable();

            // Status
            $table->string('status_aktif');

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
