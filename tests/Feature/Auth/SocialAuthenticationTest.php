<?php

use App\Models\SocialAccount;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

test('new users can authenticate using a social provider callback', function () {
    /** @var TestCase $this */
    Socialite::fake('github', (new SocialiteUser)->map([
        'id' => 'github-123',
        'name' => 'Jason Beggs',
        'email' => 'jason@example.com',
    ]));

    $response = $this->get(route('auth.callback', ['social' => 'github']));

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'jason@example.com')->first();

    expect($user)->not->toBeNull();

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider_name' => 'github',
        'provider_id' => 'github-123',
    ]);
});

test('existing social accounts authenticate the linked user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'jason@example.com',
    ]);

    SocialAccount::create([
        'user_id' => $user->id,
        'provider_name' => 'github',
        'provider_id' => 'github-123',
    ]);

    Socialite::fake('github', (new SocialiteUser)->map([
        'id' => 'github-123',
        'name' => 'Jason Beggs',
        'email' => 'jason@example.com',
    ]));

    $response = $this->get(route('auth.callback', ['social' => 'github']));

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticatedAs($user);
    expect(SocialAccount::query()->count())->toBe(1);
});
