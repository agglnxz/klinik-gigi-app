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
        Schema::create('pesanan_lab', function (Blueprint $table) {
            $table->id();

            // Foreign Keys Cascade
            $table->foreignId('id_pemesanan')
                  ->constrained('pemesanan')
                  ->onDelete('cascade');

            $table->foreignId('id_lab')
                  ->constrained('laboratorium')
                  ->onDelete('cascade');

            // Operasional Lab Tracking
            $table->date('tanggal_dikirim');
            $table->date('estimasi_selesai');
            $table->date('tanggal_tiba')->nullable();
            $table->decimal('biaya_lab', 15, 2);
            $table->enum('status_bayar_lab', ['belum_lunas', 'sudah_lunas'])->default('belum_lunas');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_lab');
    }
};
