<div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Product Image -->
    <div>
      <img 
        src="{{ asset('storage/' . $product->image_path) }}" 
        alt="{{ $product->name }}" 
        class="w-full rounded-xl object-cover"
      >
    </div>

    <!-- Product Details -->
    <div class="space-y-4">
      <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
      <p class="text-[#ff5887] text-xl font-semibold">£{{ number_format($product->base_price, 2) }}</p>

      <p class="text-gray-700 leading-relaxed">{{ $product->description ?? 'No description available.' }}</p>

      @if($product->product_options && count($product->product_options))
        <div>
          <h3 class="text-sm font-semibold text-gray-800 mb-1">Options</h3>
          <ul class="space-y-1 text-sm text-gray-600">
            @foreach($product->product_options as $option)
              <li>• {{ $option->name }} - £{{ number_format($option->price, 2) }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pt-4">
        @csrf

        @if ($product->inventory > 0)
        <button 
          type="submit"
          class="w-full bg-[#ff5887] hover:bg-[#ff4073] text-white font-semibold py-3 rounded-lg transition"
        >
          Add to Cart
        </button>
        @else
        <button 
          type="button"
          class="w-full bg-gray-400 text-white font-semibold py-3 rounded-lg cursor-not-allowed"
          disabled>
        Out of Stock
        </button>
      </form>
    </div>
  </div>
</div>
