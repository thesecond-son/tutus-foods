@extends('layouts.app')

@section('title', "Home - Tutu's Foods")

@section('meta_description', 'Order handcrafted sourdough, festive treats, and signature baked goods from Tutu’s Foods.')

@section('content')

@include('web.partials.home-styles')

<!-- HERO SECTION -->
<section id="hero" class="relative w-full h-[90vh] md:h-screen overflow-hidden">

  <!-- Background Slides -->
  <div class="absolute inset-0">
    @foreach($homepage->hero['slides'] as $index => $slide)
      <img src="{{ asset('storage/'.$slide['image']) }}" fetchpriority="high"
           class="slide {{ $index === 0 ? 'active' : '' }}"
           alt="Freshly baked bread by Tutu’s Foods">
    @endforeach
  </div>

  <!-- Softer Overlay -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/20 to-black/30 z-10"></div>

  <!-- Text content -->
  <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-4 md:px-8">
    <div class="max-w-3xl w-full px-6 py-10 md:px-12 md:py-14 rounded-3xl bg-white/15 border border-white/10 shadow-xl space-y-6 opacity-0 animate-fadeIn">
      <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight">
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--brand-pink)] to-[var(--brand-pink-light)]">
        {{ $homepage->hero['title'] }}
        </span>
      </h1>

      <p class="text-base md:text-lg text-gray-100 font-bold max-w-xl mx-auto leading-relaxed">
      {{ $homepage->hero['subtitle'] }}
      </p>

      <a href="{{ url('/products') }}"
         class="inline-flex items-center justify-center bg-[var(--brand-pink)] hover:bg-[#e04375] text-white font-semibold px-8 py-3 rounded-full shadow-md transition-transform transform hover:scale-105 duration-300">
         {{ $homepage->hero['button_text'] }}
      </a>
    </div>
  </div>

</section>

<h2 class="sr-only">Product Collections</h2>

<!-- COLLECTION SECTION -->
<section id="collections" class="bg-[var(--brand-bg)] py-12 md:py-16 px-6">
  <div class="max-w-6xl mx-auto text-center">
  <h2 class="text-3xl md:text-4xl font-bold mb-14 text-gray-900 tracking-tight fade-up">
      Our Best Sellers
    </h2>  
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8 md:gap-10">
    @foreach($homepage->favourites as $item)
      <a href="{{ $item['url'] }}" class="circle-card flex flex-col items-center text-center fade-up group">
        <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy" decoding="async"
             class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover mb-3 border-4 border-white shadow-sm 
                      transition-transform duration-300 group-hover:scale-110">
        <h3 class="text-base md:text-lg font-semibold text-gray-700 transition-colors group-hover:text-[var(--brand-pink)]">
          {{ $item['title'] }}
        </h3>
      </a>
    @endforeach
    </div>
  </div>
</section>


<!-- SIGNATURE BRANDS SECTION -->
<section id="featured" class="bg-white py-20 md:py-28 px-6">
  <div class="max-w-7xl mx-auto text-center">
    <h2 class="text-3xl md:text-4xl font-bold mb-14 text-gray-900 tracking-tight fade-up">
      Our Signature Brands
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 xl:gap-14">
    @foreach($homepage->signature_brands as $brand)
        <div class="group relative h-[450px] rounded-2xl shadow-lg overflow-hidden">
        @if(!empty($brand['is_live']) && !empty($brand['slug']))
          <a href="{{ route('products.category', $brand['slug']) }}"
            class="absolute inset-0 z-20"></a>
        @endif

        <!-- Image -->
        <img src="{{ asset('storage/'.$brand['image']) }}" loading="lazy" decoding="async" alt="{{ $brand['title'] }}"
        class="w-full h-full object-cover group-hover:scale-105 transition">

        <!-- Gradient overlay to soften the lower section -->
        <!-- <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div> -->

        <!-- Fixed-height info panel -->
        <div class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm p-6 md:p-7 text-left flex flex-col justify-between h-40 rounded-b-2xl">
          <div>
            <h3 class="text-xl font-semibold">{{ $brand['title'] }}</h3>
            <p class="text-sm text-gray-600">{{ $brand['description'] }}</p>
          </div>

          @if($brand['is_live'])
              <span class="text-[var(--brand-pink)] font-semibold mt-3 inline-block">
                View More →
              </span>
          @else
            <span class="inline-block mt-4 text-gray-400 text-sm font-medium tracking-wide">
              Coming Soon
            </span>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


@if($homepage->christmas_section['enabled'] ?? false)
<!-- CHRISTMAS SHOP SECTION -->
<section id="shop-christmas" class="bg-red-50 py-24 px-6 relative overflow-hidden">
  <div class="absolute inset-0 pointer-events-none">
    <!-- Snowflakes in the background -->
    <img src="{{ asset('images/snowflakes.webp') }}" alt="Snowflakes"
         class="absolute inset-0 w-full h-full object-cover opacity-20 z-0">

    <!-- Ornament on top -->
    <img src="{{ asset('images/ornaments.webp') }}" alt="Christmas Ornament"
         class="absolute inset-0 w-full m-auto opacity-20 z-10">
  </div>

  <div class="max-w-3xl mx-auto text-center relative z-10 space-y-6">
    <h2 class="text-4xl md:text-5xl font-bold text-red-700 section-title">
      {{ $homepage->christmas_section['title'] ?? 'Celebrate the Season with Tutu\'s Foods' }}
    </h2>
    <p class="text-gray-700 text-lg md:text-xl">
      {{ $homepage->christmas_section['description'] ?? 'Discover handcrafted festive treats, seasonal delights, and holiday specials to make your Christmas extra joyful and delicious.' }}
    </p>

    <a href="{{ $homepage->christmas_section['button_link'] ?? route('shop-christmas') }}"
       class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-4 rounded-full shadow-md text-lg transition-transform transform hover:scale-105 duration-300">
      {{ $homepage->christmas_section['button_text'] ?? 'Shop Christmas Collection' }}
    </a>
  </div>
