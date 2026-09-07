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
          class="product-variant-select w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#ff5887] focus:border-[#ff5887]">
          <option value="">Select {{ strtolower($option['name']) }}</option>
          @foreach ($option['values'] ?? [] as $value)
            <option value="{{ $value['value'] }}">{{ $value['value'] }}</option>
          @endforeach
        </select>
        <p class="variant-required-error text-red-600 text-xs mt-1 hidden">Please select a {{ strtolower($option['name']) }}.</p>
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

<script>
(function () {
    // ✅ Self-contained variant enforcement + quantity controls for this
    // product's markup. Note: this <script> only executes when this page
    // is loaded as a normal navigation (e.g. visited directly). When this
    // fragment is instead fetched and injected into a modal via
    // DOMParser + appendChild elsewhere, browsers intentionally do not
    // execute scripts inserted that way — that flow relies on the parent
    // page's own listener (in products_index.blade.php) for the same
    // behavior, so both paths stay covered.
    const root = document.currentScript.previousElementSibling?.closest('.bg-white') || document;
    const form = root.querySelector('#addToCartForm') || document.getElementById('addToCartForm');
    if (!form) return;

    const btn = form.querySelector('.add-to-cart-btn');
    const optionSelects = form.querySelectorAll('.product-variant-select');
    const qtyInput = form.querySelector('.qty-input');

    function validateOptions(showErrors) {
        let allValid = true;
        optionSelects.forEach(select => {
            const err = select.parentElement.querySelector('.variant-required-error');
            if (!select.value) {
                allValid = false;
                if (showErrors) {
                    select.classList.add('border-red-500');
                    err?.classList.remove('hidden');
                }
            } else {
                select.classList.remove('border-red-500');
                err?.classList.add('hidden');
            }
        });
        return allValid;
    }

    if (btn && optionSelects.length) {
        // Start disabled until every variant is chosen
        if (!validateOptions(false)) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        optionSelects.forEach(select => {
            select.addEventListener('change', () => {
                const valid = validateOptions(false);
                btn.disabled = !valid;
                btn.classList.toggle('opacity-50', !valid);
                btn.classList.toggle('cursor-not-allowed', !valid);
            });
        });
    }

    if (btn) {
        btn.addEventListener('click', async () => {
            if (optionSelects.length && !validateOptions(true)) {
                const firstMissing = form.querySelector('.product-variant-select.border-red-500');
                firstMissing?.reportValidity?.() ?? firstMissing?.focus();
                firstMissing?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const options = {};
            optionSelects.forEach(select => {
                const key = select.name.match(/\[(.*)\]/)?.[1];
                if (key && select.value) options[key] = select.value;
            });

            const quantity = parseInt(qtyInput?.value) || 1;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                || form.querySelector('input[name="_token"]')?.value;

            btn.disabled = true;
            const originalLabel = btn.innerHTML;
            btn.innerHTML = 'Adding...';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ quantity, options })
                });

                if (res.ok) {
                    btn.innerHTML = 'Added!';
                    btn.classList.add('bg-green-500');
                } else {
                    let message = 'Something went wrong. Please try again.';
                    try {
                        const data = await res.json();
                        if (data?.message) message = data.message;
                    } catch (_) {}
                    alert(message);
                    btn.innerHTML = originalLabel;
                }
            } catch (err) {
                console.error(err);
                alert('Something went wrong. Please try again.');
                btn.innerHTML = originalLabel;
            } finally {
                setTimeout(() => {
                    btn.disabled = !validateOptions(false);
                    btn.classList.remove('bg-green-500');
                    btn.innerHTML = originalLabel;
                }, 1200);
            }
        });
    }

    form.querySelectorAll('.qty-inc, .qty-dec').forEach(qtyBtn => {
        qtyBtn.addEventListener('click', () => {
            const input = qtyBtn.closest('.quantity-group')?.querySelector('input[type="number"]');
            if (!input) return;
            const step = Number(input.step) || 1;
            const min = Number(input.min) || 1;
            let val = Number(input.value) || 1;
            val = qtyBtn.classList.contains('qty-inc') ? val + step : Math.max(val - step, min);
            input.value = val;
        });
    });
})();
</script>