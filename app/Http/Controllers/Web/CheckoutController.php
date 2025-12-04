<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryOption;
use App\Models\PickupLocation;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Square\SquareClient;
use Square\Environments;
use Square\Exceptions\ApiException;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use App\Mail\AdminOrderMail;
use App\Mail\CustomerOrderMail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $deliveryOptions = DeliveryOption::all();
        $pickupLocations = PickupLocation::all();

        return view('web.checkout.index', compact('cart', 'deliveryOptions', 'pickupLocations'));
    }

    // --- Order creation after payment ---
    public function createOrderAfterPayment(Request $request, $paymentMethod, $paymentStatus, $paymentReference = null)
    {
        $cart = session()->get('cart', []);
        if (!$cart) {
            return response()->json(['status' => 'failed', 'message' => 'Cart is empty']);
        }

        $request->validate([
            'customer_email' => 'required|email',
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
            'customer_phone' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_option_id' => 'nullable|exists:delivery_options,id',
            'delivery_address' => 'nullable|string|max:500',
            'pickup_location_id' => 'nullable|exists:pickup_locations,id',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable|string',
        ]);

        $totalAmount = $this->calculateTotal($cart, $request);

        return DB::transaction(function() use ($request, $cart, $totalAmount, $paymentMethod, $paymentStatus, $paymentReference) {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_email' => $request->customer_email,
                'customer_first_name' => $request->customer_first_name,
                'customer_last_name' => $request->customer_last_name,
                'customer_phone' => $request->customer_phone,
                'customer_instagram' => $request->customer_instagram,
                'order_notes' => $request->order_notes,
                'delivery_type' => $request->delivery_type,
                'delivery_option_id' => $request->delivery_option_id,
                'delivery_address' => $request->delivery_address,
                'pickup_location_id' => $request->pickup_location_id,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'delivery_pickup_status' => false,
            ]);
    
            // Prepare multiple order items
            $orderItems = [];
            foreach ($cart as $productId => $details) {
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'unit_price' => $details['price'],
                    'total_price' => $details['price'] * $details['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            OrderItem::insert($orderItems);
    
            // Clear cart
            session()->forget('cart');

            // REGISTER EMAILS HERE (inside transaction)
    DB::afterCommit(function () use ($order) {
        Mail::to($order->customer_email)->send(new CustomerOrderMail($order));
        Mail::to('info@tutuscupcakes.com')->send(new AdminOrderMail($order));
        // \Log::info('Email sent AFTER COMMIT for order ' . $order->order_number);
    });
    
            return $order;
        });
    
}


// --- PayPal ---
public function createPayPalOrder(Request $request)
{
    try {

        $total = floatval($request->total ?? 0);

        // Temporary log
        if ($total <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'Total must be greater than zero.',
            ], 422);
        }
        

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        // 1. Generate Access Token
        $provider->getAccessToken();

        // 2. Create actual ORDER
        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "GBP",
                    "value" => $total
                ]
            ]]
        ]);

        \Log::info('PayPal Order Created', $order);

        // If PayPal returns an error
        if (isset($order['error'])) {
            return response()->json([
                'error'   => true,
                'message' => $order['error']['message'] ?? 'PayPal validation failed',
                'paypal'  => $order,
            ], 422);
        }

        // SUCCESS — return the PayPal order ID
        return response()->json([
            'orderID' => $order['id'] ?? null
        ]);

    } catch (\Exception $e) {

        \Log::error('PayPal Order Error', [
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'error'   => true,
            'message' => 'Could not create PayPal order.',
            'details' => $e->getMessage(),
        ], 500);
    }
}    
    

public function capturePayPalOrder(Request $request)
{
    if (!$request->expectsJson()) {
        return response()->json(['error' => 'Invalid request'], 422);
    }
    
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $provider->getAccessToken();

    // Correct request key
    $orderID = $request->order_id;

    // Log for debugging
    \Log::info('PayPal Order Captured', ['order_id' => $orderID]);

    $result = $provider->capturePaymentOrder($orderID);

    \Log::info('PayPal Capture Result', ['result' => $result]);
    \Log::info('Capture Request Data', $request->all());
    \Log::info('PayPal Order ID', ['order_id' => $orderID]);



    if (isset($result['status']) && $result['status'] === 'COMPLETED') {
        $order = $this->createOrderAfterPayment($request, 'paypal', 'paid', $orderID);
        return response()->json(['status' => 'success', 'order_id' => $order->id]);
    }

    return response()->json([
        'status' => 'success',
        'redirect' => route('checkout.success')
    ]);
}


