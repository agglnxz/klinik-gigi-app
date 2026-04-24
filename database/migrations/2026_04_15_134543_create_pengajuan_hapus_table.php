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
        Schema::create('pengajuan_hapus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tabel'); // Isi: pasien, dokter, dll
            $table->string('id_referensi'); // Bisa INT atau VARCHAR, makanya pakai string
            $table->string('nama_data'); // Nama item untuk ditampilkan
            $table->text('alasan_hapus');
            $table->enum('status_approval', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');

            // Siapa yang mengajukan hapus?
            $table->unsignedBigInteger('id_pemohon');
            $table->foreign('id_pemohon')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_hapus');
    }
};
