<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\SubscribeStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrdersItem;
use App\Models\Subscribe;
use Filament\Widgets\StatsOverviewWidget as BaseStatsWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardOverview extends BaseStatsWidget
{
    protected static ?string $pollingInterval = '15s';
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];

    // Custom heading based on user role
    protected function getHeading(): ?string
    {
        return Auth::user()->hasRole('artist') ? 'Your Performance Overview' : 'Platform Overview';
    }

    protected function getStats(): array
    {
        // Get current month and previous month for comparisons
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $previousMonth = Carbon::now()->subMonth()->month;
        $previousYear = Carbon::now()->subMonth()->year;

        // If user is an artist, show only their stats
        if (Auth::user()->hasRole('artist')) {
            return $this->getArtistStats($currentMonth, $currentYear, $previousMonth, $previousYear);
        }

        // Otherwise, show admin stats
        return $this->getAdminStats($currentMonth, $currentYear, $previousMonth, $previousYear);
    }

    protected function getAdminStats(int $currentMonth, int $currentYear, int $previousMonth, int $previousYear): array
    {
        // PLATFORM REVENUE METRICS
        // Total revenue - all time
        $totalRevenue = Order::where('status', 'completed')->sum('total');

        // Current month revenue
        $currentMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');

        // Previous month revenue
        $previousMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->sum('total');

        // Calculate growth percentage for revenue
        $revenueGrowth = $previousMonthRevenue ?
            round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 2) : 0;

        // PLATFORM ORDER METRICS
        // Current month order count
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Previous month order count
        $previousMonthOrders = Order::whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->count();

        // Calculate order growth percentage
        $orderGrowth = $previousMonthOrders ?
            round((($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100, 2) : 0;

//        // ARTIST METRICS
//        // Active artists
//        $activeArtists = User::role('artist')->count();
//        $newArtists = User::role('artist')->where('created_at', '>=', now()->subDays(30))->count();
//
//        // Top performing artist
//        $topArtist = User::whereHas('roles', function ($query) {
//                $query->where('name', 'artist');
//            })
//            ->withSum(['products as total_revenue' => function ($query) {
//                $query->join('orders_items', 'products.id', '=', 'orders_items.product_id')
//                      ->join('orders', 'orders_items.order_id', '=', 'orders.id')
//                      ->where('orders.status', 'completed');
//            }], DB::raw('orders_items.price * orders_items.qty'))
//            ->orderByDesc('total_revenue')
//            ->first();
        // PRODUCT METRICS
        // Total active products
        $activeProducts = Product::where('is_activated', true)->count();
        $totalProducts = Product::count();
        $activeProductPercentage = $totalProducts ? round(($activeProducts / $totalProducts) * 100) : 0;

        $activeSubscribers = Subscribe::where('status', SubscribeStatus::ACTIVE->value)->count();
        $totalUsers= User::count();
        $subscriberPercentage = $totalUsers ? round(($activeSubscribers / $totalUsers) * 100) : 0;

        return [
            // Revenue Card
            Stat::make('Platform Revenue', '$' . number_format($totalRevenue, 2))
                ->description($revenueGrowth >= 0 ? $revenueGrowth . '% increase' : abs($revenueGrowth) . '% decrease')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger')
                ->chart([
                    $previousMonthRevenue / 100,
                    $currentMonthRevenue / 100
                ]),

            // Orders Card
            Stat::make('Total Orders', number_format(Order::count()))
                ->description($orderGrowth >= 0 ? $orderGrowth . '% increase' : abs($orderGrowth) . '% decrease')
                ->descriptionIcon($orderGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($orderGrowth >= 0 ? 'success' : 'danger')
                ->chart([
                    $previousMonthOrders,
                    $currentMonthOrders
                ]),

//            // Artists Card
//            Stat::make('Active Artists', number_format($activeArtists))
//                ->description($newArtists . ' new in last 30 days')
//                ->descriptionIcon('heroicon-m-user-circle')
//                ->color('primary'),

//            // Top Artist Card
//            Stat::make('Top Artist', $topArtistName)
//                ->description('$' . number_format($topArtistRevenue, 2) . ' in sales')
//                ->descriptionIcon('heroicon-m-trophy')
//                ->color('warning'),

            // Products Card
            Stat::make('Active Products', number_format($activeProducts))
                ->description($activeProductPercentage . '% of all products')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

//            // Low Stock Card
//            Stat::make('Active Subscriber', number_format($activeSubscribers))
//                ->description($subscriberPercentage . '% of all users')
//                ->descriptionIcon('heroicon-o-credit-card')
//                ->color($activeSubscribers > 0 ? 'success' : 'danger'),
        ];
    }

    protected function getArtistStats(int $currentMonth, int $currentYear, int $previousMonth, int $previousYear): array
    {
        $artistId = Auth::id();

        // ARTIST REVENUE METRICS
        // Calculate artist's revenue
        $totalRevenue = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum(DB::raw('price * qty'));

        // Calculate current month revenue
        $currentMonthRevenue = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) use ($currentMonth, $currentYear) {
                $query->where('status', 'completed')
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            })
            ->sum(DB::raw('price * qty'));

        // Calculate previous month revenue
        $previousMonthRevenue = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) use ($previousMonth, $previousYear) {
                $query->where('status', 'completed')
                    ->whereMonth('created_at', $previousMonth)
                    ->whereYear('created_at', $previousYear);
            })
            ->sum(DB::raw('price * qty'));

        // Calculate revenue growth percentage
        $revenueGrowth = $previousMonthRevenue ?
            round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 2) : 0;

        // ARTIST PRODUCT METRICS
        // Get products by the artist
        $productsCount = Product::where('user_id', $artistId)->count();

        // Get active products by the artist
        $activeProducts = Product::where('user_id', $artistId)
            ->where('is_activated', true)
            ->count();

        // Calculate percentage of active products
        $activePercentage = $productsCount ? round(($activeProducts / $productsCount) * 100) : 0;

        // SALES METRICS
        // Get total units sold
        $unitsSold = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('qty');

        // Get current month units sold
        $currentMonthUnitsSold = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) use ($currentMonth, $currentYear) {
                $query->where('status', 'completed')
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            })
            ->sum('qty');

        // Get previous month units sold
        $previousMonthUnitsSold = OrdersItem::whereHas('product', function ($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->whereHas('order', function ($query) use ($previousMonth, $previousYear) {
                $query->where('status', 'completed')
                    ->whereMonth('created_at', $previousMonth)
                    ->whereYear('created_at', $previousYear);
            })
            ->sum('qty');

        // Calculate units sold growth percentage
        $unitsSoldGrowth = $previousMonthUnitsSold ?
            round((($currentMonthUnitsSold - $previousMonthUnitsSold) / $previousMonthUnitsSold) * 100, 2) : 0;

        // INVENTORY METRICS
        // Get low stock products
        $lowStockProducts = Product::where('user_id', $artistId)
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->where('has_unlimited_stock', false)
            ->count();

        // Get out of stock products
        $outOfStockProducts = Product::where('user_id', $artistId)
            ->where('is_in_stock', false)
            ->where('has_unlimited_stock', false)
            ->count();

        // Calculate inventory issues percentage
        $inventoryIssuesPercentage = $productsCount ?
            round((($lowStockProducts + $outOfStockProducts) / $productsCount) * 100) : 0;

        // Get top selling product
        $topProduct = Product::where('user_id', $artistId)
            ->withSum(['ordersItems as total_sales' => function ($query) {
                $query->whereHas('order', function ($innerQuery) {
                    $innerQuery->where('status', 'completed');
                });
            }], 'qty')
            ->orderByDesc('total_sales')
            ->first();

        $topProductName = $topProduct?->name ?? 'None';
        $topProductSales = $topProduct?->total_sales ?? 0;

        // Get newest (latest created) product
        $newestProduct = Product::where('user_id', $artistId)
            ->orderByDesc('created_at')
            ->first();

        $newestProductName = $newestProduct?->name ?? 'None';
        $newestProductDaysAgo = $newestProduct ?
            $newestProduct->created_at->diffInDays(now()) : 0;

        return [
            // Revenue Card
            Stat::make('Your Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description($revenueGrowth >= 0 ? $revenueGrowth . '% increase' : abs($revenueGrowth) . '% decrease')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger')
                ->chart([
                    $previousMonthRevenue / 100,
                    $currentMonthRevenue / 100
                ]),

            // Units Sold Card
            Stat::make('Total Units Sold', number_format($unitsSold))
                ->description($unitsSoldGrowth >= 0 ? $unitsSoldGrowth . '% increase' : abs($unitsSoldGrowth) . '% decrease')
                ->descriptionIcon($unitsSoldGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($unitsSoldGrowth >= 0 ? 'success' : 'danger')
                ->chart([
                    $previousMonthUnitsSold,
                    $currentMonthUnitsSold
                ]),

            // Active Products Card
            Stat::make('Active Products', number_format($activeProducts))
                ->description($activePercentage . '% of your products')
                ->descriptionIcon($activePercentage >= 75 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->color($activePercentage >= 75 ? 'success' : 'warning'),

            // Top Product Card
            Stat::make('Top Selling Product', $topProductName)
                ->description($topProductSales . ' units sold')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('primary'),

            // // Inventory Issues Card
            // Stat::make('Inventory Issues', number_format($lowStockProducts + $outOfStockProducts))
            //     ->description($inventoryIssuesPercentage . '% of your products')
            //     ->descriptionIcon('heroicon-m-exclamation-triangle')
            //     ->color(($lowStockProducts + $outOfStockProducts) > 0 ? 'danger' : 'success'),

            // // Newest Product Card
            // Stat::make('Newest Product', $newestProductName)
            //     ->description($newestProductDaysAgo > 0 ? 'Added ' . $newestProductDaysAgo . ' days ago' : 'Added today')
            //     ->descriptionIcon('heroicon-m-sparkles')
            //     ->color('info'),
        ];
    }
}
