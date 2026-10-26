<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\ProfileController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns profile.index view.
     */
    public function test_index_returns_view(): void
    {
        $controller = new ProfileController();
        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('profile.index', $response->name());
    }

    /**
     * Test updateFoto uploads new avatar and deletes old file if present.
     */
    public function test_update_foto_uploads_file_and_removes_old(): void
    {
        // Arrange
        Storage::fake('public');

        $oldFile = 'foto-profil/old_avatar.png';
        Storage::disk('public')->put($oldFile, 'dummy content');

        $user = User::factory()->create(['foto' => $oldFile]);
        Auth::login($user);

        $newImage = UploadedFile::fake()->image('new_avatar.jpg', 200, 200);

        $request = Request::create('/profile/foto', 'POST', [], [], [
            'foto' => $newImage,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new ProfileController();

        // Act
        $response = $controller->updateFoto($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('success'));

        $user->refresh();
        self::assertNotNull($user->foto);
        self::assertNotSame($oldFile, $user->foto);

        // Old file deleted
        Storage::disk('public')->assertMissing($oldFile);

        // New file exists
        Storage::disk('public')->assertExists($user->foto);
    }
}