</section>
@endif

<!-- ORDER CUT-OFF SECTION -->
<section id="order-times" class="bg-gradient-to-b from-[var(--brand-bg)] to-[#ffe4ea] py-20 px-6 text-center">
  <div class="max-w-3xl mx-auto fade-up">
    <div class="soft-card p-8 sm:p-10">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">
        Ready to Get Your Orders?
      </h2>

      <p class="text-gray-700 text-base md:text-lg font-medium mb-4">
      {{ $homepage->order_cutoff_times['subtitle'] ?? 'Our Sourdough Bread Order Cut-Off Times:' }}
      </p>

      <ul class="text-gray-600 text-sm md:text-base leading-relaxed space-y-1 mb-10">
      @foreach($homepage->order_cutoff_times as $time)
        <li>
          <strong>{{ $time['pickup_day'] }}</strong>: Order by {{ $time['order_by'] }}
        </li>
      @endforeach
      </ul>

      <a href="#footer"
         class="inline-block bg-black text-white text-sm md:text-base font-semibold px-7 py-2.5 rounded-md transition-all duration-300 hover:bg-[var(--brand-pink)] hover:text-white scroll-smooth">
        Contact Us
      </a>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section id="newsletter"
         class="relative bg-cover bg-center py-24 px-6 md:px-10"
         style="background-image: url('{{ asset('images/newsletter-bg.jpg') }}');">

  <!-- Overlay -->
  <div class="absolute inset-0 bg-white/70 backdrop-blur-sm"></div>

  <div class="relative max-w-2xl mx-auto bg-white shadow-xl rounded-2xl p-10 text-center z-10">
    @if(session('success'))
      <div class="mb-4 p-3 text-green-700 bg-green-100 border border-green-300 rounded-md">
        {{ session('success') }}
      </div>
    @endif

    <h3 class="text-xl md:text-2xl font-bold mb-6 text-gray-800">
      Subscribe To Our Newsletter
    </h3>

    <p class="text-gray-600 mb-6 text-sm md:text-base">
      Get updates on new products and exclusive offers straight to your inbox.
    </p>

    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-5">
      @csrf
      <div class="text-left">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
        <input type="email" id="email" name="email" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-base focus:outline-none focus:ring-2 focus:ring-[var(--brand-pink)] transition-all" />
      </div>

      <div class="flex items-center text-left">
        <input type="checkbox" id="subscribe" name="subscribe"
               class="h-4 w-4 text-[var(--brand-pink)] focus:ring-[var(--brand-pink)] border-gray-300 rounded">
        <label for="subscribe" class="ml-2 text-sm text-gray-700">
          Yes, subscribe me to your newsletter.
        </label>
      </div>

      <button type="submit"
              class="w-full bg-[var(--brand-pink)] hover:bg-[var(--brand-pink-light)] text-white font-semibold py-2.5 rounded-md text-base transition-all duration-300">
        Submit
      </button>
    </form>
  </div>
</section>

@endsection

@push('scripts')
<script defer>
(() => {
  const slides = document.querySelectorAll('.slide');
  let i = 0;
  const next = () => {
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
  };
  setInterval(next, 4000);
})();

// Countdown to second week of December (e.g., Dec 8)
const countdownDate = new Date(new Date().getFullYear(), 11, 8, 0, 0, 0).getTime(); // Dec 8 midnight
const countdownElements = {
  days: document.getElementById('days'),
  hours: document.getElementById('hours'),
  minutes: document.getElementById('minutes'),
  seconds: document.getElementById('seconds')
};

function updateCountdown() {
  const now = new Date().getTime();
  const distance = countdownDate - now;

  if (distance < 0) return; // Countdown finished

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  if (countdownElements.days) countdownElements.days.textContent = String(days).padStart(2,'0');
  if (countdownElements.hours) countdownElements.hours.textContent = String(hours).padStart(2,'0');
  if (countdownElements.minutes) countdownElements.minutes.textContent = String(minutes).padStart(2,'0');
  if (countdownElements.seconds) countdownElements.seconds.textContent = String(seconds).padStart(2,'0');
}

updateCountdown();
setInterval(updateCountdown, 1000);

/* Lightweight reveal-on-scroll for fade-up elements (minimal JS) */
requestIdleCallback(() => {
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
});
</script>

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FoodEstablishment",
  "name": "Tutu’s Foods",
  "image": "{{ asset('images/logo.png') }}",
  "url": "{{ url('/') }}",
  "servesCuisine": "Bakery",
  "sameAs": []
}
</script>
@endverbatim
@endpush