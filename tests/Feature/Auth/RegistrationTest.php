<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home'));
});

test('registration cannot assign privileged roles', function () {
    $response = $this->post('/register', [
        'name' => 'Untrusted User',
        'email' => 'untrusted@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'admin',
    ]);

    $response->assertSessionHasErrors('role');
    $this->assertGuest();
    expect(User::query()->where('email', 'untrusted@example.com')->exists())->toBeFalse();
});