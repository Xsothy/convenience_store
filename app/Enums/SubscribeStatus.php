<?php

namespace App\Enums;

enum SubscribeStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case CANCELLED = 'Cancelled';
    case PAUSED = 'Paused';
    case SCHEDULED = 'Scheduled';
    // case EXPIRED = 'expired';
    case PENDING = 'pending';
    case FAILED = 'failed';

    /**
     * Get the display label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::CANCELLED => 'Cancelled',
            self::PAUSED => 'Paused',
            self::SCHEDULED => 'Scheduled',
            self::PENDING => __('Pending'),
            self::FAILED => __('Failed'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::INACTIVE => 'red',
            self::CANCELLED => 'purple',
            self::PAUSED => 'amber',
            self::SCHEDULED => 'blue',
            self::PENDING => 'yellow',
            self::FAILED => 'red',
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
            self::ACTIVE->value => self::ACTIVE->label(),
            self::INACTIVE->value => self::INACTIVE->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
            self::PAUSED->value => self::PAUSED->label(),
            self::SCHEDULED->value => self::SCHEDULED->label(),
        ];
    }
}
