<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShippingResource\Pages;
use App\Models\Shipping;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Shipping Methods';

    public static function getNavigationGroup(): ?string
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
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Toggle::make('status')
                    ->label('Active')
                    ->inline(false)
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListShippings::route('/'),
            'create' => Pages\CreateShipping::route('/create'),
            'edit' => Pages\EditShipping::route('/{record}/edit'),
        ];
    }
}
