<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->logFillable()
            ->useLogName(self::getTableName())
            ->dontLogIfAttributesChangedOnly(['updated_at', 'created_at', 'remember_token'])
            ->dontSubmitEmptyLogs();
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    // override and log only update and delete
    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents)) {
            return collect(static::$recordEvents);
        }

        $events = collect([
            'updated',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }

        return $events;
    }
}
