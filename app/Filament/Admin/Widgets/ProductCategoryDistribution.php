<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductCategoryDistribution extends ChartWidget
{
    protected static ?string $heading = 'Product Category Distribution';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];
    
    protected function getData(): array
    {
        // Get all categories with product count
        $categories = Category::withCount('products')->get();
        
        // If there are no products, return default data
        if ($categories->sum('products_count') === 0) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e0e0e0'],
                    ],
                ],
                'labels' => ['No Products'],
            ];
        }
        
        // Prepare data
        $data = [];
        $labels = [];
        $colors = [
            '#4f46e5', // Indigo
            '#10b981', // Emerald
            '#f97316', // Orange
            '#8b5cf6', // Violet
            '#06b6d4', // Cyan
            '#ec4899', // Pink
            '#84cc16', // Lime
            '#14b8a6', // Teal
            '#ef4444', // Red
            '#6366f1', // Indigo
            '#f59e0b', // Amber
            '#64748b', // Slate
        ];
        
        // Process each category
        $categoryIndex = 0;
        foreach ($categories as $category) {
            // Skip categories with no products
            if ($category->products_count === 0) {
                continue;
            }
            
            $labels[] = $category->name;
            $data[] = $category->products_count;
            
            $categoryIndex++;
        }
        
        // Ensure we have enough colors by cycling through the array
        $backgroundColor = array_slice($colors, 0, count($data));
        
        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.raw + " products";
                        }',
                    ],
                ],
            ],
            'cutout' => '60%',
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('admin');
    }
} 