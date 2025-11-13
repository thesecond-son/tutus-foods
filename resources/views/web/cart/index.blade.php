@extends('layouts.products')

@section('styles')
<style>
  :root {
    --brand-pink: #ff5887;
    --brand-dark-pink: #e04375;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
  }
  body {
    background: #fafafa;
  }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-20 px-5 md:px-8 grid md:grid-cols-3 gap-8">

  {{-- LEFT COLUMN: CART ITEMS --}}
  <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Your Cart</h1>

    @if (session('cart') && count(session('cart')))
      <div id="cartItems" class="divide-y divide-gray-100 space-y-4">
        @foreach (session('cart') as $id => $item)
          <div class="flex items-center justify-between pt-4" data-id="{{ $id }}" data-price="{{ $item['price'] }}">
            <div class="flex items-center gap-4">
              <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                   class="w-14 h-14 rounded-lg object-cover">
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ $item['name'] }}</p>
                <p class="text-xs text-gray-500 price">£{{ number_format($item['price'], 2) }}</p>
              </div>
            </div>

            <div class="flex items-center gap-3">
              {{-- Quantity control --}}
              <div class="flex items-center border border-gray-200 rounded-md">
                <button class="qty-dec px-2 text-gray-600 hover:text-[var(--brand-pink)]">−</button>
                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                       class="w-12 text-center py-1 text-sm border-x border-gray-200 focus:ring-2 focus:ring-[var(--brand-pink)] outline-none quantity">
                <button class="qty-inc px-2 text-gray-600 hover:text-[var(--brand-pink)]">+</button>
              </div>

              {{-- Remove Item --}}
              <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-item">
                @csrf
                @method('DELETE')
                <button class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-md text-xs font-medium hover:bg-red-500 hover:text-white transition">
                  Remove
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Clear Cart Button --}}
      <div class="text-right pt-6 border-t border-gray-100">
        <form action="{{ route('cart.clear') }}" method="POST">
          @csrf
          <button class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-md text-sm font-semibold transition">
            Clear Cart
          </button>
        </form>
      </div>

      @else
        <div class="text-center py-12 text-gray-500 space-y-4">
            <p>Your cart is empty.</p>
            <a href="/products"
            class="inline-block bg-[var(--brand-pink)] text-white px-5 py-2 rounded-lg font-semibold hover:bg-[var(--brand-pink-light)] transition">
            Keep Browsing
            </a>
        </div>
        @endif

  </div>

  {{-- RIGHT COLUMN: SUMMARY --}}
  @if (session('cart') && count(session('cart')))
  @php
      $subtotal = collect(session('cart'))->sum(fn($i) => $i['price'] * $i['quantity']);
  @endphp
  <div class="bg-[var(--brand-bg)] rounded-2xl border border-pink-100 shadow-inner p-6 md:p-8 h-fit space-y-6">
    <h3 class="text-xl font-semibold text-gray-800">Order Summary</h3>

    <div id="summary" class="space-y-3 border-t border-pink-100 pt-4">
      <div class="flex justify-between text-sm text-gray-700">
        <span>Subtotal</span>
        <span id="subtotal">£{{ number_format($subtotal, 2) }}</span>
      </div>
      <div class="flex justify-between text-sm text-gray-700">
        <span>Delivery</span>
        <span>£0.00</span>
      </div>
      <div class="flex justify-between text-base font-semibold text-gray-900">
        <span>Total</span>
        <span id="total">£{{ number_format($subtotal, 2) }}</span>
      </div>
    </div>

    {{-- Buttons --}}
    <div class="space-y-3 pt-4">
      <a href="/products"
         class="block text-center bg-white border border-gray-200 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">
        Continue Shopping
      </a>

      <a href="/checkout"
         class="block text-center bg-white border border-[var(--brand-pink)] text-[var(--brand-pink)] py-2 rounded-lg font-semibold hover:bg-[var(--brand-pink)] hover:text-white transition">
        Proceed to Checkout
      </a>
    </div>

    {{-- Secure Checkout Notice --}}
    <div class="flex items-center justify-center gap-2 pt-4 text-sm text-gray-600 border-t border-pink-100">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[var(--brand-pink)]">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4m-2 0h12v10H6V11z" />
      </svg>
      <span>Secure Checkout</span>
    </div>
  </div>
  @endif
</div>

{{-- ✅ Live Quantity + Remove Update Script --}}
<script>
/* ========= Quantity Update ========= */
document.querySelectorAll('.qty-inc, .qty-dec').forEach(btn => {
  btn.addEventListener('click', e => {
    const parent = e.target.closest('[data-id]');
    const input = parent.querySelector('.quantity');
    const price = parseFloat(parent.dataset.price);
    const id = parent.dataset.id;

    if (e.target.classList.contains('qty-inc')) input.stepUp();
    else if (e.target.classList.contains('qty-dec') && input.value > 1) input.stepDown();

    updateFrontendTotals();

    // quietly sync backend
    fetch(`/cart/update/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ quantity: input.value })
    }).catch(() => console.error('Cart update failed.'));
  });
});

document.querySelectorAll('.quantity').forEach(input => {
  input.addEventListener('change', () => {
    const id = input.closest('[data-id]').dataset.id;
    updateFrontendTotals();

    fetch(`/cart/update/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ quantity: input.value })
    }).catch(() => console.error('Cart update failed.'));
  });
});

/* ========= Remove Item ========= */
document.querySelectorAll('.remove-item').forEach(form => {
  form.addEventListener('submit', e => {
    e.preventDefault(); // stop normal form submit

    const parent = form.closest('[data-id]');
    const id = parent.dataset.id;

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: new FormData(form)
    })
    .then(() => {
      // Remove item visually
      parent.remove();
      updateFrontendTotals();

      // If cart is empty, show "empty" message
      if (document.querySelectorAll('[data-id]').length === 0) {
      document.getElementById('cartItems').innerHTML =
        '<div class="text-center py-12 text-gray-500 space-y-4">' +
        '<p>Your cart is empty.</p>' +
        '<a href="/products" class="inline-block bg-[var(--brand-pink)] text-white px-5 py-2 rounded-lg font-semibold hover:bg-[var(--brand-pink-light)] transition">Keep Browsing</a>' +
        '</div>';

      // Remove order summary
      document.querySelector('#summary')?.parentElement.remove();
      
      location.reload();

      // Remove "Clear Cart" button section if it exists
      const clearCartSection = document.querySelector('form[action*="cart.clear"]')?.closest('div.text-right');
      if (clearCartSection) clearCartSection.remove();
    }
    
    })
    .catch(() => console.error('Failed to remove item.'));
  });
});

/* ========= Recalculate Subtotal/Total ========= */
function updateFrontendTotals() {
  let subtotal = 0;
  document.querySelectorAll('[data-id]').forEach(el => {
    const qty = parseFloat(el.querySelector('.quantity').value);
    const price = parseFloat(el.dataset.price);
    subtotal += qty * price;
  });

  const subtotalEl = document.getElementById('subtotal');
  const totalEl = document.getElementById('total');

  if (subtotalEl && totalEl) {
    subtotalEl.textContent = '£' + subtotal.toFixed(2);
    totalEl.textContent = '£' + subtotal.toFixed(2);
  }
}
</script>

@endsection
