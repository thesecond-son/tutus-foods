@if(empty($cart))
    <p class="text-gray-500 text-center py-8">Your cart is empty 🍰</p>
@else
    <ul class="divide-y divide-gray-100 pb-36">
        @foreach($cart as $id => $item)
            <li class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/' . $item['image']) }}"
                             class="w-12 h-12 rounded-md object-cover" alt="{{ $item['name'] }}">
                    @endif
                    <div>
                        <p class="font-medium text-sm">{{ $item['name'] }}</p>
                        @if(!empty($item['size']) || !empty($item['color']))
                            <p class="text-xs text-gray-500">
                                {{ $item['size'] ?? '' }} {{ $item['color'] ?? '' }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-700">£{{ number_format($item['price'], 2) }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-end">
                    <span class="text-sm text-gray-800 font-semibold">×{{ $item['quantity'] }}</span>

                    <!-- Always visible delete button -->
                    <button 
                        onclick="removeFromCart('{{ $id }}')" 
                        class="mt-1 hover:text-red-600"
                        title="Remove item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </li>
        @endforeach
    </ul>

    <!-- Fixed Footer -->
    <div class="fixed bottom-0 right-0 w-80 bg-white border-t border-gray-100 p-4 space-y-3">
        <div class="text-right text-sm font-semibold text-gray-800">
            Total: £{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }}
        </div>

        <button onclick="clearCart()" 
                class="text-xs text-red-600 hover:text-red-800">
            Clear Cart
        </button>

        <a href="/cart" 
           class="block text-center bg-[var(--brand-pink)] text-white py-2 rounded-lg font-semibold hover:bg-[var(--brand-pink-light)] transition">
            View Full Cart
        </a>

        <a href="/checkout" 
            class="block text-center bg-white border border-[var(--brand-pink)] text-[var(--brand-pink)] py-2 rounded-lg font-semibold hover:bg-[var(--brand-pink)] hover:text-white transition">
                Checkout
            </a>


    </div>
@endif