public function createSquarePayment(Request $request)
{
    \Log::info('Square Payment Init', ['request' => $request->all()]);

    $cart = session()->get('cart', []);
    $totalAmount = $this->calculateTotal($cart, $request);

    $mode = config('services.square.mode', 'sandbox');

    $accessToken = $mode === 'live'
        ? config('services.square.live_access_token')
        : config('services.square.sandbox_access_token');

     // --- NEW SDK STYLE ---
     $client = new SquareClient(
        token: $accessToken,
        options: [
            'baseUrl' => $mode === 'live' ? Environments::Production->value : Environments::Sandbox->value
        ]
    );
    
    $paymentsApi = $client->payments;
    $orderId = 'ORD-' . strtoupper(uniqid());

    try {
        $money = new Money();
        $money->setAmount((int)($totalAmount * 100)); // convert GBP → pence
        $money->setCurrency('GBP');

        $body = new CreatePaymentRequest(
            $request->source_id, // ← SQUARE PAYMENT TOKEN FROM JS
            $orderId
        );

        $body->setAmountMoney($money);

        $response = $paymentsApi->createPayment($body);

        if ($response->isSuccess()) {
            \Log::info('Square Payment Success', ['response' => $response->getResult()]);
            $order = $this->createOrderAfterPayment($request, 'square', 'paid', $orderId);
            return response()->json(['status' => 'success', 'order_id' => $order->id]);
        }

        \Log::error('Square Payment Failed', ['errors' => $response->getErrors()]);
        return response()->json(['status' => 'failed', 'errors' => $response->getErrors()]);

    } catch (\Exception $e) {
        \Log::error('Square Exception', ['error' => $e->getMessage()]);
        return response()->json(['status' => 'failed', 'errors' => $e->getMessage()]);
    }
}

public function getSquareConfig()
{
    $mode = config('services.square.mode', 'sandbox');

    $appId = $mode === 'live'
        ? config('services.square.live_application_id')
        : config('services.square.sandbox_application_id');

    $locationId = $mode === 'live'
        ? config('services.square.live_location_id')
        : config('services.square.sandbox_location_id');

    return response()->json([
        'appId' => $appId,
        'locationId' => $locationId,
    ]);
}


    // --- Total calculation ---
    private function calculateTotal($cart, $request)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Delivery fee
        $deliveryFee = 0;
        if ($request->delivery_type === 'delivery' && $request->delivery_option_id) {
            $option = DeliveryOption::find($request->delivery_option_id);
            if ($option->calculated_by === 'flat_rate') {
                $deliveryFee = $option->rate;
            } elseif ($option->calculated_by === 'weight_based') {
                $totalWeight = array_sum(array_map(fn($item) => $item['weight'] * $item['quantity'], $cart));
                $range = $option->weightRanges()->where('min_weight', '<=', $totalWeight)
                                               ->where('max_weight', '>=', $totalWeight)
                                               ->first();
                if ($range) $deliveryFee = $range->rate;
            }
        }

        return $subtotal + $deliveryFee;
    }

    public function store(Request $request)
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->back()->withErrors('Your cart is empty.');
    }

    $request->validate([
        'customer_email' => 'required|email',
        'customer_first_name' => 'required|string',
        'customer_last_name' => 'required|string',
        'customer_phone' => 'required|string',
        'delivery_type' => 'required|in:pickup,delivery',
        'delivery_option_id' => 'nullable|exists:delivery_options,id',
        'delivery_address' => 'nullable|string|max:500',
        'pickup_location_id' => 'nullable|exists:pickup_locations,id',
        'pickup_date' => 'nullable|date',
        'pickup_time' => 'nullable|string',
        'payment_method' => 'required|string',
    ]);

    $totalAmount = $this->calculateTotal($cart, $request);

    $order = DB::transaction(function () use ($request, $cart, $totalAmount) {
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_email' => $request->customer_email,
            'customer_first_name' => $request->customer_first_name,
            'customer_last_name' => $request->customer_last_name,
            'customer_phone' => $request->customer_phone,
            'customer_instagram' => $request->customer_instagram,
            'order_notes' => $request->order_notes,
            'delivery_type' => $request->delivery_type,
            'delivery_option_id' => $request->delivery_option_id,
            'delivery_address' => $request->delivery_address,
            'pickup_location_id' => $request->pickup_location_id,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending', // For now
            'delivery_pickup_status' => false,
        ]);

        $orderItems = [];
        foreach ($cart as $key => $details) {
            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $details['id'] ?? $key, 
                'quantity' => $details['quantity'],
                'unit_price' => $details['price'],
                'total_price' => $details['price'] * $details['quantity'],
                'product_options' => !empty($details['options']) ? $details['options'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        OrderItem::insert($orderItems);

        session()->forget('cart');

        return $order;
    });


    DB::afterCommit(function () use ($order) {
        Mail::to($order->customer_email)->send(new CustomerOrderMail($order));
        // Mail::to('info@tutuscupcakes.com')->send(new AdminOrderMail($order));
    });
    
    // 🔔 3. Create a Filament admin notification
    Notification::make()
        ->title('New Order')
        ->body("Order {$order->order_number} was placed.")
        ->success()
        ->sendToDatabase(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get());


    // Redirect to a success page or show a thank-you message
    return redirect()->route('checkout.success', $order->id)->with('status', 'Order placed successfully!');
}


public function success($orderId)
{

    $order = Order::with(['items.product'])->findOrFail($orderId); // load products

    // optional: clear cart session
    session()->forget('cart');

    return view('web.checkout.success', compact('order'));
}


}
