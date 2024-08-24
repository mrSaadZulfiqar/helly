@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Success') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Success')])

  <!-- Start Purchase Success Section -->
  <div class="booking-message">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="booking-success">
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2>{{ __('Success') . '!' }}</h2>

            @if ($bookingType == 'request_price_booking')
              <p>{{ __('Thank you for writing to us') . '.' }}</p>
              <p>{{ __('We have received your message and') . ', ' . __('will get back to you as soon as possible') . '.' }}</p>
            @elseif ($bookingType == 'offline_booking')
              <p>{{ __('Your transaction request was received and sent for review') . '.' }}</p>
              <p>{{ __('We answer every request as quickly as we can') . ', ' . __('usually within 24–48 hours') . '.' }}</p>
            @else
              <p>{{ __('Your transaction was successful') . '.' }}</p>
              <p>{{ __('We have sent you a mail with an invoice') . '.' }}</p>
            @endif

            <p class="mt-4">{{ __('Thank you') . '.' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Purchase Success Section -->
@endsection
