<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\UserWebController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Tests\TestCase;

class UserWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with user list.
     */
    public function test_index_returns_view(): void
    {
        $user = User::factory()->create();
        $controller = new UserWebController();

        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('users.index', $response->name());
        self::assertTrue($response->getData()['users']->contains('id', $user->id));
    }

    /**
     * Test create returns create view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new UserWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('users.create', $response->name());
    }

    /**
     * Test store creates user with hashed password.
     */
    public function test_store_creates_user(): void
    {
        $request = Request::create('/users', 'POST', [
            'name'     => 'Staf Marketing Baru',
            'email'    => 'marketing.baru@klinik.com',
            'password' => 'secret12345',
            'role'     => 'Marketing',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new UserWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('users.index'), $response->getTargetUrl());

        $created = User::where('email', 'marketing.baru@klinik.com')->first();
        self::assertNotNull($created);
        self::assertSame('Marketing', $created->role);
        self::assertTrue(Hash::check('secret12345', $created->password));
    }

    /**
     * Test edit returns edit view.
     */
    public function test_edit_returns_view(): void
    {
        $user = User::factory()->create();
        $controller = new UserWebController();

        $response = $controller->edit($user->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('users.edit', $response->name());
        self::assertTrue($response->getData()['user']->is($user));
    }

    /**
     * Test update modifies user and changes password when provided.
     */
    public function test_update_modifies_user_with_new_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $request = Request::create("/users/{$user->id}", 'PUT', [
            'name'     => 'Nama Baru Terupdate',
            'email'    => $user->email,
            'role'     => 'Admin',
            'password' => 'newpassword123',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new UserWebController();
        $response = $controller->update($request, $user->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('users.index'), $response->getTargetUrl());

        $user->refresh();
        self::assertSame('Nama Baru Terupdate', $user->name);
        self::assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test update leaves password untouched when password is empty.
     */
    public function test_update_preserves_existing_password_when_empty(): void
    {
        $oldHash = Hash::make('tetap_sama');
        $user = User::factory()->create(['password' => $oldHash]);

        $request = Request::create("/users/{$user->id}", 'PUT', [
            'name'     => 'Ganti Nama Saja',
            'email'    => $user->email,
            'role'     => 'Admin',
            'password' => '',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new UserWebController();
        $response = $controller->update($request, $user->id);

        self::assertInstanceOf(RedirectResponse::class, $response);

        $user->refresh();
        self::assertSame('Ganti Nama Saja', $user->name);
        self::assertTrue(Hash::check('tetap_sama', $user->password));
    }

    /**
     * Test destroy deletes user.
     */
    public function test_destroy_deletes_user(): void
    {
        $user = User::factory()->create();
        $controller = new UserWebController();

        $response = $controller->destroy($user->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('users.index'), $response->getTargetUrl());
        self::assertSoftDeleted('users', ['id' => $user->id]);
    }
}
