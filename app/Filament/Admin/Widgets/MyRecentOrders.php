<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyRecentOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];
    
    protected function getHeader(): ?string
    {
        return Auth::user()->hasRole('artist') ? 'Recent Orders With Your Products' : 'Recent Orders';
    }

    public function table(Table $table): Table
    {
        $artistId = Auth::id();

        return $table
            ->query(
                Order::query()
                    ->whereHas('ordersItems.product', function ($query) use ($artistId) {
                        $query->where('user_id', $artistId);
                    })
                    ->with(['user', 'ordersItems.product'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),
                    
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'warning',
                        'pending' => 'info',
                        'canceled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                    
                TextColumn::make('artistProducts')
                    ->label('Your Products')
                    ->formatStateUsing(function ($record) use ($artistId) {
                        $artistProducts = $record->ordersItems
                            ->filter(function ($item) use ($artistId) {
                                return $item->product && $item->product->user_id == $artistId;
                            })
                            ->map(function ($item) {
                                return $item->product->name . ' (x' . $item->qty . ')';
                            })
                            ->implode(', ');
                        
                        return $artistProducts;
                    }),
                    
                TextColumn::make('artistRevenue')
                    ->label('Your Revenue')
                    ->formatStateUsing(function ($record) use ($artistId) {
                        $revenue = $record->ordersItems
                            ->filter(function ($item) use ($artistId) {
                                return $item->product && $item->product->user_id == $artistId;
                            })
                            ->sum(function ($item) {
                                return $item->price * $item->qty;
                            });
                        
                        return '$' . number_format($revenue, 2);
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ]),
                    
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->emptyStateHeading('No Orders Yet')
            ->emptyStateDescription('When customers purchase your products, orders will appear here.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->defaultSort('created_at', 'desc');
    }
} 