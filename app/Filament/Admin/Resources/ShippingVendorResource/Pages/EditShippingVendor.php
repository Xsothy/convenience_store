<?php

namespace App\Filament\Admin\Resources\ShippingVendorResource\Pages;

use App\Filament\Admin\Resources\ShippingVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingVendor extends EditRecord
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
