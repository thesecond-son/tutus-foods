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
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Types\Money;
use Square\Types\Currency;
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
            'delivery_town' => 'nullable|string|max:500',
            'delivery_postcode' => 'nullable|string|max:500',
            'delivery_fee' => 'nullable|numeric|min:0',
            'pickup_location_id' => 'nullable|exists:pickup_locations,id',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable|string',
        ]);

        $totalAmount = $this->calculateTotal($cart, $request);

        $order = DB::transaction(function () use (
            $request,
            $cart,
            $totalAmount,
            $paymentMethod,
            $paymentStatus,
            $paymentReference
        ) {
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
                'delivery_town' => $request->delivery_town,
                'delivery_postcode' => $request->delivery_postcode,
                'delivery_fee' => $request->delivery_fee ?? 0,
                'pickup_location_id' => $request->pickup_location_id,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status'              => 'completed',
                'payment_reference' => $paymentReference,
                'delivery_pickup_status' => false,
            ]);
    
            // Prepare multiple order items
            $orderItems = [];
            foreach ($cart as $details) {
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $details['id'], // ✅ REAL ID
                    'quantity' => $details['quantity'],
                    'unit_price' => $details['price'],
                    'total_price' => $details['price'] * $details['quantity'],
                    'product_options' => !empty($details['options'])
                        ? json_encode($details['options'])
                        : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            OrderItem::insert($orderItems);
            \Log::info('Items Ordered', ['Order Items' => $orderItems]);
    
            // Clear cart
            session()->forget('cart');
    
            return $order;
        });

        // REGISTER EMAILS HERE (inside transaction)
    DB::afterCommit(function () use ($order) {
        Mail::to($order->customer_email)->send(new CustomerOrderMail($order));
        Mail::to('info@tutuscupcakes.com')->send(new AdminOrderMail($order));
        // \Log::info('Email sent AFTER COMMIT for order ' . $order->order_number);
    });

    return $order;
}


// --- PayPal ---
public function createPayPalOrder(Request $request)
{
    $cart = $request->cart ?? session()->get('cart');

    if (!$cart || empty($cart)) {
        return response()->json([
            'error' => true,
            'message' => 'Cart is empty',
        ], 422);
    }


    try {

         // Calculate total from cart instead of trusting frontend
        $total = floatval($request->total ?? 0);

        \Log::info('Creating PayPal Order', [
            'cart' => $cart,
            'total' => $total
        ]);        

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
        $accessToken = $provider->getAccessToken();

        if (!$accessToken) {
            \Log::error('PayPal access token failed');
            return response()->json([
                'error' => true,
                'message' => 'Could not get PayPal access token',
            ], 500);
        }

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

        \Log::info('PayPal raw response', ['order' => $order]);

         // Check for errors from PayPal
         if (!isset($order['id'])) {
            \Log::error('PayPal Order creation failed', ['response' => $order]);
            return response()->json([
                'error' => true,
                'message' => $order['message'] ?? 'PayPal order creation failed',
                'paypal' => $order,
            ], 500);
        }

        \Log::error('PayPal Order Creation Response', $order);

        $request->validate([
            'customer_email' => 'required|email',
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
        ]);        

        $localOrder = Order::create([
            'order_number'      => 'ORD-' . strtoupper(uniqid()),
            'paypal_order_id'   => $order['id'],
            'payment_reference' => $order['id'],
        
            'customer_email'    => $request->customer_email,
            'customer_first_name' => $request->customer_first_name,
            'customer_last_name'  => $request->customer_last_name,
            'customer_phone'    => $request->customer_phone,
        
            'delivery_type'     => $request->delivery_type,
            'delivery_option_id'=> $request->delivery_option_id,
            'delivery_address'  => $request->delivery_address,
            'delivery_town'     => $request->delivery_town,
            'delivery_postcode' => $request->delivery_postcode,
            'delivery_fee'      => $request->delivery_fee ?? 0,
        
            'pickup_location_id'=> $request->pickup_location_id,
            'pickup_date'       => $request->pickup_date,
            'pickup_time'       => $request->pickup_time,

            'cart_snapshot'     => json_encode($request->cart ?? session()->get('cart', [])),
        
            'total_amount'      => $total,
            'payment_method'    => 'paypal',
            'payment_status'    => 'pending',
            'status'            => 'pending',
        ]);        
        
        \Log::info('Local Order Created', ['order' => $localOrder->toArray()]);
        \Log::info('Saved Order Fields', $localOrder->toArray());


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
    \Log::info('Incoming PayPal capture request', $request->all());

    if (! $request->expectsJson()) {
        return response()->json(['error' => 'Invalid request'], 422);
    }

    $orderID = $request->input('order_id');

    // 1️⃣ Find local order FIRST using the PayPal order ID
    $order = Order::where('paypal_order_id', $orderID)->first();

    if (! $order) {
        \Log::error('PayPal capture but local order not found', [
            'paypal_order_id' => $orderID,
        ]);

        return response()->json([
            'error' => 'Order not found',
        ], 404);
    }

    // 2️⃣ Prevent double capture / double restore
    if ($order->payment_status === 'paid') {
        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
        ]);
    }
    
    // 3️⃣ Capture payment from PayPal
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $provider->getAccessToken();


    // Log for debugging
    \Log::info('PayPal Order Captured', ['order_id' => $orderID]);

    $result = $provider->capturePaymentOrder($orderID);

    \Log::info('PayPal Capture Result', ['result' => $result]);
    \Log::info('Capture Request Data', $request->all());
    \Log::info('PayPal Order ID', ['order_id' => $orderID]);


    // 4️⃣ Handle success
    if (($result['status'] ?? null) === 'COMPLETED') {

        DB::transaction(function () use ($order, $request) {
    
            // 1️⃣ Mark order as paid
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed',

                'delivery_type' => $request->delivery_type,
                'delivery_option_id'=> $request->delivery_option_id,
                'delivery_address'  => $request->delivery_address,
                'delivery_town'     => $request->delivery_town,
                'delivery_postcode' => $request->delivery_postcode,
                'delivery_fee'      => $request->delivery_fee ?? 0,
            
                'pickup_location_id'=> $request->pickup_location_id,
                'pickup_date'       => $request->pickup_date,
                'pickup_time'       => $request->pickup_time,
            ]);

            if ($order->items()->exists()) {
                return;
            }
    
            // 2️⃣ Restore items from snapshot
            $cart = json_decode($order->cart_snapshot, true);

            if (!is_array($cart) || empty($cart)) {
                throw new \Exception('Invalid cart snapshot for order ' . $order->id);
            }

    
            $items = [];
    
            foreach ($cart as $item) {
                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'product_options' => !empty($item['options'])
                        ? json_encode($item['options'])
                        : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
    
            OrderItem::insert($items);
    
            \Log::info('Order restored from PayPal capture', [
                'order_id' => $order->id,
                'items' => count($items),
            ]);
        });
    
        DB::afterCommit(function () use ($order) {
            Mail::to($order->customer_email)->send(new CustomerOrderMail($order));
            Mail::to('info@tutuscupcakes.com')->send(new AdminOrderMail($order));
        });

        session()->forget('cart');
    
        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
            'redirect' => route('checkout.success', ['order' => $order->id])
        ]);

        
    }    

    // 5️⃣ Capture failed
    return response()->json([
        'status' => 'failed',
        'message' => 'Payment not completed',
    ], 400);
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


