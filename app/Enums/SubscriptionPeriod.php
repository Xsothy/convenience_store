<?php

namespace App\Enums;

enum SubscriptionPeriod: string
{
    case MONTHLY = 'Monthly';
    case ANNUALLY = 'Annually';

    /**
     * Get the display label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Monthly',
            self::ANNUALLY => 'Annually',
        };
    }

    /**
     * Get all enum values as a simple array
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all available options as an array for select inputs
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::MONTHLY->value => self::MONTHLY->label(),
            self::ANNUALLY->value => self::ANNUALLY->label(),
        ];
    }

    /**
     * Get the period duration in days
     */
    public function days(): int
    {
        return match ($this) {
            self::MONTHLY => 30,
            self::ANNUALLY => 365,
        };
    }

    /**
     * Get the number of months in the period
     */
    public function months(): int
    {
        return match ($this) {
            self::MONTHLY => 1,
            self::ANNUALLY => 12,
        };
    }
}
