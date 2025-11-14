@extends('layouts.products')

@section('title', "Corporate Orders - Tutu's Foods")

@section('content')

<style>
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
  }

  body {
    background: #fafafa;
  }

  .form-card {
    background: #ffffff;
    border: 1px solid #f1d5dd;
    border-radius: 1rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.03);
  }

  input, select, textarea {
    border: 1px solid #e5e5e5 !important;
    border-radius: .65rem;
    padding: .7rem .9rem;
    font-size: .95rem;
    width: 100%;
    transition: .2s;
  }

  input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--brand-pink);
    box-shadow: 0 0 0 2px rgba(255,88,135,0.15);
  }

  .brand-btn {
    background: var(--brand-pink);
    color: white;
    padding: .85rem 2rem;
    border-radius: .65rem;
    font-weight: 600;
    width: 100%;
    transition: .25s;
  }

  .brand-btn:hover {
    background: var(--brand-pink-light);
  }
</style>


<section class="py-20 px-6">
  <div class="max-w-3xl mx-auto form-card p-8 md:p-10">

    <h2 class="text-center text-3xl font-bold text-gray-900 mb-3">
      Corporate Order Request
    </h2>

    <p class="text-center text-gray-600 mb-10 max-w-md mx-auto">
      For corporate events, gifting, or bulk orders — submit your request and our team will respond within 24 hours.
    </p>

    @if(session('success'))
      <div class="mb-6 p-3 bg-green-100 text-green-800 border border-green-300 rounded-md text-sm">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('corporate.submit') }}" method="POST" class="space-y-7">
      @csrf

      {{-- Name --}}
      <div>
        <label class="font-medium text-gray-800 text-sm mb-1 block">Name *</label>
        <input type="text" name="name" required>
      </div>

      {{-- Phone + Purpose --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="font-medium text-gray-800 text-sm mb-1 block">Phone Number *</label>
          <input type="text" name="phone" required>
        </div>

        <div>
          <label class="font-medium text-gray-800 text-sm mb-1 block">Purpose *</label>
          <select name="purpose" required>
            <option value="">Select purpose</option>
            <option value="Elevate Your Event">Elevate Your Event</option>
            <option value="Corporate Gifting">Corporate Gifting</option>
            <option value="Employee Gifting">Employee Gifting</option>
            <option value="Market your Brand">Market your Brand</option>
          </select>
        </div>
      </div>

      {{-- People + Type --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="font-medium text-gray-800 text-sm mb-1 block">Number of People *</label>
          <input type="number" name="people" required>
        </div>

        <div>
          <label class="font-medium text-gray-800 text-sm mb-1 block">Type *</label>
          <select name="type" required>
            <option value="">Select type</option>
            <option value="Branded Cupcakes">Branded Cupcakes</option>
            <option value="Branded Cookies">Branded Cookies</option>
            <option value="Loaf Cakes">Loaf Cakes</option>
            <option value="Mini Loaf Cakes">Mini Loaf Cakes</option>
            <option value="Cupcake Station">Cupcake Station</option>
            <option value="Bespoke Desert Grazing Boxes">Bespoke Desert Grazing Boxes</option>
            <option value="Bespoke Desert Gift Boxes">Bespoke Desert Gift Boxes</option>
            <option value="Dessert Tables">Dessert Tables</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      {{-- Special Requests --}}
      <div>
        <label class="font-medium text-gray-800 text-sm mb-1 block">Any special requests</label>
        <textarea name="requests" rows="4"></textarea>
      </div>

      {{-- Terms --}}
      <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" required class="w-4 h-4">
        I accept Tutu’s Foods' Terms & Conditions *
      </label>

      {{-- Submit --}}
      <button type="submit" class="brand-btn">Submit Request</button>

    </form>

  </div>
</section>

@endsection
