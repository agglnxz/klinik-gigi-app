<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\JenisGigiWebController;
use App\Models\JenisGigi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class JenisGigiWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with jenis_gigi list.
     */
    public function test_index_returns_view(): void
    {
        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.index', $response->name());
        self::assertTrue($response->getData()['jenis_gigi']->contains('id', $jenis->id));
    }

    /**
     * Test create returns view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new JenisGigiWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.create', $response->name());
    }

    /**
     * Test store creates jenis gigi.
     */
    public function test_store_creates_jenis_gigi(): void
    {
        $request = Request::create('/jenis-gigi', 'POST', [
            'kode_gigi'      => 'GIGI-VALPLAST-01',
            'nama_jenis'     => 'Gigi Tiruan Valplast Fleksibel',
            'estimasi_biaya' => 2000000,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('jenis-gigi.index'), $response->getTargetUrl());
        self::assertDatabaseHas('jenis_gigi', [
            'kode_gigi'      => 'GIGI-VALPLAST-01',
            'nama_jenis'     => 'Gigi Tiruan Valplast Fleksibel',
            'estimasi_biaya' => 2000000,
            'is_aktif'       => 1,
        ]);
    }

    /**
     * Test edit returns update view.
     */
    public function test_edit_returns_view(): void
    {
        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->edit($jenis->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.update', $response->name());
        self::assertTrue($response->getData()['jenisGigi']->is($jenis));
    }

    /**
     * Test update modifies jenis gigi.
     */
    public function test_update_modifies_jenis_gigi(): void
    {
        $jenis = JenisGigi::factory()->create();
        $request = Request::create("/jenis-gigi/{$jenis->id}", 'PUT', [
            'kode_gigi'      => $jenis->kode_gigi,
            'nama_jenis'     => 'Nama Baru Jenis Gigi',
            'estimasi_biaya' => 3500000,
            'is_aktif'       => 0,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();
        $response = $controller->update($request, $jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('jenis-gigi.index'), $response->getTargetUrl());
        self::assertDatabaseHas('jenis_gigi', [
            'id'             => $jenis->id,
            'nama_jenis'     => 'Nama Baru Jenis Gigi',
            'estimasi_biaya' => 3500000,
            'is_aktif'       => 0,
        ]);
    }

    /**
     * Test destroy allows Direktur to delete.
     */
    public function test_destroy_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->destroy($jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('jenis_gigi', ['id' => $jenis->id]);
    }

    /**
     * Test destroy rejects non-direktur.
     */
    public function test_destroy_rejects_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->destroy($jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('jenis_gigi', ['id' => $jenis->id]);
        self::assertTrue(session()->has('error'));
    }

    /**
     * Test store fails and does not save data when estimasi_biaya is negative.
     */
    public function test_store_fails_with_negative_cost_and_does_not_save_data(): void
    {
        $request = Request::create('/jenis-gigi', 'POST', [
            'kode_gigi'      => 'GIGI-NEG-01',
            'nama_jenis'     => 'Gigi Biaya Negatif',
            'estimasi_biaya' => -50000,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for negative estimasi_biaya');
        } catch (\Illuminate\Validation\ValidationException $e) {
            self::assertArrayHasKey('estimasi_biaya', $e->errors());
        }

        self::assertDatabaseCount('jenis_gigi', 0);
    }

    /**
     * Test store fails and does not save data when estimasi_biaya is non-numeric.
     */
    public function test_store_fails_with_non_numeric_cost_and_does_not_save_data(): void
    {
        $request = Request::create('/jenis-gigi', 'POST', [
            'kode_gigi'      => 'GIGI-NONNUM-01',
            'nama_jenis'     => 'Gigi Biaya Huruf',
            'estimasi_biaya' => 'seratus_ribu',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for non-numeric estimasi_biaya');
        } catch (\Illuminate\Validation\ValidationException $e) {
            self::assertArrayHasKey('estimasi_biaya', $e->errors());
        }

        self::assertDatabaseCount('jenis_gigi', 0);
    }

    /**
     * Data provider untuk field-field wajib jenis gigi.
     *
     * @return array<string, array{string}>
     */
    public static function requiredJenisGigiFields(): array
    {
        return [
            'missing_kode_gigi'      => ['kode_gigi'],
            'missing_nama_jenis'     => ['nama_jenis'],
            'missing_estimasi_biaya' => ['estimasi_biaya'],
        ];
    }

    /**
     * Test store fails and does not save data when required fields are empty.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('requiredJenisGigiFields')]
    public function test_store_fails_when_required_field_is_empty(string $missingField): void
    {
        $payload = [
            'kode_gigi'      => 'GIGI-VALID-01',
            'nama_jenis'     => 'Gigi Tiruan Valid',
            'estimasi_biaya' => 750000,
        ];
        $payload[$missingField] = '';

        $request = Request::create('/jenis-gigi', 'POST', $payload);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();

        try {
            $controller->store($request);
            self::fail("Expected ValidationException when field '{$missingField}' is empty");
        } catch (\Illuminate\Validation\ValidationException $e) {
            self::assertArrayHasKey($missingField, $e->errors());
        }

        self::assertDatabaseCount('jenis_gigi', 0);
    }
}
