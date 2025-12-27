<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use App\Models\HomepageSetting;

class HomeController extends Controller
{
    public function index()
    {

     

        return view('web.home');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        NewsletterSubscriber::create([
            'email' => $request->email,
            'subscribed' => $request->has('subscribe'),
        ]);

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    public function shopChristmas()
    {
        $category = \App\Models\Category::where('slug', 'shop-christmas')->first();
    
        if ($category) {
            // Eloquent query builder with pagination
            $products = $category->products()->paginate(12);
        } else {
            // Empty paginator to avoid links() errors
            $products = \App\Models\Product::whereNull('id')->paginate(12);
        }
    
        return view('web.products.shop-christmas', [
            'products' => $products,
            'currentCategory' => $category,
            'categories' => \App\Models\Category::all(),
        ]);


    }
    

}
