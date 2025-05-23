<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GiftCardResource\Pages;
use App\Models\GiftCard;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Gift Cards';

    public static function getNavigationGroup(): ?string
    {
        return 'Promotions & Marketing';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_activated', true)->count();
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-ecommerce::messages.gift_card.title'); // TODO: Change the autogenerated stub
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-ecommerce::messages.gift_card.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.gift_card.single');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->default(trans('filament-ecommerce::messages.gift_card.columns.default'))
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->unique(ignoreRecord: true)
                    ->default(Str::random(6))
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.code'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('balance')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.balance'))
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('user_id')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.user_id'))
                    ->hint(trans('filament-ecommerce::messages.gift_card.columns.user_id_hint'))
                    ->options(User::query()->get()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Toggle::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.is_activated')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->badge()
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.balance'))
                    ->money(locale: 'en', currency: setting('site_currency'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.is_activated'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_expired')
                    ->label(trans('filament-ecommerce::messages.gift_card.columns.is_expired'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.gift_card.filters.is_activated')),
                Tables\Filters\TernaryFilter::make('is_expired')
                    ->label(trans('filament-ecommerce::messages.gift_card.filters.is_expired')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGiftCards::route('/'),
        ];
    }
}
