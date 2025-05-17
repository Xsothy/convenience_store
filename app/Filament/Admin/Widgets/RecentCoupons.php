<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Coupon;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RecentCoupons extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];
    protected static ?string $heading = 'Active Coupons';
    
    protected static ?int $itemsPerPage = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Coupon::query()
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('expiry_date')
                              ->orWhere('expiry_date', '>=', Carbon::now());
                    })
                    ->latest()
            )
            ->columns([
                TextColumn::make('code')
                    ->label('Coupon Code')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('Coupon code copied')
                    ->copyMessageDuration(1500),
                    
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'warning',
                        'fixed' => 'success',
                        default => 'gray',
                    }),
                    
                TextColumn::make('amount')
                    ->label('Value')
                    ->formatStateUsing(function (Coupon $record) {
                        return $record->type === 'percentage' 
                            ? $record->amount . '%' 
                            : '$' . number_format($record->amount, 2);
                    }),
                    
                TextColumn::make('expiry_date')
                    ->label('Expires')
                    ->date()
                    ->placeholder('No Expiry'),
                    
                TextColumn::make('usage_limit')
                    ->label('Usage')
                    ->formatStateUsing(function (Coupon $record) {
                        $usedCount = $record->orders()->count();
                        $limit = $record->usage_limit ?? '∞';
                        return "{$usedCount} / {$limit}";
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn (Coupon $record): string => route('filament.admin.resources.coupons.edit', $record))
                    ->icon('heroicon-m-pencil'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed Amount',
                    ]),
                    
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('is_active', true)),
                    
                Tables\Filters\Filter::make('expiring_soon')
                    ->query(fn ($query) => $query
                        ->whereNotNull('expiry_date')
                        ->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(7)])
                    ),
            ])
            ->emptyStateHeading('No Active Coupons')
            ->emptyStateDescription('You can create new coupons to offer discounts to your customers.')
            ->emptyStateIcon('heroicon-o-receipt-percent')
            ->defaultSort('created_at', 'desc');
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('admin');
    }
} 