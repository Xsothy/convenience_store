<?php

namespace App\Filament\Admin\Resources;

use App\Enums\SubscribeStatus;
use App\Enums\SubscriptionPeriod;
use App\Filament\Admin\Resources\SubscribeResource\Pages;
use App\Models\Subscribe;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscribeResource extends Resource
{
    protected static ?string $model = Subscribe::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'User Subscriptions';

    public static function getNavigationGroup(): string
    {
        return 'Subscriptions & Payments';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Plan')
                    ->native(false),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('User')
                    ->native(false),

                Select::make('period')
                    ->options(SubscriptionPeriod::options())
                    ->required()
                    ->label('Billing Period')
                    ->native(false),

                Select::make('status')
                    ->options([
                        SubscribeStatus::ACTIVE->value => 'Active',
                        SubscribeStatus::CANCELLED->value => 'Cancelled',
                        SubscribeStatus::INACTIVE->value => 'Inactive',
                        SubscribeStatus::PENDING->value => 'Pending',
                        SubscribeStatus::FAILED->value => 'Failed',
                    ])
                    ->required()
                    ->label('Status')
                    ->native(false),

                DatePicker::make('end_at')
                    ->label('End Date')
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('User'),

                Tables\Columns\TextColumn::make('plan.name')
                    ->searchable()
                    ->sortable()
                    ->label('Plan'),

                Tables\Columns\TextColumn::make('period')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Monthly' => 'gray',
                        'Annually' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'cancelled' => 'warning',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('end_at')
                    ->date()
                    ->sortable()
                    ->label('End Date'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        SubscribeStatus::ACTIVE->value => 'Active',
                        SubscribeStatus::CANCELLED->value => 'Cancelled',
                        SubscribeStatus::INACTIVE->value => 'Inactive',
                        SubscribeStatus::PENDING->value => 'Pending',
                        SubscribeStatus::FAILED->value => 'Failed',
                    ]),

                Tables\Filters\SelectFilter::make('period')
                    ->options(SubscriptionPeriod::options()),

                Tables\Filters\SelectFilter::make('plan_id')
                    ->relationship('plan', 'name')
                    ->label('Plan')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('renew')
                    ->label('Renew')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function (Subscribe $record) {
                        $record->update([
                            'status' => SubscribeStatus::ACTIVE,
                            'end_at' => now()->addMonths($record->period->months()),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Subscribe $record) => $record->status !== SubscribeStatus::ACTIVE),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (Subscribe $record) {
                        $record->update([
                            'status' => SubscribeStatus::CANCELLED,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Subscribe $record) => $record->status === SubscribeStatus::ACTIVE),
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
            'index' => Pages\ListSubscribes::route('/'),
            'create' => Pages\CreateSubscribe::route('/create'),
            'edit' => Pages\EditSubscribe::route('/{record}/edit'),
        ];
    }
}
