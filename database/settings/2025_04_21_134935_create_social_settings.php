<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.site_social', [
            [
                'vendor' => 'facebook',
                'link' => 'https://facebook.com/artworkshop',
            ],
            [
                'vendor' => 'instagram',
                'link' => 'https://instagram.com/artworkshop',
            ],
            [
                'vendor' => 'twitter',
                'link' => 'https://twitter.com/artworkshop',
            ],
        ]);
    }
};
