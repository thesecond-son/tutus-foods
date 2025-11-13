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
use Square\Environment;
use Square\Models\Money;
use Square\Exceptions\ApiException;
use Square\Models\CreatePaymentLinkRequest;
use Square\Models\CreatePaymentRequest;

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
    private function createOrderAfterPayment(Request $request, $paymentMethod, $paymentStatus, $paymentReference = null)
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
        ]);

        $totalAmount = $this->calculateTotal($cart, $request);

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
            'payment_reference' => $paymentReference,
        ]);

        foreach ($cart as $productId => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'unit_price' => $details['price'],
                'total_price' => $details['price'] * $details['quantity'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return $order;
    }

    // --- PayPal ---
    public function createPayPalOrder(Request $request)
    {
        $total = $this->calculateTotal(session()->get('cart', []), $request);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('services.paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "GBP",
                    "value" => $total
                ]
            ]]
        ]);

        return response()->json($order);
    }

    public function capturePayPalOrder(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('services.paypal'));
        $provider->getAccessToken();

        $result = $provider->capturePaymentOrder($request->orderID);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            $order = $this->createOrderAfterPayment($request, 'paypal', 'paid', $request->orderID);
            return response()->json(['status' => 'success', 'order_id' => $order->id]);
        }

        return response()->json(['status' => 'failed', 'data' => $result]);
    }

    public function createSquarePayment(Request $request)
{
    $cart = session()->get('cart', []);
    $totalAmount = $this->calculateTotal($cart, $request);

    $mode = config('services.square.mode', 'sandbox');

    $accessToken = $mode === 'live'
        ? config('services.square.live_access_token')
        : config('services.square.sandbox_access_token');

    // ✅ Proper instantiation (string arguments)
    $client = new SquareClient([
        'accessToken' => (string)$accessToken,
        'environment' => $mode === 'live' ? Environment::PRODUCTION : Environment::SANDBOX,
    ]);

    $paymentsApi = $client->getPaymentsApi();
    $orderId = 'ORD-' . strtoupper(uniqid());

    try {
        // Build Money object
        $money = new Money();
        $money->setAmount((int)($totalAmount * 100));
        $money->setCurrency('GBP');

        // Build payment request
        $body = new CreatePaymentRequest(
            $request->nonce, // From frontend
            $orderId
        );
        $body->setAmountMoney($money);

        $response = $paymentsApi->createPayment($body);

        if ($response->isSuccess()) {
            $order = $this->createOrderAfterPayment($request, 'square', 'paid', $orderId);
            return response()->json(['status' => 'success', 'order_id' => $order->id]);
        }

        return response()->json(['status' => 'failed', 'errors' => $response->getErrors()]);

    } catch (ApiException $e) {
        return response()->json(['status' => 'failed', 'errors' => $e->getMessage()]);
    }
}
    
public function getSquareClientToken()
{
    $mode = config('services.square.mode', 'sandbox');

    $accessToken = $mode === 'live'
        ? config('services.square.live_access_token')
        : config('services.square.sandbox_access_token');

    return response()->json([
        'clientToken' => $accessToken,
    ]);
}

    // --- Order success page ---
    public function success(Order $order)
    {
        return view('web.checkout.success', compact('order'));
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
}
