@extends('layouts.products')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
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

@php
    $hasSeasonal = collect($cart)->contains(fn($p) => !empty($p['seasonal']));
    $hasPreOrder = collect($cart)->contains(fn($p) => !empty($p['pre_order']));
    $seasonStart = null;
    $seasonMessage = null;

    if ($hasSeasonal) {
        $seasonalItem = collect($cart)->firstWhere('seasonal', true) ?? collect($cart)->firstWhere('seasonal', 1);
        if ($seasonalItem) {
            $seasonStart = isset($seasonalItem['season_start']) ? \Carbon\Carbon::parse($seasonalItem['season_start'])->format('Y-m-d') : null;
            $seasonMessage = $seasonalItem['pre_order_expectation'] ?? ($seasonStart ? 'Pickup for this product will only be available from ' . $seasonStart : null);
        }
    }

    $hasMixedCart = $hasSeasonal && collect($cart)->contains(fn($p) => empty($p['seasonal']));
@endphp


@section('content')
<div class="max-w-7xl mx-auto py-20 px-5 md:px-8 grid md:grid-cols-3 gap-8">

  {{-- LEFT COLUMN: Checkout Form --}}
  <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 section-title">Checkout</h1>

    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="space-y-10">
      @csrf

      {{-- CUSTOMER DETAILS --}}
      <div class="space-y-5">
        <h2 class="text-xl font-semibold text-gray-800">Customer Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="email" name="customer_email" placeholder="Email" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
          <input type="text" name="customer_first_name" placeholder="First Name" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
          <input type="text" name="customer_last_name" placeholder="Last Name" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
          <input type="text" name="customer_phone" placeholder="Phone Number" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
          <input type="text" name="customer_instagram" placeholder="Instagram (optional)" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm md:col-span-2 focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
          <textarea name="order_notes" placeholder="Order Notes (optional)" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm md:col-span-2 resize-none focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"></textarea>
        </div>
        <p class="text-xs text-gray-500">Your personal data will be used to process your order and support your experience.</p>
      </div>

      <hr class="border-gray-100">

      {{-- DELIVERY METHOD --}}
      <div class="space-y-5">
        <h2 class="text-xl font-semibold text-gray-800">Delivery Method</h2>
        @if($hasSeasonal)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg p-3 mb-3">
            {{ $seasonMessage }}
        </div>
        @endif

        @if($hasMixedCart)
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-3">
            Seasonal products like our Shop Christmas products must be ordered exclusively without other products. 
            Please remove non-seasonal items to continue.
        </div>
        @endif

        <pre>{{ var_dump($cart) }}</pre>
        @php
            $hasPickupOnly = collect($cart)->contains(function ($p) {
                if (is_array($p)) {
                    return !empty($p['pickup_only']);
                } elseif (is_object($p)) {
                    return !empty($p->pickup_only);
                }
                return false;
            });
        @endphp

        @if($hasPickupOnly)
          <p class="text-red-600 font-medium text-sm">Some products are pickup-only. Pickup is the only option.</p>
          <input type="hidden" name="delivery_type" value="pickup">
        @else
        <select name="delivery_type" id="delivery_type"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"
            {{ $hasMixedCart ? 'disabled' : '' }}>
            <option value="">Select Delivery Method</option>
            @if(!$hasSeasonal)
                <option value="delivery">Delivery</option>
            @endif
            <option value="pickup">Pickup</option>
        </select>
        @endif

        {{-- DELIVERY BLOCK --}}
        <div id="method_block" class="mt-3 space-y-5">
          <div id="delivery_address_container" class="hidden space-y-3">
            <textarea name="delivery_address" id="delivery_address" placeholder="Delivery Address" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-none focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"></textarea>

            <select name="delivery_option_id" id="delivery_option_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
              <option value="">Select Delivery Option</option>
              @foreach($deliveryOptions as $option)
                <option value="{{ $option->id }}" data-type="{{ $option->calculated_by }}" data-rate="{{ $option->rate }}">
                {{ $option->name }} - £{{ $option->rate }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- PICKUP BLOCK --}}
          <div id="pickup_container" class="hidden space-y-3">
            <select name="pickup_location_id" id="pickup_location_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
              <option value="">Select Pickup Location</option>
              @foreach($pickupLocations as $location)
                <option value="{{ $location->id }}"
                        data-days='@json($location->available_days)'
                        data-start-time="{{ \Carbon\Carbon::parse($location->start_time)->format('H:i') }}"
                        data-end-time="{{ \Carbon\Carbon::parse($location->end_time)->format('H:i') }}"
                        data-interval="{{ $location->interval_minutes }}">
                  {{ $location->name }} — {{ $location->address }}
                </option>
              @endforeach
            </select>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <input type="text" name="pickup_date" id="pickup_date" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none" placeholder="Select date">
              <select name="pickup_time" id="pickup_time" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none" disabled></select>
            </div>
          </div>
        </div>
      </div>

      <hr class="border-gray-100">

      {{-- PAYMENT --}}
      <div class="space-y-5">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">Payment Method</h2>

        <div id="paymentMethods" class="flex gap-4">
        <!-- Square -->
        <div data-method="square"
            class="payment-option flex items-center gap-2 border border-gray-300 rounded-lg p-3 cursor-pointer hover:border-pink-500 transition-all">
            <img src="{{ asset('images/square-logo.webp') }}" alt="Square" class="h-6">
            <!-- <span>Square</span> -->
        </div>

        <!-- PayPal -->
        <div data-method="paypal"
            class="payment-option flex items-center gap-2 border border-gray-300 rounded-lg p-3 cursor-pointer hover:border-pink-500 transition-all">
            <img src="{{ asset('images/paypal-logo.webp') }}" alt="PayPal" class="h-6">
            <span>PayPal</span>
        </div>
        </div>

        <!-- Placeholder for payment button -->
        <div id="payment_placeholder" class="mt-4"></div>

        <!-- Hidden input to store selected payment method -->
        <input type="hidden" name="payment_method" id="payment_method_input">

      </div>
    </form>
  </div>

  {{-- RIGHT COLUMN: CART SUMMARY --}}
  <div class="bg-[var(--brand-bg)] rounded-2xl border border-pink-100 shadow-inner p-6 md:p-8 h-fit">
    <h3 class="text-xl font-semibold text-gray-800 mb-5">Your Cart</h3>

    <div class="space-y-4 divide-y divide-pink-100">
      @foreach($cart as $item)
        <div class="flex items-center justify-between pt-3">
          <div class="flex items-center gap-3">
            <img src="{{ asset('storage/' . $item['image']) }}" class="w-12 h-12 rounded-lg object-cover" alt="{{ $item['name'] }}">
            <div class="text-left">
              <p class="text-sm font-medium text-gray-800">{{ $item['name'] }}</p>
              <p class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</p>
            </div>
          </div>
          <p class="text-sm font-semibold text-gray-700">£{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
        </div>
      @endforeach
    </div>

    <div class="border-t border-pink-100 mt-6 pt-4 space-y-2">
      <div class="flex justify-between text-sm text-gray-700">
        <span>Subtotal</span>
        <span id="subtotalDisplay">£{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),2) }}</span>
      </div>
      <div class="flex justify-between text-sm text-gray-700">
        <span>Delivery</span>
        <span id="deliveryFeeDisplay">£0.00</span>
      </div>
      <div class="flex justify-between text-base font-semibold text-gray-900">
        <span>Total</span>
        <span id="orderTotal">£0.00</span>
      </div>
    </div>
    <div class="space-y-3 pt-4">
    <a href="/cart"
            class="block text-center bg-white border border-gray-200 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">
            Back to Cart
        </a>
    </div>
  </div>
