<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Tutu’s Foods') }}</title>

    <!-- Tailwind CSS -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
     <!-- Vite Scripts & Styles -->
     @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Production build fallback -->
        <link rel="stylesheet" href="{{ secure_asset('build/assets/app.css') }}">
        <script type="module" src="{{ secure_asset('build/assets/app.js') }}"></script>
    @endif


    @yield('styles')

    <!-- Slider.js -->
    <script src="https://cdn.jsdelivr.net/npm/slider-js@1.0.1/dist/slider.min.js" defer></script>

    <style>
        .overlay {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-800">

<header>
  <nav class="w-full bg-white shadow-sm fixed top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3">
      
      <!-- Logo -->
      <a href="/" class="flex items-center space-x-2">
        <img src="{{ asset('images/logo.webp') }}" alt="Tutu's Foods Logo" class="h-10 w-auto">
      </a>

      <!-- Desktop Menu + Cart -->
      <div class="hidden md:flex items-center space-x-8">
        <a href="/" class="text-gray-800 hover:text-pink-500 font-medium border-b-2 border-transparent hover:border-pink-500">Home</a>
        <a href="/about" class="text-gray-800 hover:text-pink-500 font-medium border-b-2 border-transparent hover:border-pink-500">About Us</a>
        <a href="/blog" class="text-gray-800 hover:text-pink-500 font-medium border-b-2 border-transparent hover:border-pink-500">Blog</a>

        <!-- Cart Icon (desktop) -->
        <a href="/cart" id="cartIcon" class="relative text-gray-600 hover:text-pink-500 transition ml-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 1.293A1 1 0 007 16h10" />
          </svg>

          @php
            $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0;
          @endphp

          @if($cartCount > 0)
            <span id="cartCount"
              class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ $cartCount }}
            </span>
          @endif
        </a>
      </div>

      <!-- Mobile Right Section -->
      <div class="flex items-center space-x-4 md:hidden">
        <!-- Cart Icon (mobile) -->
        <a href="/cart" id="cartIconMobile" class="relative text-gray-600 hover:text-pink-500 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 1.293A1 1 0 007 16h10" />
          </svg>

          @if($cartCount > 0)
            <span id="cartCountMobile"
              class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ $cartCount }}
            </span>
          @endif
        </a>

        <!-- Hamburger -->
        <button id="menuToggle" class="text-gray-700 hover:text-pink-500 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <!-- Mobile Slide-in Menu -->
  <div id="mobileMenu"
    class="fixed top-0 right-0 h-full w-64 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden">
    <div class="p-6 flex flex-col space-y-6">
      <button id="closeMenu" class="self-end text-gray-600 hover:text-pink-500 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <a href="/" class="text-gray-800 hover:text-pink-500 font-medium border-b border-gray-100 pb-2">Home</a>
      <a href="/about" class="text-gray-800 hover:text-pink-500 font-medium border-b border-gray-100 pb-2">About Us</a>
    </div>
  </div>

  <!-- Overlay -->
  <div id="menuOverlay" class="hidden fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden"></div>

  <script>
    const menuToggle = document.getElementById('menuToggle');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');

    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.remove('translate-x-full');
      menuOverlay.classList.remove('hidden');
    });

    closeMenu.addEventListener('click', () => {
      mobileMenu.classList.add('translate-x-full');
      menuOverlay.classList.add('hidden');
    });

    menuOverlay.addEventListener('click', () => {
      mobileMenu.classList.add('translate-x-full');
      menuOverlay.classList.add('hidden');
    });
  </script>
</header>


    <!-- Page Content -->
   <main>
        @yield('content')
   </main>

   <!-- CART SIDEBAR -->
<div id="cartSidebar" class="fixed right-0 top-0 h-full w-80 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300">
  <div class="p-4 flex justify-between items-center border-b">
    <h3 class="text-lg font-semibold text-gray-900">Your Cart</h3>
    <button onclick="toggleCartSidebar()" class="text-gray-600 hover:text-pink-500">
      ✕
    </button>
  </div>
  <div id="cartItemsContainer" class="p-4 overflow-y-auto max-h-[calc(100vh-140px)] text-sm text-gray-700">
    Loading cart...
  </div>
  <!-- <div class="p-4 border-t space-y-2">
    <a href="/cart" class="block text-center bg-pink-500 text-white py-2 rounded-lg font-semibold hover:bg-pink-600 transition">
      View Full Cart
    </a>
  </div> -->
</div>

<!-- Overlay -->
<div id="cartOverlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="toggleCartSidebar()"></div>

<script>
function toggleCartSidebar(forceOpen = null) {
  const sidebar = document.getElementById('cartSidebar');
  const overlay = document.getElementById('cartOverlay');

  const isOpen = sidebar.classList.contains('translate-x-0');
  const shouldOpen = forceOpen !== null ? forceOpen : !isOpen;

  if (shouldOpen) {
    sidebar.classList.remove('translate-x-full');
    sidebar.classList.add('translate-x-0');
    overlay.classList.remove('hidden');
    updateCartSidebar();
  } else {
    sidebar.classList.remove('translate-x-0');
    sidebar.classList.add('translate-x-full');
    overlay.classList.add('hidden');
  }
}

// Fetch and display cart items
async function updateCartSidebar() {
  const container = document.getElementById('cartItemsContainer');
  container.innerHTML = 'Loading...';
  try {
    const response = await fetch('/cart/summary');
    const html = await response.text();
    container.innerHTML = html;
  } catch {
    container.innerHTML = '<p class="text-center text-gray-500">Failed to load cart.</p>';
  }
}

