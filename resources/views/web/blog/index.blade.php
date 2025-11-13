@extends('layouts.app')

@section('content')
<section class="bg-white py-16 px-6">
  <div class="max-w-7xl mx-auto">

    <!-- Page Title -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-8 text-center">
      Blog
    </h1>

    <!-- Categories -->
    <div class="flex flex-wrap justify-center gap-4 mb-8">
      @foreach($categories as $cat)
        <a href="{{ route('blog.category', $cat->slug) }}"
           class="px-4 py-2 rounded-full text-sm md:text-base font-medium border border-gray-300 hover:bg-[var(--brand-pink)] hover:text-white transition">
          {{ $cat->name }}
        </a>
      @endforeach
    </div>

    @isset($category)
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">
        {{ $category->name }}
      </h2>
    @endisset

    <!-- Blog Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($blogs as $blog)
        <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
          
          <!-- Image -->
          <div class="relative">
            <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}"
                 class="w-full h-56 md:h-64 object-cover transition-transform duration-500 hover:scale-105">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
              <div class="flex justify-between items-center text-white text-xs md:text-sm">
                <span>{{ $blog->posted_at->format('M d, Y') }}</span>
                <span>{{ $blog->read_length }} min read</span>
                @if($blog->share_link)
                  <a href="{{ $blog->share_link }}" target="_blank" class="ml-2">⋯</a>
                @endif
              </div>
            </div>
          </div>

          <!-- Content -->
          <div class="p-5 md:p-6">
            <a href="{{ route('blog.show', $blog->slug) }}" class="text-lg md:text-xl font-semibold text-gray-800 hover:text-[var(--brand-pink)] transition">
              {{ $blog->title }}
            </a>

            <p class="text-gray-600 mt-2 text-sm md:text-base line-clamp-3">
              {!! Str::limit(strip_tags($blog->content), 150) !!}
            </p>

            <!-- Likes & Comments -->
            <div class="flex items-center justify-start gap-4 mt-4 text-gray-500 text-xs md:text-sm">
              <span>❤️ {{ $blog->likes }}</span>
              <!-- s -->
            </div>
          </div>

        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
      {{ $blogs->links() }}
    </div>

  </div>
</section>
@endsection
