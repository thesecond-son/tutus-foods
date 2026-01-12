<div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md overflow-hidden transition-all duration-500">
          
          <!-- Product Image -->
          <img src="{{ asset('storage/' . $product->image_path) }}"
               loading="lazy" decoding="async" fetchpriority="low"
               alt="{{ $product->name }} – handcrafted baked product from Tutu’s Foods"
               class="w-full aspect-[4/5] object-cover rounded-t-2xl transition-transform duration-700 ease-out group-hover:scale-105">

          <!-- Hover Icons -->
          <div class="absolute inset-0 flex items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            <!-- View -->
            <button onclick="openProductModal('{{ route('products.show', $product->slug) }}')"
                    class="bg-white rounded-full p-3 shadow text-[var(--brand-pink)] hover:bg-[var(--brand-pink)] hover:text-white transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>

            <!-- Add to Cart -->
            <button
              onclick="
                @if ($product->product_options && count($product->product_options))
                  openProductModal('{{ route('products.show', $product->slug) }}')
                @else
                  document.getElementById('directAddForm{{ $product->id }}').submit()
                @endif
              "
              class="bg-white rounded-full p-3 shadow text-[var(--brand-pink)] hover:bg-[var(--brand-pink)] hover:text-white transition">
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
            <p class="text-[var(--brand-pink)] font-semibold text-sm">£{{ number_format($product->base_price, 2) }}</p>
          </div>

          <!-- Accent line -->
          <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-[var(--brand-pink)]/50 via-[#ff88a6]/50 to-[var(--brand-pink)]/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>