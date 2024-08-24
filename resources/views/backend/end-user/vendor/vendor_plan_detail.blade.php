@extends('backend.layout')

@section('content')
<style>
    ul.price_summary_ag {
    list-style: none;
    margin: 0;
    background: #fff;
    padding: 8px 8px;
    border-radius: 10px;
    margin-top: 5px;
}
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Vendor Plan Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Plan') }}</a>
      </li>
    </ul>
  </div>
  
  
  <div class="row">

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Membership Purchase : ') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
              
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Subscription Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ date_format($vendor_plan->created_at, 'M d, Y') }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Vendor') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $vendor->username }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Price') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $vendor_plan->total }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Paid via') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $vendor_plan->payment_method }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Payment Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                 @if($vendor_plan->payment_status == 'pending')
                    <h2 class="d-inline-block"><span class="badge badge-danger">Pending</span></h2>
                @else
                    <h2 class="d-inline-block"><span class="badge badge-success">Completed</span></h2>
                @endif
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Plan Information') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $plan->name }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Price') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">${{ $plan->price }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Validity') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $plan->validity }} Days</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Trial Days') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ $vendor_plan->trial_days }} Days</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('No of Features') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ count($plan->plan_features) }} Features</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Billing Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $vendor_plan->name  }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $vendor_plan->email  }}</div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-4">
                <strong>{{ __('Contact Number') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $vendor_plan->contact  }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection