@extends('layouts.app')

@section('title', "About Us - Tutu's Foods")

@section('content')
<style>
  /* Reuse brand system exactly */
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
  }

  .about-hero-title {
    font-family: 'Playfair Display', serif;
  }

  .about-image-stack img {
    box-shadow: 0 18px 40px rgba(15,15,15,0.12);
  }
  
  .dot {
    width: 10px;
    height: 10px;
    border-radius: 9999px;
    background: #e5e5e5;
    transition: background 0.3s;
  }

  .dot.active {
    background: var(--brand-pink);
  }


</style>

<!-- ABOUT HERO -->
<section class="bg-[var(--brand-bg)] py-20 md:py-28 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

    <!-- Text -->
    <div class="space-y-6 fade-up">
      <span class="inline-block text-sm font-bold tracking-wide text-[var(--brand-pink)] uppercase">
        About Tutu’s Foods
      </span>

      <h1 class="about-hero-title text-4xl md:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight">
      {!! nl2br(e($aboutUs['hero']['title'] ?? '')) !!}
      </h1>

      @if(!empty($aboutUs['hero']['subtitle1']))
        <p class="text-gray-700 text-base md:text-lg leading-relaxed max-w-xl">
          {!! nl2br(e($aboutUs['hero']['subtitle1'])) !!}
        </p>
      @endif

      @if(!empty($aboutUs['hero']['subtitle2']))
        <p class="text-gray-700 text-base md:text-lg leading-relaxed max-w-xl">
          {!! nl2br(e($aboutUs['hero']['subtitle2'])) !!}
        </p>
      @endif

      @if(!empty($aboutUs['hero']['button_text']) && !empty($aboutUs['hero']['button_link']))
        <a href="{{ $aboutUs['hero']['button_link'] }}"
           class="inline-flex items-center justify-center border-2 border-[var(--brand-pink)] text-[var(--brand-pink)]
                  hover:bg-[var(--brand-pink)] hover:text-white font-semibold px-8 py-3 rounded-full
                  transition-all duration-300 w-fit">
          {{ $aboutUs['hero']['button_text'] }}
        </a>
      @endif
    </div>

    <!-- Image Stack -->
    <div class="relative about-image-stack fade-up">

      @php
        $images = $aboutUs['hero']['images'] ?? [];
      @endphp

      <!-- Main image -->
      @if(isset($images[0]['image']))
        <img
          src="{{ asset('storage/' . $images[0]['image']) }}"
          alt="Hero image"
          class="rounded-3xl w-full object-cover"
        >
      @endif

      <!-- Floating image -->
      @if(isset($images[1]['image']))
        <img
          src="{{ asset('storage/' . $images[1]['image']) }}"
          alt="Hero secondary image"
          class="hidden md:block absolute -bottom-14 -left-14 w-56 h-56 object-cover rounded-2xl border-8 border-white"
        >
      @endif

    </div>

  </div>
</section>

<!-- Our Philisophy -->
<section class="bg-white py-20 px-6 text-center">
  <div class="max-w-3xl mx-auto space-y-6 fade-up">
    <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
    {{ $aboutUs['philisophy']['title'] ?? '' }}
    </h2>

    {{-- Philosophy body text --}}
    @if(!empty($aboutUs['philisophy']['description']))
      <p class="text-gray-600 text-base md:text-lg">
        {!! nl2br(e($aboutUs['philisophy']['description'])) !!}
      </p>
    @endif

    @if(
        !empty($aboutUs['philisophy']['button_text']) &&
        !empty($aboutUs['philisophy']['button_link'])
    )
      <a href="{{ $aboutUs['philisophy']['button_link'] }}"
         class="inline-flex items-center justify-center bg-[var(--brand-pink)]
                hover:bg-[var(--brand-pink-light)] text-white font-semibold px-10 py-4 rounded-full
                shadow-md transition-transform transform hover:scale-105 duration-300">
        {{ $aboutUs['philisophy']['button_text'] }}
      </a>
    @endif

  </div>
</section>

