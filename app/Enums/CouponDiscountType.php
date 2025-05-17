<?php

namespace App\Enums;

enum CouponDiscountType: string
{
    case PERCENTAGE = 'percentage_coupon';
    case DISCOUNT = 'discount_coupon';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => trans('filament-ecommerce::messages.coupons.columns.percentage_coupon'),
            self::DISCOUNT => trans('filament-ecommerce::messages.coupons.columns.discount_coupon'),
        };
    }

    public function apply(float $amount): float
    {
        return match ($this) {
            self::PERCENTAGE => $amount * $this->value / 100,
            self::DISCOUNT => $amount - $this->value,
        };
    }
    public static function options(): array
    {
        return [
            self::PERCENTAGE->value => self::PERCENTAGE->label(),
            self::DISCOUNT->value => self::DISCOUNT->label(),
        ];
    }

    public function icon(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'heroicon-o-receipt-percent',
            self::DISCOUNT => 'heroicon-o-receipt-refund',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'primary',
            self::DISCOUNT => 'info',
        };
    }
}

