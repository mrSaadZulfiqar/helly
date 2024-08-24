@extends('drivers.layout')

@section('content')
  <div class="mt-2 mb-4 text-center">
    <h2 class="pb-2 welcome">{{ __('Welcome back,') }} <b class="bold-name">{{ Auth::guard('driver')->user()->username . '!' }}</b></h2>
  </div>
  @if (Session::get('secret_login') != 1)
    @if (Auth::guard('driver')->user()->status == 0 && $admin_setting->vendor_admin_approval == 1)
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          {{ $admin_setting->admin_approval_notice != null ? $admin_setting->admin_approval_notice : 'Your account is deactive!' }}
        </div>
      </div>
    @endif
  @endif


  {{-- dashboard information start --}}
  <div class="row dashboard-items">
    
    <div class="col-sm-6 col-md-3">
      <a href="{{ route('driver.equipment_booking.bookings', ['language' => '$defaultLang->code']) }}">
        <div class="card card-stats card-danger card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-calendar-alt"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Bookings') }}</p>
                  <h4 class="card-title">{{ $totalBooking }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  
@endsection

@section('script')
  
@endsection
