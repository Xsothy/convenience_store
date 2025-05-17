<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings
{
    /**
     * Social media links
     */
    public array $site_social = [];

    public static function group(): string
    {
        return 'social';
    }
}