<!-- Tutus Family -->
<section class="py-20 lg:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Title -->
      @if(!empty($aboutUs['tutus_family']['title']))
        <h2 class="text-center text-3xl md:text-4xl font-semibold text-[var(--brand-pink)] mb-14">
          {{ $aboutUs['tutus_family']['title'] }}
        </h2>
      @endif

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

            <!-- Card -->
            @foreach(($aboutUs['tutus_family']['items'] ?? []) as $item)
            @if(is_array($item))
            <div class="bg-pink-50 rounded-2xl p-8">

                <h3 class="text-[var(--brand-pink)] font-semibold text-lg mb-4">
                {{ $item['title'] ?? '' }}
                </h3>

                <p class="text-gray-800 leading-relaxed space-y-2">
                {!! nl2br(e($item['subtitle'] ?? '')) !!}
                </p>
            </div>
            @endif
            @endforeach

        </div>
    </div>
</section>


<!-- OUR STORY -->
<section class="bg-white py-20 md:py-24 px-6">
  <div class="max-w-4xl mx-auto text-center space-y-6 fade-up">

    <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
    {{ $aboutUs['story']['title'] }}
    </h2>

    @foreach(($aboutUs['story']['items'] ?? []) as $item)
    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
   {!! nl2br(e($item['paragraph'] ?? '')) !!}
    </p>
    @endforeach

  </div>
</section>

<!--Join Us-->
<section class="bg-white py-20 md:py-24 px-6">
  <div class="max-w-4xl mx-auto text-center space-y-6 fade-up">

  @if(!empty($aboutUs['join_us']['title']))
      <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
        {{ $aboutUs['join_us']['title'] }}
      </h2>
    @endif

    @foreach(($aboutUs['join_us']['items'] ?? []) as $item)
      <p class="text-gray-600 text-base md:text-lg leading-relaxed">
        {!! nl2br(e($item['paragraph'] ?? '')) !!}
      </p>
    @endforeach

  </div>
</section>


<!-- REVIEWS -->
<section class="bg-[var(--brand-bg)] py-20 px-6">
  <div class="max-w-4xl mx-auto text-center">

  @if(!empty($aboutUs['reviews']['title']))
      <h2 class="text-[var(--brand-pink)] text-3xl md:text-4xl font-semibold mb-3 fade-up">
        {{ $aboutUs['reviews']['title'] }}
      </h2>
    @endif

    <!-- Slider -->
    <div class="relative overflow-hidden">

      <div id="review-slider" class="flex transition-transform duration-500 ease-out">

      @foreach(($aboutUs['reviews']['items'] ?? []) as $item)
          <div class="min-w-full px-4">
            <div class="soft-card p-10 max-w-2xl mx-auto">

              <p class="text-gray-700 text-lg leading-relaxed mb-6">
                “{!! nl2br(e($item['review'] ?? '')) !!}”
              </p>

              <span class="block text-sm font-semibold text-gray-900">
                — {{ $item['name'] ?? '' }}
              </span>

            </div>
          </div>
        @endforeach
        
      </div>

      <!-- Dots -->
      <div class="flex justify-center gap-3 mt-8">
        <button class="dot active"></button>
        <button class="dot"></button>
        <button class="dot"></button>
      </div>

    </div>
  </div>
</section>

<!-- CTA -->
<!-- <section class="bg-white py-20 px-6 text-center">
  <div class="max-w-3xl mx-auto space-y-6 fade-up">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
      Taste the Love
    </h2>
    <p class="text-gray-600 text-base md:text-lg">
      Ready to experience handcrafted goodness made with heart?
    </p>
    <a href="{{ url('/products') }}"
       class="inline-flex items-center justify-center bg-[var(--brand-pink)]
              hover:bg-[var(--brand-pink-light)] text-white font-semibold px-10 py-4 rounded-full
              shadow-md transition-transform transform hover:scale-105 duration-300">
      Shop Our Products
    </a>
  </div>
</section> -->

@endsection


@push('scripts')

<script>
  const slider = document.getElementById('review-slider');
  const dots = document.querySelectorAll('.dot');

  let index = 0;
  const total = dots.length;

  function showSlide(i) {
    slider.style.transform = `translateX(-${i * 100}%)`;
    dots.forEach(d => d.classList.remove('active'));
    dots[i].classList.add('active');
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      index = i;
      showSlide(index);
    });
  });

  setInterval(() => {
    index = (index + 1) % total;
    showSlide(index);
  }, 5000);
</script>

@endpush