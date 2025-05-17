<?php

namespace App\Filament\Admin\Resources\ReferralCodeResource\Pages;

use App\Filament\Admin\Resources\ReferralCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReferralCode extends EditRecord
{
    protected static string $resource = ReferralCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
