<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the homepage renders the configured church identity', function () {
    config([
        'church.short_name' => 'Église de test',
        'church.tagline' => 'Une communauté de test.',
    ]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Église de test')
        ->assertSee('Une communauté de test.');
});
