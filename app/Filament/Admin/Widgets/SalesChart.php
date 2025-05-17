<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Trends';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];
    
    protected function getData(): array
    {
        // Get last 30 days sales data
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Get all completed orders in the period
        $orders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        // Group orders by date
        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d');
        });
        
        // Create date range for the last 30 days
        $dateRange = collect();
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i);
            $dateRange->push($date->format('Y-m-d'));
        }
        
        // Create datasets
        $sales = [];
        $orderCounts = [];
        $labels = [];
        
        foreach ($dateRange as $date) {
            $labels[] = Carbon::parse($date)->format('M d');
            
            // Get orders for this date
            $dailyOrders = $groupedOrders->get($date, collect([]));
            
            // Calculate daily sales and order count
            $dailySales = $dailyOrders->sum('total');
            $dailyOrderCount = $dailyOrders->count();
            
            $sales[] = $dailySales;
            $orderCounts[] = $dailyOrderCount;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Sales ($)',
                    'data' => $sales,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Orders',
                    'data' => $orderCounts,
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
                        'text' => 'Revenue ($)',
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
                        'text' => 'Number of Orders',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('admin');
    }
} 