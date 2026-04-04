<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores the extended member biodata during registration', function () {
    Storage::fake('public');
    Event::fake([Registered::class]);

    $response = $this->post(route('register'), [
        'name' => 'Grace Namutebi',
        'email' => 'grace@example.com',
        'student_id' => 'SLAU/2026/001',
        'phone' => '0700000001',
        'program' => 'Bachelor of Information Technology',
        'faculty' => 'Faculty of Science and Technology',
        'year_of_study' => 3,
        'date_of_birth' => '2002-06-04',
        'gender' => 'Female',
        'residence' => 'Nsambya Hostel',
        'headline' => 'Aspiring web application security analyst',
        'bio' => 'I am focused on practical web security, beginner friendly labs, and helping the club organise stronger learning routines across campus.',
        'specialization_track' => 'Web security',
        'emergency_contact_name' => 'Ritah Namutebi',
        'emergency_contact_phone' => '0700000002',
        'github_username' => 'gracecodes',
        'linkedin_url' => 'https://linkedin.com/in/grace-namutebi',
        'discord_username' => 'grace#0001',
        'profile_photo' => UploadedFile::fake()->image('passport.jpg'),
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => '1',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'grace@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->student_id)->toBe('SLAU/2026/001')
        ->and($user->headline)->toBe('Aspiring web application security analyst')
        ->and($user->membership_status)->toBe('pending')
        ->and($user->profile_photo)->not->toBeNull();

    Storage::disk('public')->assertExists($user->profile_photo);
    Event::assertDispatched(Registered::class);
    $this->assertAuthenticatedAs($user);
});

it('requires a passport photo and key biodata fields to register', function () {
    $response = $this->from(route('register'))->post(route('register'), [
        'name' => 'Missing Fields',
        'email' => 'missing@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors([
        'student_id',
        'phone',
        'program',
        'year_of_study',
        'date_of_birth',
        'gender',
        'residence',
        'headline',
        'bio',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_photo',
        'terms',
    ]);
});
