<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\AuthWebController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Tests\TestCase;

class AuthWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test showLoginForm returns auth.login view.
     */
    public function test_show_login_form_returns_view(): void
    {
        // Arrange
        $controller = new AuthWebController();

        // Act
        $response = $controller->showLoginForm();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('auth.login', $response->name());
    }

    /**
     * Test login succeeds with active user and valid credentials.
     */
    public function test_login_succeeds_with_valid_credentials(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email'    => 'admin@klinik.com',
            'password' => Hash::make('password123'),
            'is_aktif' => true,
        ]);

        $request = Request::create('/login', 'POST', [
            'email'    => 'admin@klinik.com',
            'password' => 'password123',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthWebController();

        // Act
        $response = $controller->login($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(url('/dashboard'), $response->getTargetUrl());
        self::assertTrue(Auth::check());
        self::assertSame($user->id, Auth::id());
    }

    /**
     * Test login fails with invalid password.
     */
    public function test_login_fails_with_invalid_password(): void
    {
        // Arrange
        User::factory()->create([
            'email'    => 'admin@klinik.com',
            'password' => Hash::make('password123'),
            'is_aktif' => true,
        ]);

        $request = Request::create('/login', 'POST', [
            'email'    => 'admin@klinik.com',
            'password' => 'wrongpassword',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthWebController();

        // Act
        $response = $controller->login($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertFalse(Auth::check());
        self::assertTrue(session()->has('errors'));
    }

    /**
     * Test login fails when user is inactive (is_aktif = 0).
     */
    public function test_login_fails_when_user_is_inactive(): void
    {
        // Arrange
        User::factory()->create([
            'email'    => 'inactive@klinik.com',
            'password' => Hash::make('password123'),
            'is_aktif' => false,
        ]);

        $request = Request::create('/login', 'POST', [
            'email'    => 'inactive@klinik.com',
            'password' => 'password123',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthWebController();

        // Act
        $response = $controller->login($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertFalse(Auth::check());
        self::assertTrue(session()->has('errors'));
    }

    /**
     * Test logout clears session and redirects to /login.
     */
    public function test_logout_logs_user_out_and_redirects(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);
        self::assertTrue(Auth::check());

        $request = Request::create('/logout', 'POST');
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthWebController();

        // Act
        $response = $controller->logout($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(url('/login'), $response->getTargetUrl());
        self::assertFalse(Auth::check());
    }
}
