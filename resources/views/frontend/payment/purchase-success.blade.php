@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Success') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Success')])

  <!-- Start Purchase Success Section -->
  <div class="purchase-message">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="purchase-success">
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2>{{ __('Success') . '!' }}</h2>

            @if ($purchaseType == 'offline_purchase')
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

@section('script')
  <script type="text/javascript">
    sessionStorage.removeItem('chargeId');
    sessionStorage.removeItem('calculatedTax');
    sessionStorage.removeItem('grandTotal');
    sessionStorage.removeItem('charge');
    sessionStorage.removeItem('newSubtotal');
    sessionStorage.removeItem('discount');
  </script>
@endsection
