<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.guest_checkout', true);
        $this->migrator->add('shop.enable_tax', true);
        $this->migrator->add('shop.default_tax_rate', '0');
        $this->migrator->add('shop.enable_coupons', true);
        $this->migrator->add('shop.enable_gift_cards', true);
        $this->migrator->add('shop.enable_referrals', false);
        $this->migrator->add('shop.referral_discount', '0');
        $this->migrator->add('shop.enable_reviews', true);
        $this->migrator->add('shop.auto_approve_reviews', false);
        $this->migrator->add('shop.low_stock_threshold', '5');
        $this->migrator->add('shop.out_of_stock_threshold', '0');
    }
};
