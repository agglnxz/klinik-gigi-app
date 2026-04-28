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
        Schema::create('jenis_gigi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gigi', 20)->unique();
            $table->string('nama_jenis', 100);
            $table->decimal('estimasi_biaya', 15, 2); // Harga Acuan
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_gigi');
    }
};
