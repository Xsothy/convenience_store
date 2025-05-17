<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlySalesChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];
    
    public function getHeading(): ?string
    {
        return Auth::user()->hasRole('artist') ? 'Your Monthly Sales' : 'Monthly Sales';
    }
    
    protected function getData(): array
    {
        $artistId = Auth::id();
        $labels = [];
        $revenue = [];
        $unitsSold = [];
        
        // Get the last 5 months
        for ($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            // Get all orders for this month with products eagerly loaded
            $orders = Order::with(['ordersItems.product'])
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->get();
            
            // Filter to only orders containing this artist's products
            $artistOrders = $orders->filter(function ($order) use ($artistId) {
                if (!$order->ordersItems || !is_iterable($order->ordersItems)) {
                    return false;
                }
                
                foreach ($order->ordersItems as $item) {
                    if ($item->product && $item->product->user_id == $artistId) {
                        return true;
                    }
                }
                return false;
            });
            
            $monthlyRevenue = 0;
            $monthlyUnits = 0;
            
            // Calculate revenue and units for this month
            foreach ($artistOrders as $order) {
                if (!$order->ordersItems || !is_iterable($order->ordersItems)) {
                    continue;
                }
                
                foreach ($order->ordersItems as $item) {
                    if ($item->product && $item->product->user_id == $artistId) {
                        $monthlyRevenue += ($item->price ?? 0) * ($item->qty ?? 0);
                        $monthlyUnits += $item->qty ?? 0;
                    }
                }
            }
            
            $revenue[] = $monthlyRevenue;
            $unitsSold[] = $monthlyUnits;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $revenue,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Units Sold',
                    'data' => $unitsSold,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array 
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)'
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Units Sold'
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.3
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top'
                ]
            ]
        ];
    }
} 