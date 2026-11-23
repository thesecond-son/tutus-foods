<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    HomeController,
    BlogController,
    ProductController,
    CartController,
    CheckoutController,
    CorporateRequestController,
    PagesController,
};
use App\Models\Order;
use App\Mail\CustomerOrderMail;
use Illuminate\Support\Facades\Mail;


use Filament\Facades\Filament;

// Debug routes — local environment only. /login-test logs the visitor in as
// the first user, so it must never be registered in production.
if (app()->environment('local')) {
    Route::get('/admin-test', function () {
        return "works";
    });

    Route::get('/test-auth', function() {
        return auth()->check() ? auth()->user() : 'Not logged in';
    });

    Route::get('/login-test', function() {
        $user = \App\Models\User::first();
        auth()->login($user);
        return 'Logged in as ' . $user->email;
    });

    Route::get('/test-mail', function () {
        $order = Order::first(); // pick any order
        if (!$order) {
            return 'No orders found!';
        }

        Mail::to('romithesecond@gmail.com')->send(new CustomerOrderMail($order));
        return 'Mail sent!';
    });
}


// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/refund-policy', [PagesController::class, 'refund'])->name('refund-policy');
Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('privacy-policy');
Route::get('/contact', [PagesController::class, 'contact'])->name('contact');

// Route::get('/privacy-policy', function () {
//     return 'Privacy Policy route is working';
// });

// Route::get('/contact', function () {
//     return 'Contact route is working';
// });

Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');

//Public Products Pages
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/category/{slug}', [ProductController::class, 'index'])->name('products.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Private Christmas shop
Route::get('/products/shop-christmas', [HomeController::class, 'shopChristmas'])->name('shop-christmas');

Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('/blogs/{blog}/comments', [BlogController::class, 'storeComment'])->name('blog.comments.store');
});

// Shopping Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    // View Cart Page
    Route::get('/', [CartController::class, 'index'])->name('index');

    // Add Item to Cart
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');

    // Update Quantity
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');

    // Remove Item
    Route::delete('/remove/{id}', function ($id) {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        session()->save();
        return response()->json(['success' => true]);
    })->name('remove');

    // Clear Entire Cart
    Route::post('/clear', function (\Illuminate\Http\Request $request) {
        session()->forget('cart');
        session()->save();

        // The cart sidebar calls this via fetch (expects JSON); the Clear Cart
        // button on the cart page is a normal form post and needs a redirect.
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    })->name('clear');
    
    // ✅ Get total cart count (session-based)
    Route::get('/count', function () {
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    })->name('cart.count');    

    // ✅ Return cart summary HTML partial for sidebar
    Route::get('/summary', function () {
        $cart = session('cart', []);
        return view('web.partials.cart-summary', compact('cart'))->render();
    });
});

// Checkout flow
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'createOrderAfterPayment']);
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');;
Route::post('/checkout/paypal/create', [CheckoutController::class, 'createPayPalOrder'])->name('checkout.paypal.create')->middleware('throttle:10,1');
Route::post('/checkout/paypal/capture', [CheckoutController::class, 'capturePayPalOrder'])->name('checkout.paypal.capture')->middleware('throttle:10,1');
Route::post('/checkout/square/create', [CheckoutController::class, 'createSquarePayment'])->name('checkout.square.create')->middleware('throttle:10,1');;
Route::get('/square/config', [CheckoutController::class, 'getSquareConfig']);

Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Corporate requests
Route::get('/corporate', [CorporateRequestController::class, 'index'])->name('corporate.index');
Route::post('/corporate', [CorporateRequestController::class, 'submit'])->name('corporate.submit');






require __DIR__.'/auth.php';
