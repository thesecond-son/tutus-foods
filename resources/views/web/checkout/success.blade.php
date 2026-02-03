@extends('layouts.products')

<style>
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
  }
  body {
    background: #fafafa;
  }

  .payment-option.opacity-50 {
  filter: grayscale(100%);
  cursor: not-allowed;
}

</style>

@section('content')
<div class="max-w-4xl mx-auto py-20 px-6">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <div class="text-pink-500 text-5xl mx-auto mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Successful 🎉</h1>
        <p class="text-gray-600 text-lg">Thank you for shopping with Tutu’s Foods! Please check your mail, spam or promotions for your order receipt.</p>
    </div>

    {{-- ORDER CARD --}}
    <div class="bg-white shadow-sm border border-pink-100 rounded-3xl p-8 space-y-8">

        {{-- ORDER SUMMARY --}}
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $item->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            @if(!empty($item->product_options))
                                <ul style="margin: 6px 0 0 0; padding-left: 16px;">
                                    @foreach($item->product_options as $name => $value)
                                        <li>
                                            <strong>{{ $name }}:</strong> {{ $value }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-gray-700">
                            £{{ number_format($item->total_price, 2) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TOTALS --}}
        <div class="space-y-2 border-t border-gray-100 pt-5">
            @php
                $subtotal = $order->items->sum('total_price');
                $deliveryFee = $order->delivery_type === 'delivery' ? ($order->delivery_option->rate ?? 0) : 0;
            @endphp

            <div class="flex justify-between text-sm text-gray-700">
                <span>Subtotal</span>
                <span>£{{ number_format($subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between text-sm text-gray-700">
                    <span>Delivery</span>
                    <span>£{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            <div class="flex justify-between text-xl font-bold text-gray-900 mt-3">
                <span>Total Paid</span>
                <span>£{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- CUSTOMER DETAILS --}}
        <div class="pt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <p><strong>Name:</strong> {{ $order->customer_first_name }} {{ $order->customer_last_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>

                @if($order->delivery_type === 'delivery')
                    <p class="md:col-span-2"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                @else
                    <p><strong>Pickup Date:</strong> {{ $order->pickup_date }}</p>
                    <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
                @endif
            </div>
        </div>

    </div>

    {{-- BUTTONS --}}
    <div class="flex justify-center gap-4 mt-10">
        <a href="/products" class="px-6 py-3 bg-[var(--brand-pink)] text-white rounded-xl font-semibold hover:bg-pink-600 transition">
            Continue Shopping
        </a>
    </div>

</div>
@endsection
