@extends('layouts.products')

@section('content')

<style>
     :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-light-bg: #fff8f9;
  }
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
    <div class="prose prose-xl max-w-full mx-auto text-gray-900 mb-12">
      {!! $blog->content !!}
    </div>

    <!-- Likes & Comment Count -->
    <div class="flex items-center gap-6 text-gray-600 mb-12">
      <span class="flex items-center gap-2 font-medium text-gray-700">❤️ {{ $blog->likes }}</span>
      <span class="flex items-center gap-2 font-medium text-gray-700">💬 {{ $blog->comments->count() }}</span>
    </div>

    <!-- Comments Section -->
    <div class="bg-[#fff5f8] rounded-3xl p-8 md:p-10 shadow-lg">
      <h3 class="text-2xl font-bold mb-6 text-gray-900">Comments</h3>

      @forelse($blog->comments as $comment)
        <div class="mb-6 p-4 bg-white rounded-2xl shadow-sm">
          <div class="flex justify-between items-center mb-2">
            <p class="text-gray-900 font-semibold">{{ $comment->customer_name ?? 'Anonymous' }}</p>
            <span class="text-gray-400 text-xs">{{ $comment->created_at->format('M d, Y H:i') }}</span>
          </div>
          <p class="text-gray-700">{{ $comment->comment }}</p>
        </div>
      @empty
        <p class="text-gray-500 mb-4">No comments yet. Be the first to comment!</p>
      @endforelse

      <!-- Add Comment Form -->
      <form action="{{ route('blog.comments.store', $blog->id) }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <div>
          <label for="customer_name" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Name</label>
          <input type="text" name="customer_name" id="customer_name"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[var(--brand-pink)] focus:outline-none bg-white">
        </div>

        <div>
          <label for="comment" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Comment *</label>
          <textarea name="comment" id="comment" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[var(--brand-pink)] focus:outline-none bg-white"></textarea>
        </div>

        <button type="submit"
                class="bg-[var(--brand-pink)] hover:bg-[var(--brand-pink-light)] text-white font-semibold px-7 py-3 rounded-xl transition-all duration-300">
          Submit Comment
        </button>
      </form>
    </div>

  </div>
</section>
@endsection
