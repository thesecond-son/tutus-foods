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
    <p class="text-red-600 font-medium text-sm">
        Some products are pickup-only. Pickup is the only option.
    </p>

    <!-- Show the select, disabled, pre-selected to pickup -->
    <select name="delivery_type" id="delivery_type" disabled class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
        <option value="pickup" selected>Pickup</option>
    </select>
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
            <textarea name="delivery_address" id="delivery_address" placeholder="Delivery Address" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-none focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"></textarea>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <textarea name="delivery_town" id="delivery_town" placeholder="Town" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-none focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"></textarea>
                <textarea name="delivery_postcode" id="delivery_postcode" placeholder="Postcode" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-none focus:ring-2 focus:ring-[var(--brand-pink)] outline-none"></textarea>
            </div>


            <select name="delivery_option_id" id="delivery_option_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[var(--brand-pink)] outline-none">
              <option value="">Select Delivery Option</option>
              @foreach($deliveryOptions as $option)
                <option value="{{ $option->id }}" data-type="{{ $option->calculated_by }}" data-rate="{{ $option->rate }}">
                {{ $option->name }}
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
      <h2 class="text-xl font-semibold text-gray-800 mb-2">Pay with Card</h2>

        <div id="paymentMethods" class="flex gap-4">
        <!-- Square -->
        <div data-method="square"
            class="payment-option flex items-center gap-2 border border-gray-300 rounded-lg p-3 cursor-pointer hover:border-pink-500 transition-all">
            <img src="{{ asset('images/square-logo.webp') }}" alt="Square" class="h-6">
          
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
    const deliveryAddressInput = document.getElementById('delivery_address');
    const deliveryTownInput = document.getElementById('delivery_town');
    const deliveryPostcodeInput = document.getElementById('delivery_postcode');

    const pickupContainer = document.getElementById('pickup_container');
    const pickupLocationSelects = document.querySelectorAll('#pickup_container select, #pickup_container input[type="date"]');
    const pickupLocationSelect = document.querySelector('#pickup_container select[name="pickup_location_id"]');
    // const pickupLocationSelect = document.getElementById('pickup_location_id');
    const pickupDateInput = document.getElementById('pickup_date');
    const pickupTimeSelect = document.querySelector('#pickup_container select[name="pickup_time"]');
    const deliveryOptionSelect = document.getElementById('delivery_option_id');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
    const orderTotalDisplay = document.getElementById('orderTotal');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentPlaceholder = document.getElementById('payment_placeholder');
    const paymentInput = document.getElementById('payment_method_input');

     // --- disable payment options initially ---
     paymentOptions.forEach(option => {
        option.classList.add('opacity-50', 'pointer-events-none');
    });

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
        minDate: new Date().fp_incr(2),
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

    // --- state ---
    let subtotal = Object.values(cart).reduce((sum, p) => sum + (parseFloat(p.price) || 0) * (p.quantity || 0), 0);
    let deliveryFee = 0;
    let finalTotal = 0;
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

    function isReadyForPayment() {
        const type = deliveryTypeSelect?.value;

        // Pickup: must pick date + time
        if (type === "pickup" || hasPickupOnly) {
            return pickupDateInput.value && pickupTimeSelect.value;
        }

        // Delivery: must pick delivery option
        if (type === "delivery") {
            return ( 
            deliveryAddressInput?.value.trim() &&
            deliveryTownInput?.value.trim() &&
            deliveryPostcodeInput?.value.trim() &&
            deliveryOptionSelect?.value
            );
        }

        return false;
    }


    function togglePaymentAccess() {
    if (isReadyForPayment()) {
        paymentOptions.forEach(option =>
            option.classList.remove('opacity-50', 'pointer-events-none')
        );
    } else {
        paymentOptions.forEach(option =>
            option.classList.add('opacity-50', 'pointer-events-none')
        );
    }
}


    if (deliveryTypeSelect) deliveryTypeSelect.addEventListener('change', toggleMethodBlocks);
    pickupDateInput?.addEventListener('change', togglePaymentAccess);
    pickupTimeSelect?.addEventListener('change', togglePaymentAccess);
    deliveryAddressInput?.addEventListener('input', togglePaymentAccess);
    deliveryTownInput?.addEventListener('input', togglePaymentAccess);
    deliveryPostcodeInput?.addEventListener('input', togglePaymentAccess);
    deliveryOptionSelect?.addEventListener('change', togglePaymentAccess);
    deliveryTypeSelect?.addEventListener('change', togglePaymentAccess);

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
    let squareAppId = "";
    let squareLocationId = "";
    

