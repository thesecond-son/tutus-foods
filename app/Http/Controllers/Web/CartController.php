<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('web.cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Read quantity from request
        $quantity = max(1, (int) $request->input('quantity', 1));

        // Read options (size, color, etc.)
        $options = $request->input('options', []);

        // ✅ Enforce required variants server-side — this is the only place that
        // can't be bypassed by disabled JS, a front-end bug, or a direct POST.
        if (!empty($product->product_options)) {
            $missing = [];
            foreach ($product->product_options as $opt) {
                $optionName = $opt['name'] ?? null;
                if ($optionName && empty($options[$optionName])) {
                    $missing[] = $optionName;
                }
            }

            if (!empty($missing)) {
                $message = 'Please select a ' . implode(' and ', $missing) . ' before adding this item to your cart.';

                // ✅ Plain fetch() doesn't set X-Requested-With, so $request->ajax()
                // alone is unreliable. Also check isJson()/wantsJson() so a JSON
                // fetch() request always gets a real 422 back instead of silently
                // following a redirect and reporting a false "success".
                if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'missing_options' => $missing,
                    ], 422);
                }

                return back()->withErrors(['options' => $message]);
            }
        }

        // Generate a unique cart key: product ID + serialized options
        $key = $id;
        if (!empty($options)) {
            $key = $id . '_' . md5(json_encode($options));
        }

        // If the same product with same options exists, increment quantity
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'quantity' => $quantity,
                'price' => $product->base_price,
                'image' => $product->image_path,
                'options' => $options, // store options
                'pickup_only' => $product->pickup_only ?? false,
                'pre_order' => (bool) ($product->pre_order ?? false),
                'pre_order_expectation' => $product->pre_order_expectation ?? null,
                'weight' => isset($product->weight) ? (float) $product->weight : 0,
                'seasonal' => $product->seasonal ?? false,
                'season_start' => $product->season_start ?? null,
                'season_end' => $product->season_end ?? null,
            ];
        }

        session()->put('cart', $cart);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "{$product->name} added to cart!");
    }

    /**
     * Update quantity for a cart item
     */
    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = max(1, (int) $request->input('quantity', 1));
            session()->put('cart', $cart);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        // if ($request->ajax()) {
        //     return response()->json(['success' => true]);
        // }

        return back()->with('success', 'Item removed.');
    }

    public function clear(Request $request)
    {
        session()->forget('cart');

        // if ($request->ajax()) {
        //     return response('', 200);
        // }

        return back()->with('success', 'Cart cleared.');
    }
}