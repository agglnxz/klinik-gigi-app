<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CheckRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test handle throws 403 if user is not authenticated.
     */
    public function test_handle_aborts_403_when_unauthenticated(): void
    {
        // Arrange
        $middleware = new CheckRole();
        $request = Request::create('/dashboard', 'GET');
        Auth::logout();

        // Assert expectation
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Anda tidak memiliki hak akses untuk halaman ini.');

        // Act
        $middleware->handle($request, function () {
            return new Response('OK');
        }, 'Admin', 'Direktur');
    }

    /**
     * Data provider untuk kombinasi role yang diizinkan dan ditolak.
     *
     * @return array<string, array{string, array<string>, bool}>
     */
    public static function dataRoleDanIzin(): array
    {
        return [
            'admin_akses_halaman_admin'         => ['Admin', ['Admin'], true],
            'admin_akses_halaman_multi_role'     => ['Admin', ['Admin', 'Direktur'], true],
            'marketing_akses_halaman_marketing'  => ['Marketing', ['Marketing'], true],
            'direktur_akses_halaman_direktur'    => ['Direktur', ['Direktur'], true],
            'admin_akses_halaman_khusus_direktur'=> ['Admin', ['Direktur'], false],
            'marketing_akses_halaman_admin'      => ['Marketing', ['Admin'], false],
        ];
    }

    /**
     * Test handle dengan berbagai role menggunakan DataProvider.
     *
     * @param array<string> $allowedRoles
     */
    #[DataProvider('dataRoleDanIzin')]
    public function test_handle_role_access(string $userRole, array $allowedRoles, bool $shouldPass): void
    {
        // Arrange
        $user = User::factory()->create(['role' => $userRole]);
        Auth::login($user);

        $middleware = new CheckRole();
        $request = Request::create('/test-url', 'GET');
        $nextCalled = false;
        $next = function ($req) use (&$nextCalled) {
            $nextCalled = true;
            return new Response('NEXT_CALLED');
        };

        if (!$shouldPass) {
            $this->expectException(HttpException::class);
            $this->expectExceptionMessage('Anda tidak memiliki hak akses untuk halaman ini.');
        }

        // Act
        $response = $middleware->handle($request, $next, ...$allowedRoles);

        // Assert
        if ($shouldPass) {
            self::assertTrue($nextCalled);
            self::assertSame('NEXT_CALLED', $response->getContent());
        }
    }
}