// Update the small cart count badge
async function updateCartCount() {
  try {
    const response = await fetch('/cart/count');
    const data = await response.json();
    document.getElementById('cartCount').textContent = data.count ?? 0;
  } catch (e) {
    console.error('Cart count update failed', e);
  }
}

// Attach click event to icon
document.getElementById('cartIcon').addEventListener('click', e => {
  e.preventDefault();
  toggleCartSidebar(true);
});

document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
});

async function removeFromCart(id) {
  try {
    const response = await fetch(`/cart/remove/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
    });

    if (!response.ok) throw new Error('Failed to remove item');

    // Force fetch the new cart summary directly from the server
    const summaryResponse = await fetch('/cart/summary', { cache: 'no-store' });
    const html = await summaryResponse.text();

    document.getElementById('cartItemsContainer').innerHTML = html;
    updateCartCount();

  } catch (error) {
    console.error('Error removing from cart:', error);
  }
}

async function clearCart() {
  try {
    const response = await fetch('/cart/clear', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
    });

    if (!response.ok) throw new Error('Failed to clear cart');

    const summaryResponse = await fetch('/cart/summary', { cache: 'no-store' });
    const html = await summaryResponse.text();

    document.getElementById('cartItemsContainer').innerHTML = html;
    updateCartCount();

  } catch (error) {
    console.error('Error clearing cart:', error);
  }
}

</script>


   @stack('scripts')


<!-- FOOTER SECTION -->
<footer id="footer" class="bg-white py-12 px-6 md:px-12 border-t border-gray-100">
  <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8 items-start">

    <!-- Left Image -->
    <div class="rounded-lg overflow-hidden h-[250px]">
      <img
        src="{{ asset('images/footer-bread.webp') }}"
        alt="Bread"
        class="w-full h-full object-cover rounded-lg"
      >
    </div>

    <!-- Middle Column -->
    <div class="bg-[#ffe4ea] rounded-lg p-6 flex flex-col justify-between min-h-[250px] fade-up soft-card">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reach Out</h3>
        <ul class="space-y-3 text-sm text-gray-800">
          <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#ff5887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3.6 7.59a1 1 0 01-.36 1.32l-1.9 1.13a11.05 11.05 0 005.17 5.17l1.13-1.9a1 1 0 011.32-.36L19 19v2a1 1 0 01-1 1A17 17 0 013 5z"/>
            </svg>
            <span>+44 (0)20 3289 4678</span>
          </li>
          <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#ff5887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-4-4m4 4l-4 4m12 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>info@tutuscupcakes.com</span>
          </li>
          <li>
            Tutu’s Cupcake Studio, Unit 5,<br>
            Foundry Business Park, Station Approach,<br>
            Hockley SS5 4HS
          </li>
        </ul>
      </div>
    </div>

    <!-- Right Column -->
    <div class="bg-[#ffe4ea] rounded-lg p-6 flex flex-col justify-between min-h-[250px] fade-up soft-card">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Opening Hours</h3>
        <ul class="text-sm text-gray-800 space-y-2">
          <li>Monday to Friday - 9:30am to 5:30pm</li>
          <li>Saturday - 10am to 1pm</li>
          <li>Sunday – Closed</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bottom Section -->
  <div class="max-w-6xl mx-auto mt-10 bg-[#ffe4ea] rounded-lg p-6 flex flex-col md:flex-row items-center justify-between text-center md:text-left soft-card fade-up">
    <div class="mb-4 md:mb-0">
      <img src="{{ asset('images/logo.webp') }}" alt="Tutu's Foods Logo" class="h-16 mx-auto md:mx-0">
    </div>

    <div class="text-sm text-gray-800 space-y-2">
      <div class="flex flex-wrap justify-center md:justify-end gap-4 font-medium">
        <a href="/about" class="hover:text-[#ff5887] transition">About</a>
        <a href="/blog" class="hover:text-[#ff5887] transition">Blog</a>
        <a href="/contact" class="hover:text-[#ff5887] transition">Contact Us</a>
      </div>
      <div class="flex flex-wrap justify-center md:justify-end gap-4 text-gray-800 text-sm font-medium">
        <a href="/privacy-policy" class="hover:text-[#ff5887] transition">Privacy Policy</a>
        <a href="/refund-policy" class="hover:text-[#ff5887] transition">Refund Policy</a>
      </div>
      <p class="text-gray-500 text-xs mt-2">© Tutu’s Cupcakes 2025</p>
    </div>
  </div>
</footer>


<script>
function triggerCartAnimation() {
  const cartIcon = document.getElementById('cartIcon');
  if (!cartIcon) return;

  cartIcon.classList.add('animate-bounce');
  setTimeout(() => {
    cartIcon.classList.remove('animate-bounce');
  }, 600);
}

/* Lightweight reveal-on-scroll for fade-up elements (minimal JS) */
(function() {
  const els = document.querySelectorAll('.fade-up');
  if (!els.length) return;
  const reveal = () => {
    els.forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight * 0.85) el.classList.add('visible');
    });
  };
  window.addEventListener('scroll', reveal, { passive: true });
  window.addEventListener('resize', reveal);
  window.addEventListener('load', reveal);
  reveal();
})();
</script>
</script>

</body>
</html>
