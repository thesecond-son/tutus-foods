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
        // Sidebar categories (exclude Christmas)
        $categories = Category::where('slug', '!=', 'shop-christmas')
            ->orderBy('name')
            ->get();

        /**
         * 🎯 SPECIAL CASE: CUPCAKES PAGE
         * /products/category/cupcakes
         */
        if ($slug === 'cupcakes') {

            $stackedCategories = Category::whereIn('slug', [
                    'cupcakes',
                    'loaf-cakes',
                    'floral-cupcakes',
                ])
                ->with(['products' => function ($query) use ($request) {
                    $query->where('active', true)
                        ->when($request->price, fn ($q) =>
                            $q->where('base_price', '<=', $request->price)
                        )
                        ->latest();
                }])
                ->get();

            $currentCategory = Category::where('slug', 'cupcakes')->first();

            return view('web.products.cupcakes', [
                'categories'        => $categories,
                'stackedCategories' => $stackedCategories,
                'currentCategory'   => null,
            ]);
        }

        /**
         * 🧁 NORMAL CATEGORY PAGE
         * /products/category/{slug}
         */
        if ($slug) {
            $currentCategory = Category::where('slug', $slug)->firstOrFail();

            $products = $currentCategory->products()
                ->where('active', true)
                ->when($request->price, fn ($q) =>
                    $q->where('base_price', '<=', $request->price)
                )
                ->paginate(12)
                ->withQueryString();

            // ✅ Get product from query string
            $productSlug = $request->query('product');

            $openProduct = null;

            if ($productSlug) {
                $openProduct = Product::where('slug', $productSlug)
                    ->whereHas('categories', function ($q) use ($currentCategory) {
                        $q->where('categories.id', $currentCategory->id);
                    })
                    ->where('active', true)
                    ->first();
            }

            return view('web.products.index', [
                'categories'      => $categories,
                'products'        => $products,
                'currentCategory' => $currentCategory,
                'openProduct'     => $openProduct,
            ]);
        }

        /**
         * 🛍 ALL PRODUCTS PAGE
         * /products
         */
        $products = Product::where('active', true)
            ->whereHas('categories', fn ($q) =>
                $q->where('slug', '!=', 'shop-christmas')
            )
            ->when($request->price, fn ($q) =>
                $q->where('base_price', '<=', $request->price)
            )
            ->paginate(12)
            ->withQueryString();

        return view('web.products.index', [
            'categories' => $categories,
            'products'   => $products,
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

    // public function category($slug)
    // {
    //     $categories = Category::orderBy('name')->get();

    //     $products = Product::whereHas('category', function ($query) use ($slug) {
    //             $query->where('slug', $slug);
    //         })
    //         ->where('active', true)
    //         ->get();

    //     $category = Category::where('slug', $slug)->firstOrFail();

    //     return view('web.products.index', compact('categories', 'products', 'category'));
    // }

}
