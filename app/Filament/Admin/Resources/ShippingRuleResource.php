<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShippingRuleResource\Pages;
use App\Models\Shipping;
use App\Models\ShippingRule;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingRuleResource extends Resource
{
    protected static ?string $model = ShippingRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Shipping Rules';

    public static function getNavigationGroup(): string
    {
        return 'Shipping & Delivery';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('shipping_id')
                    ->options(fn () => Shipping::query()->get()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Shipping')
                    ->native(false),
                Forms\Components\TextInput::make('min_order_amount')
                    ->label('Min Order')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_days')
                    ->label('Delivery Days')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->mask(RawJs::make('$money($input)'))
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->inline(false)
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shipping.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_order_amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_days')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->getStateUsing(fn ($record) => $record->status ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingRules::route('/'),
            'create' => Pages\CreateShippingRule::route('/create'),
            'edit' => Pages\EditShippingRule::route('/{record}/edit'),
        ];
    }
}
