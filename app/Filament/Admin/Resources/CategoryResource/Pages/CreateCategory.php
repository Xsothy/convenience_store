<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\LocaleSwitcher;

class CreateCategory extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CategoryResource::class;

    public ?string $activeLocale = null;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'km'];
    }
    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
