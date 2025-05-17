<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SitesSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.site_name', '3x1');
        $this->migrator->add('sites.site_description', 'Creative Solutions');
        $this->migrator->add('sites.site_keywords', 'Graphics, Marketing, Programming');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Lor Sothy');
        $this->migrator->add('sites.site_address', 'Phnom Penh, Cambodia');
        $this->migrator->add('sites.site_email', 'info@3x1.io');
        $this->migrator->add('sites.site_phone', '+85590262305');
        $this->migrator->add('sites.site_phone_code', '+855');
        $this->migrator->add('sites.site_location', 'Cambodia');
        $this->migrator->add('sites.site_currency', 'USD');
        $this->migrator->add('sites.site_language', 'English');
        $this->migrator->add('sites.site_social', []);
    }

    public function down(): void
    {
        $this->migrator->delete('sites.site_name');
        $this->migrator->delete('sites.site_description');
        $this->migrator->delete('sites.site_keywords');
        $this->migrator->delete('sites.site_profile');
        $this->migrator->delete('sites.site_logo');
        $this->migrator->delete('sites.site_author');
        $this->migrator->delete('sites.site_address');
        $this->migrator->delete('sites.site_email');
        $this->migrator->delete('sites.site_phone');
        $this->migrator->delete('sites.site_phone_code');
        $this->migrator->delete('sites.site_location');
        $this->migrator->delete('sites.site_currency');
        $this->migrator->delete('sites.site_language');
        $this->migrator->delete('sites.site_social');
    }
}
