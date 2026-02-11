@extends('layouts.products')

@section('title', "Products - Tutu's Foods")

@section('meta_description', "Shop handcrafted baked goods, sourdough, festive treats, and pantry favourites from Tutu’s Foods.")

@section('content')

@include('web.partials.products-styles')


<section class="pt-28 pb-16 px-6 md:px-10 bg-[var(--brand-light-bg)] min-h-screen">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">

  <h2 class="sr-only">Product Categories</h2>
    <!-- =============== SIDEBAR =============== -->
    <aside id="filterSidebar"
           class="md:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-8 md:block hidden transition-all duration-300">
      <!-- Browse by -->
      <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Browse by</h2>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="{{ route('products.index') }}"
               class="{{ request()->routeIs('products.index') ? 'text-[var(--brand-pink)] font-semibold' : 'text-gray-700 hover:text-[var(--brand-pink)]' }}">
              All Products
            </a>
          </li>
          @foreach ($categories->where('slug', '!=', 'shop-christmas') as $category)
          <li>
            <a href="{{ route('products.category', $category->slug) }}"
               class="{{ isset($currentCategory) && $currentCategory->id === $category->id ? 'text-[var(--brand-pink)] font-semibold' : 'text-gray-700 hover:text-[var(--brand-pink)]' }}">
              {{ $category->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      <!-- Filter by Price -->
      <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter by</h2>
        <form action="{{ url()->current() }}" method="GET" class="space-y-4 text-sm" rel="nofollow">
          <div>
            <label for="price" class="block mb-1 text-gray-600">Max Price (£)</label>
            <input id="price" name="price" type="range" min="3" max="100" value="{{ request('price', 100) }}" class="w-full accent-[var(--brand-pink)]" oninput="this.nextElementSibling.value = this.value">
            <output class="block text-center text-sm text-gray-700 mt-1">{{ request('price', 100) }}</output>
            <div class="flex justify-between text-xs text-gray-500">
              <span>£3</span>
              <span>£100</span>
            </div>
          </div>
          <button type="submit" class="mt-2 bg-[var(--brand-pink)] hover:bg-[#ff729b] text-white text-sm font-semibold px-4 py-2 rounded-lg w-full transition">
            Apply Filter
          </button>
        </form>
      </div>
    </aside>

    <!-- COLLAPSE BUTTON (mobile only) -->
    <button id="toggleSidebar"
            class="md:hidden flex items-center justify-center mb-4 py-2 px-4 rounded-lg bg-[var(--brand-pink)] text-white text-sm font-semibold shadow-sm hover:bg-[#ff729b] transition">
      Filter & Categories
    </button>

    <!-- =============== MAIN PRODUCT GRID =============== -->
    <div class="md:col-span-3">
      <!-- Header -->
      <div class="mb-10 text-center md:text-left">
      @foreach ($stackedCategories as $category)
        <section class="mb-16">
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
            {{ $category->name }}
            </h2>
            @if ($category->description)
            <p class="text-gray-600 text-sm mt-2">
                {{ $category->description }}
            </p>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($category->products as $product)
            @include('web.partials.product-card', ['product' => $product])
            @empty
            <p class="text-gray-600">No products found.</p>
            @endforelse
        </div>
        </section>
        @endforeach

    </div>
  </div>
</section>

<!-- =============== PRODUCT MODAL (CLEAN + FIXED) =============== -->
<div id="productModalWrapper"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70">
  <div id="productModalBox"
       class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-hidden">
    <!-- Close button -->
    <button id="productModalClose"
            aria-label="Close modal"
            class="absolute top-3 right-3 bg-gray-100 hover:bg-gray-200 rounded-full p-2 z-20">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24"
           stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Scrollable modal content -->
    <div id="productModalContent"
         class="p-6 overflow-y-auto max-h-[90vh]">
    </div>
  </div>
</div>


@push('scripts')
<script defer>
/* ===== Modal open/close + outside-click + quantity delegation + Add-to-Cart animation ===== */
function openProductModal(url) {
    const wrapper = document.getElementById('productModalWrapper');
    const content = document.getElementById('productModalContent');

    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

    wrapper.classList.remove('hidden');
    wrapper.classList.add('flex');

    content.innerHTML = '<div class="text-center py-20 text-gray-400">Loading...</div>';

    fetch(url)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            // Get product section
            const productSection = doc.querySelector('div.bg-white');
            if (!productSection) {
                content.innerHTML = '<div class="text-center py-10 text-red-500">Product not found.</div>';
                return;
            }

            content.innerHTML = '';
            content.appendChild(productSection);

            // Setup Add-to-Cart button(s)
            content.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const productId = btn.dataset.productId;
        if (!productId) return console.error("Product ID not found!");

        // Find quantity input in modal (default to 1 if not found)
        let quantity = 1;
        const qtyInput = content.querySelector('input[name="quantity"]');
        if (qtyInput) quantity = parseInt(qtyInput.value) || 1;

        btn.disabled = true;
        btn.innerHTML = 'Adding...';

        try {
            const res = await fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });

            if (res.ok) {
                console.log("✅ Added to cart via button.");
                btn.innerHTML = 'Added!';
                btn.classList.add('bg-green-500');

                // Close modal after 0.8s
                setTimeout(() => closeProductModal(), 800);
            } else {
                btn.innerHTML = 'Try Again';
            }
        } catch (err) {
            console.error(err);
            btn.innerHTML = 'Error';
        } finally {
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = `Add to Cart | £${parseFloat(btn.dataset.price || 0).toFixed(2)}`;
                btn.classList.remove('bg-green-500');
            }, 1200);
        }
    });
});
        })
        .catch(() => {
            content.innerHTML = '<div class="text-center py-10 text-red-500">Failed to load product details.</div>';
        });
}

function closeProductModal() {
    const wrapper = document.getElementById('productModalWrapper');
    const content = document.getElementById('productModalContent');

    wrapper.classList.add('hidden');
    wrapper.classList.remove('flex');

    document.documentElement.style.overflow = '';
    document.body.style.overflow = '';

    if (content) content.innerHTML = '';
}

// Modal close events
document.getElementById('productModalClose').addEventListener('click', closeProductModal);
document.getElementById('productModalWrapper').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeProductModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProductModal();
});

// Delegated quantity toggle
document.getElementById('productModalWrapper').addEventListener('click', function(e) {
    const inc = e.target.closest('.qty-inc');
    const dec = e.target.closest('.qty-dec');
    const target = inc || dec;
    if (!target) return;

    const group = target.closest('.quantity-group');
    if (!group) return;

    const input = group.querySelector('input[type="number"]');
    if (!input) return;

    const step = Number(input.step) || 1;
    const min = Number(input.min) || 1;
    let val = Number(input.value) || 1;

    if (inc) val += step;
    if (dec) val = Math.max(val - step, min);

    input.value = val;
    input.dispatchEvent(new Event('change', { bubbles: true }));
});

@if(!empty($openProduct))
document.addEventListener('DOMContentLoaded', function () {
    openProductModal("{{ route('products.show', $openProduct->slug) }}");
});
@endif
</script>

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    @foreach($products as $index => $product)
    {
      "@type": "Product",
      "position": {{ $index + 1 }},
      "name": "{{ $product->name }}",
      "image": "{{ asset('storage/'.$product->image_path) }}",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "GBP",
        "price": "{{ $product->base_price }}"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endverbatim
@endpush