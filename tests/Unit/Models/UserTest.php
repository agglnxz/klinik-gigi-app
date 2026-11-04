<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fillable attributes and mass assignment.
     */
    public function test_user_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'name'     => 'Dr. Budi Utomo',
            'email'    => 'budi@klinik.com',
            'password' => 'secret123',
            'role'     => 'Admin',
            'is_aktif' => true,
            'foto'     => 'foto-profil/avatar.png',
        ];

        // Act
        $user = User::create($data);

        // Assert
        self::assertInstanceOf(User::class, $user);
        self::assertSame('Dr. Budi Utomo', $user->name);
        self::assertSame('budi@klinik.com', $user->email);
        self::assertSame('Admin', $user->role);
        self::assertTrue((bool) $user->is_aktif);
        self::assertSame('foto-profil/avatar.png', $user->foto);
    }

    /**
     * Test password casting automatically hashes plain password.
     */
    public function test_user_password_is_automatically_hashed(): void
    {
        // Arrange
        $plainPassword = 'supersecretpassword';

        // Act
        $user = User::factory()->create([
            'password' => $plainPassword,
        ]);

        // Assert
        self::assertNotSame($plainPassword, $user->password);
        self::assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test hidden attributes are not exposed in array/json serialization.
     */
    public function test_hidden_attributes_are_excluded_from_serialization(): void
    {
        // Arrange
        $user = User::factory()->create([
            'password'       => 'rahasia123',
            'remember_token' => 'randomtoken123',
        ]);

        // Act
        $array = $user->toArray();

        // Assert
        self::assertArrayNotHasKey('password', $array);
        self::assertArrayNotHasKey('remember_token', $array);
        self::assertArrayHasKey('name', $array);
        self::assertArrayHasKey('email', $array);
    }

    /**
     * Test user soft delete behavior.
     */
    public function test_user_supports_soft_deletes(): void
    {
        // Arrange
        $user = User::factory()->create();
        $userId = $user->id;

        // Act
        $user->delete();

        // Assert
        self::assertSoftDeleted('users', ['id' => $userId]);
        self::assertNull(User::find($userId));
        self::assertNotNull(User::withTrashed()->find($userId));
    }
}
