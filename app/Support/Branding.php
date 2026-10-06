<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * The showroom's identity on screen: name, logo and showroom photo from the settings.
 * URLs are relative to the current host, so they work however the app is reached.
 */
class Branding
{
    public function __construct(private readonly Settings $settings) {}

    public function name(): string
    {
        return (string) ($this->settings->get('company.name') ?: config('app.name'));
    }

    public function phone(): ?string
    {
        return $this->settings->get('company.phone') ?: null;
    }

    public function address(): ?string
    {
        return $this->settings->get('company.address') ?: null;
    }

    public function logoUrl(): ?string
    {
        return $this->url('company.logo');
    }

    /** The showroom photo used beside the login form and on the dashboard banner. */
    public function coverUrl(): ?string
    {
        return $this->url('company.cover');
    }

    /** First letter of the name, for the emblem shown when no logo was uploaded. */
    public function initial(): string
    {
        $words = preg_split('/\s+/u', trim(preg_replace('/^(معرض|شركة)\s+/u', '', $this->name()) ?? '')) ?: [];

        $word = $words[0] ?? $this->name();
        if (mb_strlen($word) > 3 && str_starts_with($word, 'ال')) {
            $word = mb_substr($word, 2); // skip the Arabic article: النجمة → ن
        }

        return mb_substr($word, 0, 1) ?: 'م';
    }

    private function url(string $key): ?string
    {
        $path = $this->settings->get($key);

        return $path && Storage::disk('public')->exists($path) ? '/storage/'.ltrim($path, '/') : null;
    }
}
