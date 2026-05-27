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
        Schema::create('notifikasi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
    $table->text('pesan');
    $table->enum('status', ['segera_tiba', 'terlambat', 'info'])->default('info');
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
