@extends('layouts.app')

@section('title', "Refund & Returns Policy - Tutu's Foods")

@section('content')
<style>
  :root {
    --brand-pink: #ff5887;
    --brand-bg: #f7fbff;
  }
</style>

<section class="bg-[var(--brand-bg)] py-20 md:py-28 px-6">
  <div class="max-w-4xl mx-auto">

    <!-- Page Title -->
    <h1 class="text-[var(--brand-pink)] text-3xl md:text-4xl font-semibold mb-10">
      Refund &amp; Returns Policy
    </h1>

    <!-- Section Heading -->
    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">
      Cancellation, postponement, alterations and postal box returns
    </h2>

    <!-- Content -->
    <div class="space-y-5 text-gray-700 text-base leading-relaxed">

      <p>
        In the event of a cancellation by yourselves, charges are as follows;
      </p>

      <p>
        <strong>More than ninety days</strong> prior to the date = Booking fee less admin cost of £10.
      </p>

      <p>
        <strong>Ninety days to one calendar month</strong> prior to the date = Booking fee plus half the remaining cost.
      </p>

      <p>
        <strong>Less than one calendar month</strong> prior to the date = Full amount.
      </p>

      <p>
        Any payments previously made are not refundable.
      </p>

      <p>
        In the event of a postponement, the payment due remains the same as a cancellation.
        Subject to availability, we will carry the monies paid forward to the new wedding date.
        Please be aware that we will only do this on one occasion.
        Any further postponements will be classed as a cancellation.
      </p>

      <p>
        You may, prior to <strong>8 weeks before the wedding day</strong>, or
        <strong>7 days for celebration cakes</strong>, amend or cancel your order by
        providing us with written notice. In the case of alterations, a new order confirmation will be issued detailing
        the changes and the new cost. Alterations are not confirmed until a new order
        confirmation has been issued. Should you make any changes after that time, there will not be a reduction
        in the cost of your order. Please note that we may not be able to accommodate any alterations if work has started on your cake.
      </p>

      <p>
      In the unlikely event that we need to cancel your order any monies paid will be refunded. Tutu’s Foods reserves the right to cancel, vary or suspend the operation of this contract if events occur which are in the nature of force majeure including (without prejudice to the generality of the foregoing): fire, floods, storm, plant breakdown, strike, lock outs, riot, hostilities, non-availability of materials or supplies or any other event outside the control of Tutu’s Foods, and Tutu’s Foods shall not be liable for any breach of contract resulting from such an event.
      </p>

      <p>
      Tutu’s Foods' liability (both in contract and tort) in respect of defects in goods or services shall be limited to the issue of a credit note in respect thereof or granting a refund or other such compensatory measures as Tutus' Foods, at its discretion, considers appropriate in the circumstances and shall be limited to the to the value of the goods or services giving rise to the claim.
      </p>

      <p>
      Tutu’s Foods' shall not under any circumstances be liable for the customer respect of indirect consequential loss or damage or loss of profits, provided always that these conditions do not exclude or restrict Tutu’s Foods' statutory liability for death or personal injury arising from any negligence on Tutu’s Foods' part or liability imposed by statutory implied terms in Consumer Contracts.
      </p>

      <p>
      Due to the highly perishable nature of the items in our postal boxes, we do not accept returns on the boxes. In the unlikely event that you have any complaints, please raise them to us as set out in the complaints section of our Terms and Conditions.
      </p>

    </div>

  </div>
</section>
@endsection
