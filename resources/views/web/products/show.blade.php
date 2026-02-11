<style>
/* Remove number input arrows */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] { -moz-appearance: textfield; }
</style>

<!-- Product Quick View Content -->
<div class="bg-white">
  <!-- Image -->
  <div class="bg-gray-50">
    <img src="{{ asset('storage/' . $product->image_path) }}"
         alt="{{ $product->name }}"
         class="w-full aspect-[4/3] object-cover rounded-t-2xl">
  </div>

  <!-- Product Details -->
  <div class="p-8 space-y-5">
    <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>

    @if ($product->allergens)
      <p class="text-sm text-gray-600">
        <span class="font-semibold text-gray-800">Allergens:</span> {{ $product->allergens }}
      </p>
    @endif

    <p class="text-gray-700 text-sm leading-relaxed">{{ $product->description }}</p>

    <form id="addToCartForm" action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-6 pt-4">
  @csrf

  @if (!empty($product->product_options) && count($product->product_options))
    @foreach ($product->product_options ?? [] as $option)
      <div>
        <label class="block font-semibold text-gray-800 mb-2">{{ $option['name'] }}</label>
        <select name="options[{{ $option['name'] }}]" required
          class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#ff5887] focus:border-[#ff5887]">
          <option value="">Select {{ strtolower($option['name']) }}</option>
          @foreach ($option['values'] ?? [] as $value)
            <option value="{{ $value['value'] }}">{{ $value['value'] }}</option>
          @endforeach
        </select>
      </div>
    @endforeach
  @endif

  <!-- Quantity Selector (always show) -->
  <div class="flex items-center space-x-3">
    <span class="font-semibold text-gray-800">Quantity:</span>
    <div class="quantity-group inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
      <button type="button" class="qty-dec px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">−</button>
      <input type="number" name="quantity" value="1" min="1" step="1"
           class="qty-input w-12 text-center border-l border-r border-gray-300 outline-none">
      <button type="button" class="qty-inc px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">+</button>
    </div>
  </div>

  <!-- Add to Cart Button (always show) -->
  @if ($product->inventory > 0)
  <button
    type="button"
    class="add-to-cart-btn w-full bg-[#ff5887] hover:bg-[#ff4073] text-white font-semibold py-3 rounded-lg text-lg transition"
    data-product-id="{{ $product->id }}"
    data-price="{{ $product->base_price }}">
    Add to Cart | £{{ number_format($product->base_price, 2) }}
  </button>
  @else
  <button
    type="button"
    class="w-full bg-gray-400 text-white font-semibold py-3 rounded-lg cursor-not-allowed text-lg"
    disabled>
    Out of Stock
  </button>
  @endif
</form>

  </div>
</div>