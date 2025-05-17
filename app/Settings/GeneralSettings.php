<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
class GeneralSettings extends Settings
{
    public string $site_name;

    public string $site_description;

    public string $site_email;

    public string $site_phone;

    public string $site_author;

    public string $site_keywords;

    public ?string $site_logo = null;

    public ?string $site_profile = null;

    public static function group(): string
    {
        return 'general';
    }
}
