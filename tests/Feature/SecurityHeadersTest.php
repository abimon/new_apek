<?php

test('secure responses include the configured security headers', function () {
    $this->get('https://localhost/up')
        ->assertOk()
        ->assertHeader('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-XSS-Protection', '1; mode=block');
});

test('http responses do not advertise hsts', function () {
    $this->get('/up')
        ->assertOk()
        ->assertHeaderMissing('Strict-Transport-Security')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-XSS-Protection', '1; mode=block');
});
