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
       Schema::create('pasien', function (Blueprint $table) {
            $table->id(); // Auto-Increment PK untuk mesin database
            $table->string('no_rm', 20)->unique(); // Contoh: RM-001 (Untuk dilihat manusia)
            $table->string('nama', 150);
            $table->string('kontak', 20);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
