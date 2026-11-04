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
        Schema::table('pemesanan_items', function (Blueprint $table) {
            $table->decimal('harga_jual_pasien', 15, 2)->after('id_jenis_gigi');
            $table->decimal('biaya_modal_lab', 15, 2)->nullable()->after('harga_jual_pasien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_items', function (Blueprint $table) {
            $table->dropColumn(['harga_jual_pasien', 'biaya_modal_lab']);
        });
    }
};
