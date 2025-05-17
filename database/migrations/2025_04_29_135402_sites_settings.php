<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
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

        $this->migrator->add('sites.site_name', '3x1');
        $this->migrator->add('sites.site_description', 'Creative Solutions');
        $this->migrator->add('sites.site_keywords', 'Art, Weeding, Poster');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Lor Sothy');
        $this->migrator->add('sites.site_email', 'info@3x1.io');
        $this->migrator->add('sites.site_phone', '+85590262305');
        $this->migrator->add('sites.site_social', []);
    }
};
