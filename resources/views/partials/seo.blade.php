{{--
    Shared SEO head tags for both storefront layouts.
    Pages can override with:
      @section('title', '...')             page <title> (and og:title)
      @section('meta_description', '...')  description (and og:description)
      @section('og_image', 'https://...')  absolute share image URL
      @section('og_type', 'article')       defaults to "website"
      @section('robots', 'noindex, follow') defaults to "index, follow"
      @section('canonical', 'https://...') defaults to the current URL without query string
      @push('structured_data') <script type="application/ld+json">...</script> @endpush
--}}
@php
    $seoSiteName   = "Tutu's Foods";
    $seoTitle      = html_entity_decode(trim($__env->yieldContent('title', $defaultTitle ?? $seoSiteName)), ENT_QUOTES | ENT_HTML5);
    $seoDesc       = trim($__env->yieldContent('meta_description', $defaultDescription ?? "Handcrafted cakes, cupcakes, sourdough, festive treats and signature baked goods from Tutu's Foods."));
    $seoImage      = html_entity_decode(trim($__env->yieldContent('og_image', asset('images/hero1.jpg'))), ENT_QUOTES | ENT_HTML5);
    $seoType       = trim($__env->yieldContent('og_type', 'website'));
    $seoRobots     = trim($__env->yieldContent('robots', 'index, follow'));
    $seoCanonical  = html_entity_decode(trim($__env->yieldContent('canonical', url()->current())), ENT_QUOTES | ENT_HTML5);
    $seoDesc       = \Illuminate\Support\Str::limit(html_entity_decode(strip_tags(html_entity_decode($seoDesc, ENT_QUOTES | ENT_HTML5)), ENT_QUOTES | ENT_HTML5), 160);
@endphp
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDesc }}">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonical }}">
<meta name="theme-color" content="#ff5887">
<meta name="format-detection" content="telephone=no">

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('images/logo.webp') }}">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn, etc.) --}}
<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:locale" content="en_GB">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:image:alt" content="{{ $seoTitle }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDesc }}">
<meta name="twitter:image" content="{{ $seoImage }}">

{{-- Structured data: the business, on every page --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Bakery',
    'name'     => $seoSiteName,
    'url'      => url('/'),
    'logo'     => asset('images/logo.webp'),
    'image'    => asset('images/hero1.jpg'),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@stack('structured_data')
