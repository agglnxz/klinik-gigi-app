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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id(); // Auto-Increment PK
            $table->string('no_pemesanan', 20)->unique(); // Contoh: ORD-001
            $table->date('tanggal_dikirim');
            $table->date('estimasi_selesai');
            $table->decimal('biaya_lab', 15, 2);
            $table->decimal('harga_pasien', 15, 2);
            $table->enum('status_bayar_lab', ['Belum Lunas', 'Sudah Lunas'])->default('Belum Lunas');
            $table->enum('status_pemesanan', ['Proses Lab', 'Telah Tiba', 'Sudah Dipasang'])->default('Proses Lab');

            // Relasi
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('id_lab');
            $table->unsignedBigInteger('id_jenis_gigi');

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan')->onDelete('cascade');
            $table->foreign('id_lab')->references('id')->on('laboratorium')->onDelete('cascade');
            $table->foreign('id_jenis_gigi')->references('id')->on('jenis_gigi')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
