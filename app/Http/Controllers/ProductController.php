<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'artist'])->orderBy('created_at', 'desc');

        // Apply category filter if provided
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Apply price range filter if provided
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply artist filter if provided
        if ($request->has('artist')) {
            $query->whereHas('artist', function ($q) use ($request) {
                $q->where('slug', $request->artist);
            });
        }

        // Apply sort order
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        $products = $query->paginate(12)->withQueryString();

        return Inertia::render('products/index', [
            'products' => $products,
            'filters' => $request->only(['category', 'min_price', 'max_price', 'artist', 'sort']),
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'artist'])
            ->firstOrFail();

        // Get related products based on the same category
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['category', 'artist'])
            ->take(4)
            ->get();

        // If not enough related products in the same category, add some from the same artist
        if ($relatedProducts->count() < 4 && $product->artist_id) {
            $additionalProducts = Product::where('id', '!=', $product->id)
                ->where('artist_id', $product->artist_id)
                ->whereNotIn('id', $relatedProducts->pluck('id')->toArray())
                ->with(['category', 'artist'])
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($additionalProducts);
        }

        // If still not enough, add some random products
        if ($relatedProducts->count() < 4) {
            $randomProducts = Product::where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id')->toArray())
                ->with(['category', 'artist'])
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($randomProducts);
        }

        return Inertia::render('products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
