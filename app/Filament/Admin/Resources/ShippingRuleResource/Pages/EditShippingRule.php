<?php

namespace App\Filament\Admin\Resources\ShippingRuleResource\Pages;

use App\Filament\Admin\Resources\ShippingRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingRule extends EditRecord
{
    protected static string $resource = ShippingRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