// Fetch Square config from backend (appId + locationId)
async function loadSquareConfig() {
    const res = await fetch('/square/config');
    const data = await res.json();

    squareAppId = data.appId;
    squareLocationId = data.locationId;
}


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

    function collectCustomerInfo() {
    return {
        customer_email: document.querySelector('input[name="customer_email"]').value,
        customer_first_name: document.querySelector('input[name="customer_first_name"]').value,
        customer_last_name: document.querySelector('input[name="customer_last_name"]').value,
        customer_phone: document.querySelector('input[name="customer_phone"]').value,
        delivery_type: document.querySelector('select[name="delivery_type"]').value,
        
        delivery_address: document.querySelector('textarea[name="delivery_address"]')?.value ?? null,
        delivery_town: document.querySelector('textarea[name="delivery_town"]')?.value ?? null,
        delivery_postcode: document.querySelector('textarea[name="delivery_postcode"]')?.value ?? null,

        delivery_option_id: document.getElementById('delivery_option_id')?.value ?? null,
        pickup_location_id: document.querySelector('#pickup_container select[name="pickup_location_id"]')?.value ?? null,
        pickup_date: document.getElementById('pickup_date')?.value ?? null,
        pickup_time: document.querySelector('#pickup_container select[name="pickup_time"]')?.value ?? null,

        delivery_fee: deliveryFee
    };
}


    async function initSquarePayment() {
        if (!window.Square) {
    console.log("Waiting for Square SDK...");
    return;
}

        // Load config first (REQUIRED)
    await loadSquareConfig();

    const placeholder = document.getElementById('payment_placeholder');
    placeholder.innerHTML = `
    <div id="card-container"></div>
    <button id="sq-pay" type="button" class="w-full mt-4 bg-black text-white p-3 rounded-lg">
        Pay with Card
    </button>
`;

// 1️⃣ Create payments instance (NO client token needed)
const payments = window.Square.payments(squareAppId, squareLocationId);

// 2️⃣ Create & attach card element
const card = await payments.card();
await card.attach('#card-container');

// 3️⃣ On Payment
document.getElementById('sq-pay').addEventListener('click', async () => {
    const btn = document.getElementById('sq-pay');
    btn.disabled = true;
    btn.innerText = "Processing...";

    const result = await card.tokenize();

        if (result.status !== 'OK') {
            alert("Card tokenization failed.");
            btn.disabled = false;
            btn.innerText = "Pay with Card";
            return;
        }

    console.log("Square payment token:", result.token);

    // Send to backend
    const res = await fetch("/checkout/square/create", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            source_id: result.token,
            delivery_fee: deliveryFee,
            ...collectCustomerInfo()
        })
    });

    const data = await res.json();

    if (data.status === "success") {
        window.location.href = "/checkout/success/" + data.order_id;
    } else {
        console.error(data);
        alert("Square payment failed.");
        btn.disabled = false;
        btn.innerText = "Pay with Card";
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
        placeholder.innerHTML = `<div id="paypal-button-container"></div>`;

        if (typeof paypal === 'undefined') {
            console.error("PayPal SDK not loaded");
            return;
        }

        const orderTotal = parseFloat(document.getElementById('orderTotal').textContent.replace('£', '') || 0).toFixed(2);
        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch("{{ route('checkout.paypal.create') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    subtotal: subtotal,
                    delivery_fee: deliveryFee,
                    total: (subtotal + deliveryFee)
                })
            })
                .then(res => res.json())
                .then(data => data.orderID);
            },
            onApprove: function(data, actions) {
               // --- grab customer info safely ---
            const emailEl = document.querySelector('input[name="customer_email"]');
            const firstNameEl = document.querySelector('input[name="customer_first_name"]');
            const lastNameEl = document.querySelector('input[name="customer_last_name"]');
            const phoneEl = document.querySelector('input[name="customer_phone"]');
            const deliveryTypeEl = document.querySelector('select[name="delivery_type"]');
            
            const deliveryOptionSelect = document.getElementById('delivery_option_id');
            const pickupLocationSelect = document.querySelector('#pickup_container select[name="pickup_location_id"]');
            const pickupDateInput = document.getElementById('pickup_date');
            const pickupTimeSelect = document.querySelector('#pickup_container select[name="pickup_time"]');

            if (!emailEl || !firstNameEl || !lastNameEl || !phoneEl || !deliveryTypeEl) {
                console.error("Required checkout fields are missing");
                alert("Please fill in all required fields before completing the payment.");
                return;
            }

            const customerInfo = {
                customer_email: emailEl.value,
                customer_first_name: firstNameEl.value,
                customer_last_name: lastNameEl.value,
                customer_phone: phoneEl.value,
                delivery_type: deliveryTypeEl.value,

                delivery_address: document.querySelector('textarea[name="delivery_address"]')?.value ?? null,
                delivery_town: document.querySelector('textarea[name="delivery_town"]')?.value ?? null,
                delivery_postcode: document.querySelector('textarea[name="delivery_postcode"]')?.value ?? null,

                delivery_option_id: deliveryOptionSelect?.value ?? null,
                pickup_location_id: pickupLocationSelect?.value ?? null,
                pickup_date: pickupDateInput?.value ?? null,
                pickup_time: pickupTimeSelect?.value ?? null,

                delivery_fee: deliveryFee
            };

                return fetch("{{ route('checkout.paypal.capture') }}?order_id=" + data.orderID, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        order_id: data.orderID,
                        ...customerInfo
                    })
                })
                .then(res => res.json())
                .then(function(response) {
                if (!response.order_id) {
                    console.error("No order ID returned from server", response);
                    return;
                }
                window.location.href = "/checkout/success/" + response.order_id;
                });
            },
            onCancel: function() {
        console.log("Payment cancelled");
    }
        }).render('#paypal-button-container');
    }
});
</script>
@endpush
