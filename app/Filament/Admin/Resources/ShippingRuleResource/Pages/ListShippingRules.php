<?php

namespace App\Filament\Admin\Resources\ShippingRuleResource\Pages;

use App\Filament\Admin\Resources\ShippingRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingRules extends ListRecords
{
    protected static string $resource = ShippingRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
