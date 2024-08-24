@extends('frontend.layout')

@section('pageHeading')
{{ __('Swap Equipment') }}
@endsection

@section('content')

<!--====== Start Equipment Bookings Section ======-->
<section class="user-dashboard">
  <div class="container-fluid">
    <div style="min-height: 100vh" class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div class="row">
          <div class="col-lg-12">
            <div class="user-profile-details">
              <div class="account-info">
                <div class="title">
                  <h4>{{ __('Swap Equipment') }}</h4>
                </div>

                <div class="main-info text-center">
                  <h6 class="text-center mb-3">Swap Charges ${{ $swap_charge }}</h6>
                  <p></p>
                  <a href="{{ route('user.equipment_booking.swap_equipment', ['id' => $booking_id]) }}?process_swap=true"
                    class="btn btn-primary">Process Swap</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--====== End Equipment Bookings Section ======-->
@endsection