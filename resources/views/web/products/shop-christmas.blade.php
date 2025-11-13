@extends('layouts.products')

@section('content')
<style>
:root {
  --brand-pink: #ff5887;
  --brand-pink-light: #ff88a6;
  --brand-light-bg: #fff8f9;
}

/* Christmas Background */
#shop-christmas-page {
  background: linear-gradient(to bottom, #fff0f5, #ffe4e1);
  position: relative;
  overflow: hidden;
}

/* Floating snowflakes */
@keyframes float-snow {
  0% { transform: translateY(-20px) rotate(0deg); opacity: 0.8; }
  50% { transform: translateY(50vh) rotate(180deg); opacity: 0.4; }
  100% { transform: translateY(100vh) rotate(360deg); opacity: 0.8; }
}
.snowflake {
  position: absolute;
  font-size: 1.5rem;
  opacity: 0.6;
  animation: float-snow linear infinite;
}
.snowflake:nth-child(1) { left: 10%; animation-duration: 12s; }
.snowflake:nth-child(2) { left: 30%; animation-duration: 15s; }
.snowflake:nth-child(3) { left: 50%; animation-duration: 18s; }
.snowflake:nth-child(4) { left: 70%; animation-duration: 14s; }
.snowflake:nth-child(5) { left: 90%; animation-duration: 16s; }

/* Ornament behind products */
#christmas-ornament {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 300px;
  height: 300px;
  transform: translate(-50%, -50%);
  opacity: 0.1;
  z-index: 0;
  border-radius: 50%;
  background: radial-gradient(circle, #ffefef 0%, #ffcccc 100%);
  box-shadow: 0 0 60px #ffb6b6;
  animation: float-ornament 6s ease-in-out infinite alternate;
}
@keyframes float-ornament {
  0% { transform: translate(-50%, -50%) translateY(0); }
  50% { transform: translate(-50%, -50%) translateY(-10px); }
  100% { transform: translate(-50%, -50%) translateY(0); }
}

#productModalContent {
    scroll-behavior: smooth;
  }

  #productModalWrapper {
    transition: opacity 0.3s ease;
  }
  #productModalWrapper.hidden {
    opacity: 0;
    pointer-events: none;
  }
  #productModalWrapper.flex {
    opacity: 1;
  }
</style>

<section id="shop-christmas-page" class="pt-28 pb-16 px-6 md:px-10 min-h-screen">
  <!-- Ornament -->
  <div id="christmas-ornament"></div>

  <!-- Snowflakes -->
  <div class="snowflake">❄️</div>
  <div class="snowflake">❄️</div>
  <div class="snowflake">❄️</div>
  <div class="snowflake">❄️</div>
  <div class="snowflake">❄️</div>

  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
    <!-- Sidebar -->
    <aside id="filterSidebar"
           class="md:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-8 md:block hidden transition-all duration-300">
      <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Browse by</h2>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="{{ route('shop-christmas') }}"
               class="text-red-600 font-semibold">Shop Christmas</a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Collapse button mobile
    <button id="toggleSidebar"
            class="md:hidden flex items-center justify-center mb-4 py-2 px-4 rounded-lg bg-red-600 text-white text-sm font-semibold shadow-sm hover:bg-red-700 transition">
      Filter & Categories
    </button> -->

    <!-- Products Grid -->
    <div class="md:col-span-3">
      <div class="mb-10 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-bold text-red-700">Christmas Collection</h1>
        <p class="text-gray-600 text-sm mt-2">
          Enjoy our festive treats, seasonal specials, and holiday goodies.
        </p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($products as $product)
        <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md overflow-hidden transition-all duration-500">
          <!-- Product Image -->
          <img src="{{ asset('storage/' . $product->image_path) }}"
               alt="{{ $product->name }}"
               class="w-full aspect-[4/5] object-cover rounded-t-2xl transition-transform duration-700 ease-out group-hover:scale-105">

          <!-- Hover Icons -->
          <div class="absolute inset-0 flex items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            <button onclick="openProductModal('{{ route('products.show', $product->slug) }}')"
                    class="bg-white rounded-full p-3 shadow text-red-600 hover:bg-red-600 hover:text-white transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>

            <button
                           class="bg-white rounded-full p-3 shadow text-red-600 hover:bg-red-600 hover:text-white transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-1.6 8H19a1 1 0 001-1V6H6L5 3H3z" />
              </svg>
            </button>
            <form id="directAddForm{{ $product->id }}" method="POST" action="{{ route('cart.add', $product->id) }}" class="hidden">
              @csrf
            </form>
          </div>

          <!-- Product Info -->
          <div class="p-4 text-center">
            <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $product->name }}</h3>
            <p class="text-red-600 font-semibold text-sm">£{{ number_format($product->base_price, 2) }}</p>
          </div>
        </div>
        @empty
        <p class="text-gray-600">No Christmas products found.</p>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="mt-10">
        {{ $products->links() }}
      </div>
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

