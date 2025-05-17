<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('location.site_address', '123 Art Street, Creative City');
        $this->migrator->add('location.site_phone_code', '+1');
        $this->migrator->add('location.site_location', 'United States');
        $this->migrator->add('location.site_currency', 'USD');
        $this->migrator->add('location.site_language', 'en');
    }
};
