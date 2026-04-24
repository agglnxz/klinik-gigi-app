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
       Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id(); // Auto-Increment PK
            $table->string('no_pemeriksaan', 20)->unique(); // Contoh: PRX-001
            $table->date('tanggal');
            $table->text('catatan');

            // Relasi sekarang memanggil 'id' angka semua! Jauh lebih rapi!
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_dokter');
            $table->unsignedBigInteger('id_asisten');

            $table->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id')->on('dokter')->onDelete('cascade');
            $table->foreign('id_asisten')->references('id')->on('asisten')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
