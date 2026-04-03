<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HtbProfileSyncService
{
    /**
     * @return array<string, mixed>|null
     */
    public function sync(User $user): ?array
    {
        if (! $user->htb_profile_url) {
            return null;
        }

        $response = Http::timeout(15)
            ->retry(1, 500)
            ->get($user->htb_profile_url);

        if (! $response->successful()) {
            return null;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($response->body())) ?? '');

        if ($text === '') {
            return null;
        }

        $username = $this->match('/@([A-Za-z0-9_.-]+)/', $text);
        $summary = $this->extractSection($text, 'Summary', [
            'Certifications',
            'Achievements',
            'Events Certificates',
            'Socials',
            'Top Interests',
            'Skills',
            'Toolstack',
            'Languages',
        ]);
        $certifications = $this->extractSection($text, 'Certifications', [
            'Achievements',
            'Events Certificates',
            'Socials',
            'Top Interests',
            'Skills',
            'Toolstack',
            'Languages',
        ]);
        $achievements = $this->extractSection($text, 'Achievements', [
            'Events Certificates',
            'Socials',
            'Top Interests',
            'Skills',
            'Toolstack',
            'Languages',
        ]);
        $skills = $this->extractSection($text, 'Skills', [
            'Toolstack',
            'Languages',
        ]);
        $toolstack = $this->extractSection($text, 'Toolstack', [
            'Languages',
        ]);

        $profileData = [
            'username' => $username,
            'summary' => $summary,
            'certifications' => $certifications,
            'achievements' => $achievements,
            'skills' => $skills,
            'toolstack' => $toolstack,
            'profile_completion' => collect([$summary, $certifications, $achievements, $skills, $toolstack])
                ->filter(fn (?string $value) => $value && ! Str::contains(Str::lower($value), ['not added', 'no achievements']))
                ->count() * 20,
        ];

        $user->forceFill([
            'htb_username' => $username,
            'htb_profile_data' => $profileData,
            'htb_last_synced_at' => now(),
        ])->save();

        return $profileData;
    }

    protected function match(string $pattern, string $subject): ?string
    {
        preg_match($pattern, $subject, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param  array<int, string>  $nextHeadings
     */
    protected function extractSection(string $text, string $heading, array $nextHeadings): ?string
    {
        $escapedHeading = preg_quote($heading, '/');
        $escapedNext = implode('|', array_map(static fn (string $value) => preg_quote($value, '/'), $nextHeadings));

        preg_match('/'.$escapedHeading.'\s*(.*?)\s*(?:'.$escapedNext.'|$)/i', $text, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