<script>
console.log("🧠 shop-christmas.js loaded.");

function openProductModal(url) {
    console.log("🔍 openProductModal triggered for:", url);

    const wrapper = document.getElementById('productModalWrapper');
    const content = document.getElementById('productModalContent');

    if (!wrapper || !content) {
        console.error("❌ Modal elements not found on page!");
        return;
    }

    // Show modal
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

            // Get product wrapper
            const productSection = doc.querySelector('div.bg-white');
            if (!productSection) {
                console.error("❌ Product section not found in fetched HTML.");
                content.innerHTML = '<div class="text-center py-10 text-red-500">Product not found.</div>';
                return;
            }

            content.innerHTML = '';
            content.appendChild(productSection);

            console.log("✅ Product section injected into modal.");

            // Setup Add to Cart form
            const form = productSection.querySelector('#addToCartForm');
            if (form) {
                console.log("✅ Add-to-Cart form found. Attaching submit handler.");
                const submitBtn = form.querySelector('button[type="submit"]');

                form.addEventListener('submit', async e => {
                    e.preventDefault();
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Adding...';

                    try {
                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: new FormData(form)
                        });

                        if (res.ok) {
                            console.log("✅ Added to cart via form.");
                            submitBtn.innerHTML = 'Added!';
                            submitBtn.classList.add('bg-green-500');

                            // ✅ Close modal after 0.8s
                      setTimeout(() => closeProductModal(), 800);

                        } else submitBtn.innerHTML = 'Try Again';
                    } catch (err) {
                        console.error(err);
                        submitBtn.innerHTML = 'Error';
                    } finally {
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = `Add to Cart | £${parseFloat(submitBtn.dataset.price || 0).toFixed(2)}`;
                            submitBtn.classList.remove('bg-green-500');
                        }, 1200);
                    }
                });
            }

            // Setup Add-to-Cart button (works for products with or without options)
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

            // Setup quantity increment/decrement for modal
            content.addEventListener('click', e => {
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
        })
        .catch(err => {
            console.error("❌ Failed to load product details:", err);
            content.innerHTML = '<div class="text-center py-10 text-red-500">Failed to load product details.</div>';
        });
}

function closeProductModal() {
  const wrapper = document.getElementById('productModalWrapper');
  const content = document.getElementById('productModalContent');
  if (!wrapper) return;
  wrapper.classList.add('hidden');
  wrapper.classList.remove('flex');
  document.documentElement.style.overflow = '';
  document.body.style.overflow = '';
  if (content) content.innerHTML = '';
}

document.addEventListener('DOMContentLoaded', () => {
  const closeBtn = document.getElementById('productModalClose');
  const wrapper = document.getElementById('productModalWrapper');

  if (closeBtn) closeBtn.addEventListener('click', closeProductModal);
  if (wrapper) wrapper.addEventListener('click', e => { if (e.target === e.currentTarget) closeProductModal(); });
});
</script>



@endsection
