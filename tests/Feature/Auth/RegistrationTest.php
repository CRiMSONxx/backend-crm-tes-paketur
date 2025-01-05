<?php

test('registration harusnya tidak tampil', function () {
    $response = $this->get('/register');

    $response->assertStatus(200)->toBeFalse();
});

test('registrasi admin/ke tabel users harus disable/gagal', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated()->toBeFalse();
    $response->assertRedirect(route('dashboard', absolute: false));
});
