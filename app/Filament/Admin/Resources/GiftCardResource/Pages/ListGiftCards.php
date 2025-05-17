<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ListGiftCards extends ManageRecords
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($data, $record) {
                $record->currency = setting('site_currency');
                $record->save();
            }),
        ];
    }
}
