@extends('backend.layout')

@section('content')
{{-- <div class="mt-2 mb-4">
  <h2 class="pb-2">{{ __('Welcome back,') }} {{ $authAdmin->first_name . ' ' . $authAdmin->last_name . '!' }}</h2>
</div> --}}

{{-- dashboard information start --}}
@php
if (!is_null($roleInfo)) {
$rolePermissions = json_decode($roleInfo->permissions);
}
@endphp

<div class="row dashboard-items">
  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Revenue', $rolePermissions)))
  <div class="col-md-3">
    <a href="{{ route('admin.monthly_earning') }}">
      <div class="card card-stats card-secondary card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fas fa-dollar-sign"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Lifetime Earnings') }}</p>
                <h4 class="card-title">
                  {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                  {{ $earning->total_revenue }}
                  {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif
  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Earning', $rolePermissions)))
  <div class="col-md-3">
    <a href="{{ route('admin.monthly_profit') }}">
      <div class="card card-stats card-info card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fas fa-dollar-sign"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Total Profit') }}</p>
                <h4 class="card-title">
                  {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                  {{ $earning->total_earning }}
                  {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Earning', $rolePermissions)))
  <div class="col-md-3">
    <a href="{{ route('admin.transcation') }}">
      <div class="card card-stats card-primary card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-exchange-alt"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Total Transcation') }}</p>
                <h4 class="card-title">{{ $transcation_count }}
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif
  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Equipment', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.equipment_management.all_equipment', ['language' => $defaultLang->code]) }}">
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
  @endif

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Equipment Booking', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">
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
  @endif


  {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
      <div class="card card-stats card-success card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-box-alt"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Products') }}</p>
                <h4 class="card-title">{{ $totalProduct }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif --}}

  {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.shop_management.orders') }}">
      <div class="card card-stats card-warning card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-shopping-cart"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Orders') }}</p>
                <h4 class="card-title">{{ $totalOrder }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif --}}

  {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
      <div class="card card-stats card-info card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-blog"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Blog') }}</p>
                <h4 class="card-title">{{ $totalBlog }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif --}}

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.user_management.registered_users') }}">
      <div class="card card-stats card-orchid card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="la flaticon-users"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Users') }}</p>
                <h4 class="card-title">{{ $totalUser }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif

  {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.user_management.subscribers') }}">
      <div class="card card-stats card-dark card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-bell"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Subscribers') }}</p>
                <h4 class="card-title">{{ $totalSubscriber }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif --}}

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Partners', $rolePermissions)))
  <div class="col-sm-6 col-md-3">
    <a href="{{ route('admin.home_page.partners') }}">
      <div class="card card-stats card-secondary card-round">
        <div class="card-body">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center">
                <i class="fal fa-handshake"></i>
              </div>
            </div>

            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">{{ __('Partners') }}</p>
                <h4 class="card-title">{{ $totalPartner }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endif
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
{{-- dashboard information end --}}
@endsection

@section('script')
{{-- chart js --}}
<script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

<script>
  'use strict';
    const monthArr = {!! json_encode($months) !!};
    const bookingArr = {!! json_encode($bookings) !!};
    const incomeArr = {!! json_encode($incomes) !!};
</script>

<script type="text/javascript" src="{{ asset('assets/js/chart-init.js') }}"></script>
@endsection