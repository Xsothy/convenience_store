<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrdersItem;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ProgressBarColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductPerformance extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];
    
    protected function getTableHeading(): ?string
    {
        return Auth::user()->hasRole('artist') ? 'Your Product Sales Performance' : 'Product Performance by Artist';
    }

    public function table(Table $table): Table
    {
        // Build the query based on user role
        $query = Product::query();
        
        // Artists can only see their own products
        if (Auth::user()->hasRole('artist')) {
            $query->where('user_id', Auth::id());
        }
        
        return $table
            ->query($query)
            ->columns([
                ImageColumn::make('feature_image')
                    ->label('Image')
                    ->circular(),
                    
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                
                TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),
                    
                TextColumn::make('total_sales')
                    ->label('Units Sold')
                    ->getStateUsing(function (Product $product) {
                        return OrdersItem::whereHas('order', function (Builder $query) {
                            $query->where('status', 'completed');
                        })
                        ->where('product_id', $product->id)
                        ->sum('qty');
                    })
                    ->sortable(),
                    
                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->getStateUsing(function (Product $product) {
                        return OrdersItem::whereHas('order', function (Builder $query) {
                            $query->where('status', 'completed');
                        })
                        ->where('product_id', $product->id)
                        ->sum(DB::raw('price * qty'));
                    })
                    ->sortable(),
                
                TextColumn::make('is_activated')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->defaultSort('total_sales', 'desc')
            ->actions([
                \Filament\Tables\Actions\Action::make('edit')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->icon('heroicon-m-pencil-square')
            ])
            ->emptyStateHeading('No Products Yet')
            ->emptyStateDescription('Your products will appear here once you create them.')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }
} 