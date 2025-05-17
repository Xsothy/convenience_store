<?php

namespace App\Http\Controllers;

use App\Data\CategoryData;
use App\Models\Category;
use Inertia\Inertia;

class ThemeController extends Controller
{
    /**
     * Display the theme detail page.
     *
     * @return \Inertia\Response
     */
    public function show(Category $category)
    {
        return Inertia::render('theme/show', [
            'category' => CategoryData::from($category)->include('products'),
        ]);
    }
}
