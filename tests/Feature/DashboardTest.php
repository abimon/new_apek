<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('admin users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create(['role' => 'admin']));

    $this->get('/dashboard')->assertOk();
});

test('non-admin users cannot visit the dashboard', function () {
    $this->actingAs(User::factory()->create(['role' => 'guest']));

    $this->get('/dashboard')->assertForbidden();
});