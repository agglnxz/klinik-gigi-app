<?php

namespace Tests\Unit\Console;

use App\Console\Commands\GenerateNotifikasi;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GenerateNotifikasiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Data provider untuk berbagai kondisi selisih hari estimasi selesai.
     *
     * @return array<string, array{int, string, string|null}>
     */
    public static function dataEstimasiSelesai(): array
    {
        return [
            'terlambat_2_hari' => [-2, 'terlambat 2 hari', 'terlambat'],
            'terlambat_1_hari' => [-1, 'terlambat 1 hari', 'terlambat'],
            'hari_h'           => [0, 'dijadwalkan tiba hari ini', 'segera_tiba'],
            'segera_tiba_h_minus_1' => [1, 'akan segera tiba 1 hari lagi', 'segera_tiba'],
            'segera_tiba_h_minus_3' => [3, 'akan segera tiba 3 hari lagi', 'segera_tiba'],
            'masih_lama_h_minus_4'  => [4, '', null],
        ];
    }

    /**
     * Test handle command generates notifications according to business rules.
     */
    #[DataProvider('dataEstimasiSelesai')]
    public function test_handle_generates_correct_notification_message_and_status(
        int $offsetDays,
        string $expectedMessage,
        ?string $expectedStatus
    ): void {
        // Arrange
        $estimasi = Carbon::today()->addDays($offsetDays);
        $pemesanan = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'estimasi_selesai' => $estimasi,
        ]);

        $command = new GenerateNotifikasi();

        // Act
        $command->handle();

        // Assert
        if ($expectedMessage !== '') {
            $notif = Notifikasi::where('pemesanan_id', $pemesanan->id)->first();
            self::assertNotNull($notif);
            self::assertSame($expectedMessage, $notif->pesan);
            self::assertSame($expectedStatus, $notif->status);
            self::assertFalse((bool) $notif->is_read);
        } else {
            self::assertSame(0, Notifikasi::where('pemesanan_id', $pemesanan->id)->count());
        }
    }

    /**
     * Test handle prevents duplicate notifications on the same day.
     */
    public function test_handle_prevents_duplicate_notification_on_same_day(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'estimasi_selesai' => Carbon::today(),
        ]);

        $command = new GenerateNotifikasi();

        // Act 1: Run once
        $command->handle();
        self::assertSame(1, Notifikasi::where('pemesanan_id', $pemesanan->id)->count());

        // Act 2: Run again on the same day
        $command->handle();

        // Assert: Count should remain 1
        self::assertSame(1, Notifikasi::where('pemesanan_id', $pemesanan->id)->count());
    }

    /**
     * Test handle ignores orders not in 'dalam_proses' status.
     */
    public function test_handle_ignores_non_active_orders(): void
    {
        // Arrange
        $pemesananSelesai = Pemesanan::factory()->create([
            'status_pemesanan' => 'selesai',
            'estimasi_selesai' => Carbon::today()->subDays(2),
        ]);
        $pemesananTiba = Pemesanan::factory()->create([
            'status_pemesanan' => 'tiba_di_klinik',
            'estimasi_selesai' => Carbon::today()->subDays(1),
        ]);

        $command = new GenerateNotifikasi();

        // Act
        $command->handle();

        // Assert
        self::assertSame(0, Notifikasi::where('pemesanan_id', $pemesananSelesai->id)->count());
        self::assertSame(0, Notifikasi::where('pemesanan_id', $pemesananTiba->id)->count());
    }
}
