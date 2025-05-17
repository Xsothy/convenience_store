<?php

namespace App\Filament\Admin\Resources\ShippingResource\Pages;

use App\Filament\Admin\Resources\ShippingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippings extends ListRecords
{
    protected static string $resource = ShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
