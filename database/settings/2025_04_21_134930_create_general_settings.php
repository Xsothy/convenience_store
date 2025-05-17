<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Artwork Shop');
        $this->migrator->add('general.site_description', 'Your one-stop shop for artwork and creative supplies');
        $this->migrator->add('general.site_email', 'info@artworkshop.com');
        $this->migrator->add('general.site_phone', '+1234567890');
        $this->migrator->add('general.site_author', 'Artwork Shop Team');
        $this->migrator->add('general.site_keywords', 'artwork, shop, creative, supplies, art');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_profile', null);
    }
};
