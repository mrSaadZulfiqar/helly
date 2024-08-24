@extends('frontend.layout')

@section('pageHeading')
{{ __('Dashboard') }}
@endsection

@section('content')

<!--====== Start Dashboard Section ======-->
<section class="user-dashboard">
  <div class="container-fluid">

    <div style="min-height: 100vh" class="row align-items-stretch">
      <div style="min-height: 100%" class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div style="padding-block:20px">
          <div class="row mb-5">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Account Information') }}</h4>
                  </div>

                  <div class="main-info">
                    <ul class="list first-list">
                      @if ($authUser->first_name != null && $authUser->last_name != null)
                      <li><span>{{ __('Name') . ':' }}</span></li>
                      @endif

                      <li><span>{{ __('Username') . ':' }}</span></li>

                      <li><span>{{ __('Email') . ':' }}</span></li>

                      @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')

                      <li><span>{{ __('Company Name') . ':' }}</span></li>

                      @endif

                      @if ($authUser->contact_number != null)
                      <li><span>{{ __('Phone') . ':' }}</span></li>
                      @endif

                      @if ($authUser->address != null)
                      <li><span>{{ __('Address') . ':' }}</span></li>
                      @endif

                      @if ($authUser->city != null)
                      <li><span>{{ __('City') . ':' }}</span></li>
                      @endif

                      @if ($authUser->state != null)
                      <li><span>{{ __('State') . ':' }}</span></li>
                      @endif

                      @if ($authUser->country != null)
                      <li><span>{{ __('Country') . ':' }}</span></li>
                      @endif
                    </ul>

                    <ul class="list">
                      @if ($authUser->first_name != null && $authUser->last_name != null)
                      <li>{{ $authUser->first_name . ' ' . $authUser->last_name }}</li>
                      @endif

                      <li>{{ $authUser->username }}</li>

                      <li>{{ $authUser->email }}</li>

                      @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')

                      <li>{{ $company->name }}</li>

                      @endif

                      @if ($authUser->contact_number != null)
                      <li>{{ $authUser->contact_number }}</li>
                      @endif

                      @if ($authUser->address != null)
                      <li>{{ $authUser->address }}</li>
                      @endif

                      @if ($authUser->city != null)
                      <li>{{ $authUser->city }}</li>
                      @endif

                      @if ($authUser->state != null)
                      <li>{{ $authUser->state }}</li>
                      @endif

                      @if ($authUser->country != null)
                      <li>{{ $authUser->country }}</li>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <a href="{{ route('user.equipment_bookings') }}" class="d-block">
                <div class="card card-box box-1">
                  <div class="card-info">
                    <h5>{{ __('Total Bookings') }}</h5>
                    <p>{{ $numOfBookings }}</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-6">
              <a href="{{ route('user.product_orders') }}" class="d-block">
                <div class="card card-box box-2">
                  <div class="card-info">
                    <h5>{{ __('Total Orders') }}</h5>
                    <p>{{ $numOfOrders }}</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--====== End Dashboard Section ======-->
@endsection