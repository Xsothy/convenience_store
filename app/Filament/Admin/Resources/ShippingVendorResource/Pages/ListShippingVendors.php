<?php

namespace App\Filament\Admin\Resources\ShippingVendorResource\Pages;

use App\Filament\Admin\Resources\ShippingVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ListShippingVendors extends ManageRecords
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
