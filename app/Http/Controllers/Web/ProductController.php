<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        // Exclude Christmas category
        $categories = Category::where('slug', '!=', 'shop-christmas')
            ->orderBy('name')
            ->get();
    
        // Determine current category (if any)
        $currentCategory = $slug
            ? Category::where('slug', $slug)->firstOrFail()
            : null;
    
        // Base product query
        $query = Product::where('active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', '!=', 'shop-christmas');
            });
    
        // Filter by category if present
        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }
    
        // Filter by price if provided
        if ($request->has('price')) {
            $query->where('base_price', '<=', $request->price);
        }
    
        // Get products with pagination
        $products = $query->paginate(12)->withQueryString();
    
        return view('web.products.index', [
            'categories' => $categories,
            'products' => $products,
            'currentCategory' => $currentCategory,
        ]);
    }    

    public function show($slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();

    if (request()->ajax()) {
        // If requested via fetch, return only the product partial
        return view('web.partials.product-modal', compact('product'));
    }

    // If visited directly, show the normal page
    return view('web.products.show', compact('product'));
}

    public function category($slug)
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('active', true)
            ->get();

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('web.products.index', compact('categories', 'products', 'category'));
    }
}
