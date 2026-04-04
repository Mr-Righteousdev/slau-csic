<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the public projects page', function () {
    $response = $this->get(route('projects'));

    $response->assertSuccessful();
    $response->assertSee('Projects and Delivery');
});

it('shows approved members in the public directory', function () {
    User::factory()->create([
        'name' => 'Doreen Nanyonga',
        'email' => 'doreen@example.com',
        'membership_status' => 'active',
        'approved_at' => now(),
        'joined_at' => now()->subMonths(4),
        'headline' => 'Digital forensics learner',
        'bio' => 'Focused on evidence handling, structured investigation, and club challenge work.',
        'privacy_settings' => [
            'show_profile' => true,
            'show_program' => true,
            'show_year' => true,
        ],
    ]);

    $response = $this->get(route('members.public'));

    $response->assertSuccessful();
    $response->assertSee('Doreen Nanyonga');
});

it('shows the public profile of an approved visible member', function () {
    $member = User::factory()->create([
        'name' => 'Paul Kato',
        'email' => 'paul@example.com',
        'membership_status' => 'active',
        'approved_at' => now(),
        'joined_at' => now()->subMonths(2),
        'headline' => 'CTF participant',
        'bio' => 'Building practical experience through club labs, competition practice, and secure development exercises.',
        'privacy_settings' => [
            'show_profile' => true,
            'show_program' => true,
            'show_year' => true,
        ],
    ]);

    $response = $this->get(route('members.public.show', $member));

    $response->assertSuccessful();
    $response->assertSee('Paul Kato');
    $response->assertSee('CTF participant');
});
