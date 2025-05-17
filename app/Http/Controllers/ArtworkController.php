<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Data\ProductData;
use App\Models\Category;
use App\Data\CategoryData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\LaravelData\PaginatedDataCollection;

class ArtworkController extends Controller
{
    /**
     * Display the artwork index page.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->input('category');
        $style = $request->input('style');
        $color = $request->input('color');
        $price = $request->input('price');
        $size = $request->input('size');
        
        // Start with a base query
        $query = Product::with('user', 'category');
        
        // Apply filters
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        
        // if ($style) {
        //     $query->where('style', $style);
        // }
        
        // if ($color) {
        //     $query->where('color', $color);
        // }
        
        if ($price) {
            $priceParts = explode('-', $price);
            $minPrice = $priceParts[0] ?? null;
            $maxPrice = $priceParts[1] ?? null;
            
            if ($minPrice !== null && $minPrice !== '') {
                $query->where('price', '>=', $minPrice);
            }
            
            if ($maxPrice !== null && $maxPrice !== '') {
                $query->where('price', '<=', $maxPrice);
            }
        }
        
        // if ($size) {
        //     $query->where('size', $size);
        // }
        
        // Get paginated products
        $products = $query->paginate(25)->withQueryString();
        
        // Get all categories for filters
        $categories = Category::all()->map(function($category) {
            return [
                'label' => $category->name,
                'value' => $category->slug,
            ];
        });
        
        // // Get unique style options
        // $styles = Product::distinct()->pluck('style')
        //     ->filter()
        //     ->map(function($style) {
        //         return [
        //             'label' => $style,
        //             'value' => $style,
        //         ];
        //     })->values();
        
        return Inertia::render('artwork/index', [
            'products' => ProductData::collect($products, PaginatedDataCollection::class),
            'filters' => [
                'category' => $category,
                'style' => $style,
                'color' => $color,
                'price' => $price,
                'size' => $size,
            ],
            'filterOptions' => [
                'categories' => $categories
            ],
        ]);
    }

    /**
     * Display the artwork detail page.
     *
     * @param  \App\Models\Product  $product
     * @return \Inertia\Response
     */
    public function show(Product $product)
    {
        // Eager load the user/artist with the product
        $product->load('user');

        return Inertia::render('artwork/show', [
            'product' => ProductData::from($product)
        ]);
    }
}
