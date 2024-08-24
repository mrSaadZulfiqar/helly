@extends('vendors.layout')

@section('content')

  @if (Session::get('secret_login') != 1)
    @if (Auth::guard('vendor')->user()->status == 0 && $admin_setting->vendor_admin_approval == 1)
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          {{ $admin_setting->admin_approval_notice != null ? $admin_setting->admin_approval_notice : 'Your account is deactive!' }}
        </div>
      </div>
    @endif
  @endif


  {{-- dashboard information start --}}
  <div class="row dashboard-items">
    <div class="col-md-3">
      <a href="{{ route('vendor.monthly_income') }}">
        <div class="card card-stats card-primary card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('My Balance ') }}</p>
                  <h4 class="card-title">
                    {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                    {{ Auth::guard('vendor')->user()->amount }}
                    {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="{{ route('vendor.transcation') }}">
        <div class="card card-stats card-warning card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-exchange"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Transaction') }}</p>
                  <h4 class="card-title">
                    {{ $transcations }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-sm-6 col-md-3">
      <a href="{{ route('vendor.equipment_management.all_equipment', ['language' => $defaultLang->code]) }}">
        <div class="card card-stats card-success card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-truck-container"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Equipment') }}</p>
                  <h4 class="card-title">{{ $totalEquipment }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-sm-6 col-md-3">
      <a href="{{ route('vendor.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">
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
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Number of Equipment Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="bookingChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Income from Equipment Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="incomeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- chart js ----->
  <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

  <script>
    'use strict';
    const monthArr = {!! json_encode($months) !!};
    const bookingArr = {!! json_encode($bookings) !!};
    const incomeArr = {!! json_encode($incomes) !!};
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/chart-init.js') }}"></script>
@endsection
