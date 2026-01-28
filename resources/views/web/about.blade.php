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
        We Create The World’s Most<br class="hidden md:block">
        Exquisite Treats
      </h1>

      <p class="text-gray-700 text-base md:text-lg leading-relaxed max-w-xl">
        Welcome to <strong>Tutu’s Foods</strong> — where tradition, flavour, and community
        come together under one roof. We’re a family of food brands built on
        love, culture, and craft.
      </p>

      <p class="text-gray-700 text-base md:text-lg leading-relaxed max-w-xl">
        From the sweet joys of cupcakes to the soulful chew of sourdough and
        the warm nostalgia of puff puff, we believe every bite should
        tell a story.
      </p>

      <a href="{{ url('/products') }}"
         class="inline-flex items-center justify-center border-2 border-[var(--brand-pink)] text-[var(--brand-pink)]
                hover:bg-[var(--brand-pink)] hover:text-white font-semibold px-8 py-3 rounded-full
                transition-all duration-300 w-fit">
        Explore Our Menu
      </a>
    </div>

    <!-- Image Stack -->
    <div class="relative about-image-stack fade-up">

      <!-- Main image -->
      <img
        src="{{ asset('images/about-main.webp') }}"
        alt="Tutu’s Foods cupcakes"
        class="rounded-3xl w-full object-cover"
      >

      <!-- Floating image -->
      <img
        src="{{ asset('images/about-secondary.webp') }}"
        alt="Handcrafted cupcakes"
        class="hidden md:block absolute -bottom-14 -left-14 w-56 h-56 object-cover rounded-2xl border-8 border-white"
      >

    </div>

  </div>
</section>

<!-- Our Philisophy -->
<section class="bg-white py-20 px-6 text-center">
  <div class="max-w-3xl mx-auto space-y-6 fade-up">
    <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
      Our Philosophy
    </h2>
    <p class="text-gray-600 text-base md:text-lg">
      Elevate everday — high-quality ingredients, no shortcuts.
      Bring people together — around tables, markets, and community.
      Keep it simple, soulful & honest.
    </p>
    <a href="{{ url('/products') }}"
       class="inline-flex items-center justify-center bg-[var(--brand-pink)]
              hover:bg-[var(--brand-pink-light)] text-white font-semibold px-10 py-4 rounded-full
              shadow-md transition-transform transform hover:scale-105 duration-300">
      Explore Our Menu
    </a>
  </div>
</section>

<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Title -->
        <h2 class="text-center text-3xl md:text-4xl font-semibold text-[var(--brand-pink)] mb-14">
            Meet the Tutu’s Family
        </h2>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

            <!-- Card -->
            <div class="bg-pink-50 rounded-2xl p-8">
                <h3 class="text-[var(--brand-pink)] font-semibold text-lg mb-4">
                    Tutu’s Cupcakes
                </h3>
                <p class="text-gray-800 leading-relaxed space-y-2">
                    Elevate everyday eating – high-quality ingredients, no shortcuts<br>
                    Bring people together – around tables, markets, and community<br>
                    Keep it simple, soulful & honest
                </p>
            </div>

            <!-- Card -->
            <div class="bg-pink-50 rounded-2xl p-8">
                <h3 class="text-[var(--brand-pink)] font-semibold text-lg mb-4">
                    Tutu’s Sourdough
                </h3>
                <p class="text-gray-800 leading-relaxed">
                    Crusty. Tangy. Made the slow way. Our sourdough loaves are
                    naturally fermented, baked with patience, and crafted to
                    last. A true staple for your kitchen.
                </p>
            </div>

            <!-- Card -->
            <div class="bg-pink-50 rounded-2xl p-8">
                <h3 class="text-[var(--brand-pink)] font-semibold text-lg mb-4">
                    The Original Sourdough Puff Puff
                </h3>
                <p class="text-gray-800 leading-relaxed">
                    Crispy on the outside, airy on the inside. A nostalgic treat,
                    reimagined with sourdough for extra depth and character.
                    Sweet or savoury — puff puff for every occasion.
                </p>
            </div>

            <!-- Card -->
            <div class="bg-pink-50 rounded-2xl p-8">
                <h3 class="text-[var(--brand-pink)] font-semibold text-lg mb-4">
                    Tutu’s Pantry
                </h3>
                <p class="text-gray-800 leading-relaxed">
                    Staples that spark creativity. From spice blends and sauces
                    to essential ingredients, our pantry collection makes
                    everyday cooking a little more inspired.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- OUR STORY -->
<section class="bg-white py-20 md:py-28 px-6">
  <div class="max-w-4xl mx-auto text-center space-y-6 fade-up">

    <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
      Our Story
    </h2>

    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
      What started as a passion for baking at home has grown into a collection
      of thoughtfully crafted food brands. 
    </p>

    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
    At Tutu’s Foods, we honour
    time-tested techniques while embracing modern creativity.
    </p>

  </div>
</section>

<!-- OUR STORY -->
<section class="bg-white py-20 md:py-28 px-6">
  <div class="max-w-4xl mx-auto text-center space-y-6 fade-up">

    <h2 class="text-3xl md:text-4xl font-semibold text-[var(--brand-pink)]">
      Join Us At The Table
    </h2>

    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
      We'd love to share this journey with you.
    </p>

    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
    Explore each brand on our website.
    </p>



  </div>
</section>


<!-- REVIEWS -->
<section class="bg-[var(--brand-bg)] py-20 px-6">
  <div class="max-w-4xl mx-auto text-center">

    <h2 class="text-[var(--brand-pink)] text-3xl md:text-4xl font-semibold mb-14 fade-up">
      What our customers are saying
    </h2>

    <!-- Slider -->
    <div class="relative overflow-hidden">

      <div id="review-slider" class="flex transition-transform duration-500 ease-out">

        @foreach([
          ['The cupcakes were honestly the best I’ve had in years. You can taste the care in every bite.', 'Amaka O.'],
          ['Tutu’s sourdough has become a weekly staple in our home. So wholesome and satisfying.', 'David K.'],
          ['I love how everything feels intentional — from the food to the experience.', 'Zainab A.'],
        ] as [$review, $name])

          <div class="min-w-full px-6">
            <div class="soft-card p-10 max-w-2xl mx-auto">
              <p class="text-gray-700 text-lg leading-relaxed mb-6">
                “{{ $review }}”
              </p>
              <span class="block text-sm font-semibold text-gray-900">
                — {{ $name }}
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