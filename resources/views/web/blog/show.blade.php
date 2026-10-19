@extends('layouts.products')

@section('content')

<style>
     :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-light-bg: #fff8f9;
  }

  /* Tailwind's preflight strips margins/sizes from p, h1-h3, ul, ol, blockquote.
     These rules restore them for content written in the admin rich editor. */
  .blog-content { line-height: 1.75; font-size: 1.125rem; }
  .blog-content p { margin: 0 0 1.25rem; }
  .blog-content p:empty { min-height: 1.5em; }       /* blank lines added in the editor */
  .blog-content h1 { font-size: 2rem; font-weight: 800; margin: 2rem 0 1rem; line-height: 1.2; }
  .blog-content h2 { font-size: 1.6rem; font-weight: 700; margin: 1.75rem 0 0.75rem; line-height: 1.25; }
  .blog-content h3 { font-size: 1.3rem; font-weight: 700; margin: 1.5rem 0 0.75rem; line-height: 1.3; }
  .blog-content ul { list-style: disc; padding-left: 1.5rem; margin: 0 0 1.25rem; }
  .blog-content ol { list-style: decimal; padding-left: 1.5rem; margin: 0 0 1.25rem; }
  .blog-content li { margin-bottom: 0.5rem; }
  .blog-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; margin: 0 0 1.25rem; font-style: italic; }
  .blog-content a { text-decoration: underline; }
  .blog-content strong { font-weight: 700; }
</style>

<section class="bg-white py-20 px-6">
  <div class="max-w-5xl mx-auto">

    <!-- Categories -->
    <div class="flex flex-wrap justify-center gap-3 mb-6">
      @foreach($blog->categories as $cat)
        <a href="{{ route('blog.category', $cat->slug) }}"
           class="px-4 py-1 rounded-full text-xs md:text-sm font-medium border border-gray-300 hover:bg-[var(--brand-pink)] hover:text-white transition">
          {{ $cat->name }}
        </a>
      @endforeach
    </div>

    <!-- Blog Title -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 text-center md:text-left leading-tight">
      {{ $blog->title }}
    </h1>

    <!-- Meta Info + Share Buttons -->
    <div class="flex flex-col md:flex-row md:justify-between items-center text-gray-500 text-sm md:text-base mb-8 gap-3">
      <div class="flex gap-4">
        <span>{{ $blog->posted_at->format('M d, Y') }}</span>
        <span>{{ $blog->read_length }} min read</span>
      </div>

      <div class="flex gap-3 text-gray-500 text-lg">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($blog->share_link) }}" target="_blank" class="hover:text-[#4267B2] transition">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($blog->share_link) }}" target="_blank" class="hover:text-[#1DA1F2] transition">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($blog->share_link) }}" target="_blank" class="hover:text-[#0077B5] transition">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>
    </div>

    <!-- Blog Image -->
    @if($blog->image)
      <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}"
           class="w-full h-96 md:h-[500px] object-cover rounded-3xl mb-10 shadow-lg">
    @endif

    <!-- Blog Content -->
    <div class="blog-content max-w-full mx-auto text-gray-900 mb-12">
      {!! $blog->content !!}
    </div>

    <!-- Likes & Comment Count -->
    <div class="flex items-center gap-6 text-gray-600 mb-12">
      <span class="flex items-center gap-2 font-medium text-gray-700">❤️ {{ $blog->likes }}</span>
    </div>

  </div>
</section>
@endsection