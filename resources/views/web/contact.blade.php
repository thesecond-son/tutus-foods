@extends('layouts.regular')

@section('title', "Contact Us - Tutu's Foods")

@section('meta_description', "Get in touch with Tutu’s Foods. Call, email or visit us, see our opening hours, and find help with your order.")

@section('content')

<style>
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
  }
</style>

@php
  $footer  = $homepage->footer ?? [];
  $phone   = $footer['phone'] ?? null;
  $email   = $footer['email'] ?? null;
  $address = $footer['address'] ?? null;
  $hours   = $footer['opening_hours'] ?? [];
  $cutoffs = $homepage->order_cutoff_times['items'] ?? [];
  $telHref = $phone ? preg_replace('/[^\d+]/', '', $phone) : null;
@endphp

<!-- HEADER -->
<section class="bg-[var(--brand-bg)] py-20 pt-32 pb-12 px-6 text-center">
  <div class="max-w-3xl mx-auto">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">Contact Us</h1>
    <p class="mt-4 text-gray-600 text-base md:text-lg">
      Questions about an order, a collection, or delivery? Here is how to reach us.
    </p>
  </div>
</section>

<!-- CONTACT METHODS -->
<section class="bg-white py-12 md:py-16 px-6">
  <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

    @if($phone)
    <div class="bg-[var(--brand-bg)] border border-pink-100 rounded-2xl p-8 text-center shadow-sm">
      <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--brand-pink)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3.6 7.59a1 1 0 01-.36 1.32l-1.9 1.13a11.05 11.05 0 005.17 5.17l1.13-1.9a1 1 0 011.32-.36L19 19v2a1 1 0 01-1 1A17 17 0 013 5z"/>
        </svg>
      </div>
      <h2 class="text-lg font-semibold text-gray-900 mb-2">Call Us</h2>
      <p class="text-sm text-gray-600 mb-4">Speak to us during opening hours.</p>
      <a href="tel:{{ $telHref }}" class="font-semibold text-gray-900 hover:text-[var(--brand-pink)] transition">{{ $phone }}</a>
    </div>
    @endif

    @if($email)
    <div class="bg-[var(--brand-bg)] border border-pink-100 rounded-2xl p-8 text-center shadow-sm">
      <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--brand-pink)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </div>
      <h2 class="text-lg font-semibold text-gray-900 mb-2">Email Us</h2>
      <p class="text-sm text-gray-600 mb-4">We reply as soon as we can.</p>
      <a href="mailto:{{ $email }}" class="font-semibold text-gray-900 hover:text-[var(--brand-pink)] transition break-all">{{ $email }}</a>
    </div>
    @endif

    @if($address)
    <div class="bg-[var(--brand-bg)] border border-pink-100 rounded-2xl p-8 text-center shadow-sm">
      <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--brand-pink)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
      <h2 class="text-lg font-semibold text-gray-900 mb-2">Find Us</h2>
      <p class="text-sm text-gray-600 mb-4">Our location for collections.</p>
      <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener"
         class="font-semibold text-gray-900 hover:text-[var(--brand-pink)] transition">{{ $address }}</a>
    </div>
    @endif

  </div>
</section>

<!-- HOURS + ORDER HELP -->
<section class="bg-white pb-16 md:pb-24 px-6">
  <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

    @if(count($hours))
    <div class="bg-[#ffe4ea] rounded-2xl p-8">
      <h2 class="text-xl font-semibold text-gray-900 mb-4">Opening Hours</h2>
      <ul class="space-y-2 text-sm md:text-base text-gray-800">
        @foreach($hours as $row)
          <li class="flex justify-between gap-4">
            <span class="font-medium">{{ $row['days'] ?? '' }}</span>
            <span>{{ $row['time'] ?? '' }}</span>
          </li>
        @endforeach
      </ul>
    </div>
    @endif

    <div class="bg-[var(--brand-bg)] border border-pink-100 rounded-2xl p-8">
      <h2 class="text-xl font-semibold text-gray-900 mb-4">Help With Your Order</h2>
      <ul class="space-y-3 text-sm md:text-base text-gray-700">
        <li>Have your <strong>order number</strong> ready (it starts with ORD- and is in your confirmation email).</li>
        <li>For changes to a delivery or collection, contact us as early as possible.</li>
        @if(count($cutoffs))
          <li>
            <strong>{{ $homepage->order_cutoff_times['subtitle'] ?? 'Order cut-off times' }}</strong>
            <ul class="mt-2 space-y-1 text-gray-600">
              @foreach($cutoffs as $time)
                <li>
                  {{ $time['pickup_day'] ?? '' }}@if(!empty($time['order_by'])): {{ $time['order_by'] }}@endif
                </li>
              @endforeach
            </ul>
          </li>
        @endif
        <li>
          See our <a href="/refund-policy" class="underline hover:text-[var(--brand-pink)] transition">Refund Policy</a>
          and <a href="/privacy-policy" class="underline hover:text-[var(--brand-pink)] transition">Privacy Policy</a>.
        </li>
      </ul>
    </div>

  </div>
</section>

@endsection