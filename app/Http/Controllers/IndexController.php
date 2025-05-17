<?php

namespace App\Http\Controllers;

use App\Data\CategoryData;
use App\Data\ProductData;
use App\Models\Category;
use App\Models\Product;
use App\Settings\ContentSettings;
use Inertia\Inertia;

class IndexController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('home', [
            'featuredProducts' => ProductData::collect(
                Product::query()->where('is_trend', true)->limit(10)->get()
            ),
            'discountedProducts' => ProductData::collect(
                Product::query()->whereDate('discount_to', '>', now())->limit(10)->get()
            ),
            'categories' => CategoryData::collect(
                Category::query()->where('is_active', true)->get()
            )->map(fn ($category) => $category->include('products')),
            'contentSettings' => app(ContentSettings::class),
        ]);
    }

    /**
     * Display the dashboard page.
     *
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render('dashboard');
    }
}
