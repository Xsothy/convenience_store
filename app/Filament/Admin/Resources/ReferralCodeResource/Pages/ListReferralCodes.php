<?php

namespace App\Filament\Admin\Resources\ReferralCodeResource\Pages;

use App\Filament\Admin\Resources\ReferralCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ListReferralCodes extends ManageRecords
{
    protected static string $resource = ReferralCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