</div>
@endsection

@php
    $deliveryWeightRangesJson = $deliveryOptions->mapWithKeys(function($o) {
        return [
            $o->id => $o->weightRanges->map(function($r) {
                return [
                    'min' => (float)$r->min_weight,
                    'max' => (float)$r->max_weight,
                    'rate' => (float)$r->rate,
                ];
            })->values(),
        ];
    })->toJson();
@endphp


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("⚡ Checkout script initialized");

    // --- server-provided data ---
    const cart = @json($cart);
    const hasPickupOnly = {{ $hasPickupOnly ? 'true' : 'false' }};

    // --- DOM refs ---
    const deliveryTypeSelect = document.getElementById('delivery_type');
    const deliveryAddressContainer = document.getElementById('delivery_address_container');
    const pickupContainer = document.getElementById('pickup_container');
    const pickupLocationSelects = document.querySelectorAll('#pickup_container select, #pickup_container input[type="date"]');
    const pickupLocationSelect = document.querySelector('#pickup_container select[name="pickup_location_id"]');
    const pickupDateInput = document.getElementById('pickup_date');
    const pickupTimeSelect = document.querySelector('#pickup_container select[name="pickup_time"]');
   // Reinitialize flatpickr when pickup location changes
    pickupLocationSelect.addEventListener('change', function() {
    const selectedOption = pickupLocationSelect.options[pickupLocationSelect.selectedIndex];
    const availableDays = JSON.parse(selectedOption.dataset.days || '[]'); // e.g. ["Monday","Wednesday"]

    // Destroy previous flatpickr instance if any
    if (pickupDateInput._flatpickr) {
        pickupDateInput._flatpickr.destroy();
    }

    // Recreate flatpickr with day restriction
    flatpickr(pickupDateInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true,
        disable: [
        function(date) {
            const weekday = date.toLocaleString('en-US', { weekday: 'long' });
            // disable if the weekday is NOT in availableDays
            return !availableDays.includes(weekday);
        }
        ],
        onDayCreate: function(dObj, dStr, fp, dayElem) {
        const weekday = dayElem.dateObj.toLocaleString('en-US', { weekday: 'long' });
        if (!availableDays.includes(weekday)) {
            // make it visibly greyed-out
            dayElem.style.opacity = '0.3';
            dayElem.style.pointerEvents = 'none';
        }
        },
        onChange: function(selectedDates, dateStr) {
        pickupDateInput.dispatchEvent(new Event('change'));
        }
    });
    });


    const deliveryOptionSelect = document.getElementById('delivery_option_id');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
    const orderTotalDisplay = document.getElementById('orderTotal');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentPlaceholder = document.getElementById('payment_placeholder');
    const paymentInput = document.getElementById('payment_method_input');

    // --- state ---
    let subtotal = Object.values(cart).reduce((sum, p) => sum + (parseFloat(p.price) || 0) * (p.quantity || 0), 0);
    let deliveryFee = 0;
    let fpInstance = null;
    let paypalLoaded = false;
    let squareLoaded = false;

    // --- helper ---
    function updateTotals() {
        subtotalDisplay.textContent = '£' + subtotal.toFixed(2);
        deliveryFeeDisplay.textContent = '£' + deliveryFee.toFixed(2);
        orderTotalDisplay.textContent = '£' + (subtotal + deliveryFee).toFixed(2);
    }

    // ✅ Generate time slots when pickup location or date changes
    function updatePickupTimeSlots() {
    const locSelect = pickupLocationSelect;
    const dateInput = pickupDateInput;
    const timeSelect = pickupTimeSelect;

    // Clear old slots first
    timeSelect.innerHTML = '<option value="">Select a time</option>';
    timeSelect.disabled = true;

    if (!locSelect?.value || !dateInput?.value) return;

    const selectedOption = locSelect.options[locSelect.selectedIndex];
    const start = selectedOption.dataset.startTime;
    const end = selectedOption.dataset.endTime;
    const intervalMinutes = parseInt(selectedOption.dataset.interval || "30", 10);

    if (!start || !end) return;

    let startTime = new Date(`1970-01-01T${start}`);
    let endTime = new Date(`1970-01-01T${end}`);

    const slots = [];
    while (startTime < endTime) {
        slots.push(startTime.toTimeString().substring(0, 5)); // HH:MM
        startTime = new Date(startTime.getTime() + intervalMinutes * 60000);
    }

    slots.forEach(slot => {
        const opt = document.createElement('option');
        opt.value = slot;
        opt.textContent = slot;
        timeSelect.appendChild(opt);
    });

    // ✅ Enable once slots are ready
    timeSelect.disabled = false;
    }

