<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubscribePlanResource\Pages;
use App\Models\SubscribePlan;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscribePlanResource extends Resource
{
    protected static ?string $model = SubscribePlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Subscription Plans';

    public static function getNavigationGroup(): string
    {
        return 'Subscriptions & Payments';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->label('Monthly Price'),
                TextInput::make('annual_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->label('Annual Price'),
                RichEditor::make('description')
                    ->columnSpan(2),
                TagsInput::make('features_list')
                    ->helperText('Enter feature and press Enter/Return')
                    ->placeholder('Add a feature...')
                    ->columnSpan(2),
                TextInput::make('currency')
                    ->default('USD')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Enable or disable this subscription plan')
                    ->inline(false)
                    ->default(false),
                Toggle::make('is_popular')
                    ->label('Mark as Popular')
                    ->helperText('Highlight this plan in the pricing table')
                    ->inline(false)
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->label('Monthly Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('annual_price')
                    ->money('USD')
                    ->label('Annual Price')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_popular')
                    ->boolean()
                    ->label('Popular')
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('warning')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Plans')
                    ->trueLabel('Active Plans')
                    ->falseLabel('Inactive Plans'),
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Popular Status')
                    ->placeholder('All Plans')
                    ->trueLabel('Popular Plans')
                    ->falseLabel('Regular Plans'),
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
            'index' => Pages\ListSubscribePlans::route('/'),
            'create' => Pages\CreateSubscribePlan::route('/create'),
            'edit' => Pages\EditSubscribePlan::route('/{record}/edit'),
        ];
    }
}
