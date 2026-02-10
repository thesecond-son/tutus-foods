@extends('layouts.app')

@section('title', "Privacy Policy - Tutu's Foods")

@section('content')
<style>
  :root {
    --brand-pink: #ff5887;
    --brand-bg: #f7fbff;
  }

  .policy-content {
  color: #374151; /* gray-700 */
  font-size: 1rem;
  line-height: 1.75;
}

/* Headings */
.policy-content h2 {
  font-size: 1.5rem; /* ~text-2xl */
  font-weight: 700;
  margin-top: 2.5rem;
  margin-bottom: 1rem;
  color: #111827; /* gray-900 */
}

.policy-content h3 {
  font-size: 1.25rem; /* ~text-xl */
  font-weight: 600;
  margin-top: 1.75rem;
  margin-bottom: 0.75rem;
  color: #111827;
}

/* Paragraphs */
.policy-content p {
  margin-bottom: 1.25rem;
}

/* Lists */
.policy-content ul,
.policy-content ol {
  padding-left: 1.5rem;
  margin-bottom: 1.25rem;
}

.policy-content li {
  margin-bottom: 0.5rem;
}

/* Strong text */
.policy-content strong {
  font-weight: 600;
}

/* Links */
.policy-content a {
  color: var(--brand-pink);
  text-decoration: underline;
}

</style>

<section class="bg-[var(--brand-bg)] py-20 md:py-28 px-6">
  <div class="max-w-4xl mx-auto">

    <!-- Page Title -->
    <h1 class="text-[var(--brand-pink)] text-3xl md:text-4xl font-semibold mb-10">
      {{ $privacyPolicy->privacy_policy['title'] ?? 'Privacy Policy' }}
    </h1>

    <div class="policy-content">
      {!! $privacyPolicy->privacy_policy['content'] ?? '' !!}
    </div>


  </div>
</section>
@endsection