pickupLocationSelect.addEventListener('change', updatePickupTimeSlots);
pickupDateInput.addEventListener('change', updatePickupTimeSlots);


    // --- disable payment options initially ---
    paymentOptions.forEach(option => {
        option.classList.add('opacity-50', 'pointer-events-none');
    });

    // --- toggle delivery/pickup UI ---
    function toggleMethodBlocks() {
        if (hasPickupOnly || (deliveryTypeSelect && deliveryTypeSelect.value === 'pickup')) {
            deliveryAddressContainer?.classList.add('hidden');
            pickupContainer?.classList.remove('hidden');
        } else if (deliveryTypeSelect && deliveryTypeSelect.value === 'delivery') {
            deliveryAddressContainer?.classList.remove('hidden');
            pickupContainer?.classList.add('hidden');
        } else {
            deliveryAddressContainer?.classList.add('hidden');
            pickupContainer?.classList.add('hidden');
        }

        calculateDeliveryFee();
        togglePaymentAccess();
    }

    // --- enable/disable payment options based on delivery method ---
    function togglePaymentAccess() {
        const selectedValue = deliveryTypeSelect ? deliveryTypeSelect.value : '';
        if (selectedValue || hasPickupOnly) {
            paymentOptions.forEach(option => {
                option.classList.remove('opacity-50', 'pointer-events-none');
            });
        } else {
            paymentOptions.forEach(option => {
                option.classList.add('opacity-50', 'pointer-events-none');
            });
        }
    }

    if (deliveryTypeSelect) deliveryTypeSelect.addEventListener('change', toggleMethodBlocks);
    toggleMethodBlocks();

    // --- delivery fee calculation ---
    const deliveryWeightRanges = {!! $deliveryWeightRangesJson !!};
    function calculateDeliveryFee() {
        deliveryFee = 0;
        if (!deliveryOptionSelect || !deliveryOptionSelect.value || !deliveryTypeSelect || deliveryTypeSelect.value !== 'delivery') {
            updateTotals();
            return;
        }

        const selectedOption = deliveryOptionSelect.selectedOptions[0];
        if (!selectedOption) {
            updateTotals();
            return;
        }

        const type = selectedOption.dataset.type || '';
        const baseRate = parseFloat(selectedOption.dataset.rate || 0);
        let totalWeight = 0;
        let matched = null;
        let optionRanges = [];

        if (type === 'flat_rate') {
            deliveryFee = baseRate;
        } else if (type === 'weight_based') {
            totalWeight = Object.values(cart).reduce((sum, p) => {
                const w = parseFloat(p.weight) || 0;
                const qty = parseInt(p.quantity || 0, 10);
                return sum + (w * qty);
            }, 0);

            optionRanges = (deliveryWeightRanges && deliveryWeightRanges[deliveryOptionSelect.value]) || [];
            matched = optionRanges.find(r => {
                const min = parseFloat(r.min);
                const max = parseFloat(r.max);
                return totalWeight >= min && totalWeight <= max;
            });

            deliveryFee = matched ? parseFloat(matched.rate) : baseRate;

            if (matched) {
                selectedOption.textContent = `${selectedOption.textContent.split(' - ')[0]} - £${matched.rate.toFixed(2)}`;
            }

            console.log("Weight-based selected — totalWeight:", totalWeight, "matched:", matched ? matched.rate : 'none', "fee:", deliveryFee);
        } else {
            deliveryFee = baseRate;
        }

        updateTotals();
    }

    if (deliveryOptionSelect) deliveryOptionSelect.addEventListener('change', calculateDeliveryFee);

    calculateDeliveryFee();
    updateTotals();

    // --- payment option click handling ---
    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            const deliveryType = document.getElementById('delivery_type');
            if (deliveryType && !deliveryType.value && !hasPickupOnly) {
                alert('Please select a delivery method before choosing a payment option.');
                return;
            }

            paymentOptions.forEach(o => o.classList.remove('border-pink-500', 'bg-pink-50'));
            option.classList.add('border-pink-500', 'bg-pink-50');
            paymentInput.value = option.dataset.method;
            paymentPlaceholder.innerHTML = '';

            if (option.dataset.method === 'square') loadSquareButton();
            if (option.dataset.method === 'paypal') loadPayPalButton();
        });
    });

    // --- Square ---
    const squareMode = "{{ config('services.square.mode') }}";
    const squareAppId = squareMode === 'live'
        ? "{{ config('services.square.live_application_id') }}"
        : "{{ config('services.square.sandbox_application_id') }}";
    const squareLocationId = squareMode === 'live'
        ? "{{ config('services.square.live_location_id') }}"
        : "{{ config('services.square.sandbox_location_id') }}";

    function loadSquareButton() {
        if (squareLoaded) return initSquarePayment();
        const script = document.createElement('script');
        script.src = squareMode === 'live'
            ? 'https://web.squarecdn.com/v1/square.js'
            : 'https://sandbox.web.squarecdn.com/v1/square.js';
        script.onload = () => {
            squareLoaded = true;
            initSquarePayment();
        };
        document.body.appendChild(script);
    }

    async function initSquarePayment() {
        if (!window.Square) return console.error("Square SDK not loaded");

        const placeholder = document.getElementById('payment_placeholder');
        placeholder.innerHTML = `<div id="square-payment-button" class="w-full"></div>`;

        const response = await fetch('/square/client-token');
        const data = await response.json();
        const clientToken = data.clientToken;

        const payments = Square.payments(squareAppId, squareLocationId, { clientToken });
        const card = await payments.card();
        await card.attach('#square-payment-button');

        document.getElementById('square-payment-button').addEventListener('click', async () => {
            const result = await card.tokenize();
            if (result.status === 'OK') {
                console.log(`Square ${squareMode} token:`, result.token);
                document.getElementById('checkoutForm').submit();
            } else {
                alert("Square tokenization failed");
            }
        });
    }

    // --- PayPal ---
    function loadPayPalButton() {
        if (paypalLoaded) return initPayPalButton();

        const paypalMode = "{{ config('services.paypal.mode') }}";
        const paypalClientId = paypalMode === 'live'
            ? "{{ config('services.paypal.live_client_id') }}"
            : "{{ config('services.paypal.sandbox_client_id') }}";

        const script = document.createElement('script');
        script.src = `https://www.paypal.com/sdk/js?client-id=${paypalClientId}&currency=GBP`;
        script.onload = () => {
            paypalLoaded = true;
            initPayPalButton();
        };
        document.body.appendChild(script);
    }

    function initPayPalButton() {
    const placeholder = document.getElementById('payment_placeholder');
    let container = document.getElementById('paypal-button-container');

    // Avoid multiple renders
    if (container) {
        container.innerHTML = '';
    } else {
        placeholder.innerHTML = `<div id="paypal-button-container"></div>`;
        container = document.getElementById('paypal-button-container');
    }

    if (typeof paypal === 'undefined') {
        console.error("PayPal SDK not loaded");
        return;
    }

    const orderTotal = parseFloat(document.getElementById('orderTotal').textContent.replace('£', '') || 0).toFixed(2);

    // Render PayPal button only once
    paypal.Buttons({
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{ amount: { value: orderTotal } }]
            });
        },
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                alert(`PayPal {{ config('services.paypal.mode') }} payment completed by ${details.payer.name.given_name}`);
                document.getElementById('checkoutForm').submit();
            });
        }
    }).render('#paypal-button-container');
}

});
</script>
@endpush