public function createSquarePayment(Request $request)
{
    \Log::info('Square Payment Init', ['request' => $request->all()]);

    $cart = session()->get('cart', []);
    $totalAmount = $this->calculateTotal($cart, $request);

    if ($totalAmount <= 0) {
        return response()->json(['status' => 'failed', 'errors' => 'Invalid total'], 400);
    }

    $mode = config('services.square.mode', 'sandbox');

    $accessToken = $mode === 'live'
        ? config('services.square.live_access_token')
        : config('services.square.sandbox_access_token');

    \Log::info('Square Backend Mode Check', [
        'mode' => $mode,
        'token_prefix' => substr($accessToken, 0, 4),
    ]);
        

    // ✅ NEW SDK CLIENT
    $square = new SquareClient(
        token: $accessToken,
        options: [
            'baseUrl' => $mode === 'live'
                ? Environments::Production->value
                : Environments::Sandbox->value,
        ]
    );

    $orderId = 'ORD-' . strtoupper(uniqid());

    // ✅ Correct Money object
    $money = new Money([
        'amount' => (int) round($totalAmount * 100), // smallest unit
        'currency' => 'GBP',
    ]);

    // ✅ Correct CreatePaymentRequest
    $paymentRequest = new CreatePaymentRequest([
        'sourceId' => (string) $request->source_id,
        'idempotencyKey' => (string) $orderId,
        'amountMoney' => $money,
        'autocomplete' => true,
        'buyerEmailAddress' => $request->customer_email,
        'note' => "Order $orderId",
    ]);

    try {
        // ✅ CORRECT METHOD
        $response = $square->payments->create(
            request: $paymentRequest
        );

         // The response is the result directly, not wrapped
         \Log::info('Square Payment Response', ['response' => $response]);

         $payment = $response->getPayment();

        // Check if payment was successful
        
        if ($payment && $payment->getStatus() === 'COMPLETED') {

            $order = $this->createOrderAfterPayment(
                $request,
                'square',
                'paid',
                $payment->getId()
            );

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'payment_id' => $payment->getId(),
            ]);
        }

        \Log::error('Square Payment Failed', ['response' => $response]);

        return response()->json([
            'status' => 'failed',
            'errors' => $response['errors'] ?? ['message' => 'Payment not completed'],
        ], 400);

        } catch (\Throwable $e) {
            \Log::error('Square Exception', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'failed', 'errors' => $e->getMessage()], 500);
        }
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
        'delivery_town' => 'nullable|string|max:500',
        'delivery_postcode' => 'nullable|string|max:500',
        'delivery_fee' => 'nullable|numeric|min:0',
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
            'delivery_town' => $request->delivery_town,
            'delivery_postcode' => $request->delivery_postcode,
            'delivery_fee' => $request->delivery_fee ?? 0,
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
