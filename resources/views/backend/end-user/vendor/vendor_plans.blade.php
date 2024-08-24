@extends('backend.layout')

@section('content')
 <div class="page-header">
    <h4 class="page-title">{{ __('Vendors Plan') }}</h4>
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
        <a href="#">{{ __('Vendors Plan') }}</a>
      </li>
    </ul>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end mb-4">
                <a class="btn btn-primary btn-sm text-white" href="{{ route('admin.vendor_management.plan.assign') }}"><i class="fa fa-plus"></i> Assign</a>
            </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Vendors Plan') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.vendor_management.bulk_delete_vendor') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.vendor_management.registered_vendor') }}" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="{{ __('Search By Username or Email ID') }}"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Plan') }}</th>
                        <th scope="col">{{ __('Trial Status') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Validity') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($vendor_plans as $vendor_plan)
                        @php
                            $plan = \App\Models\MembershipPlan::find($vendor_plan->plan_id);
                            $vendor = \App\Models\Vendor::find($vendor_plan->vendor_id);
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="bulk-check" data-val="{{ $vendor_plan->id }}"></td>
                            <td><a href="{{ url('admin/vendor-management/vendor/'. $vendor->id .'/details?language=en') }}">{{ $vendor->username }}</a></td>
                            <td>{{ $plan->name }}</td>
                            <td>
                                @if($vendor_plan->is_trial_active == '1')
                                    <h2 class="d-inline-block"><span class="badge badge-danger">Active</span></h2>
                                @else
                                    <h2 class="d-inline-block"><span class="badge badge-success">Completed</span></h2>
                                @endif
                            </td>
                            <td>
                                @if($vendor_plan->payment_status == 'pending')
                                    <h2 class="d-inline-block"><span class="badge badge-danger">Pending</span></h2>
                                @else
                                    <h2 class="d-inline-block"><span class="badge badge-success">Completed</span></h2>
                                @endif
                            </td>
                            <td>{{ $vendor_plan->expiration_date }}</td>
                            <td>
                                @if($vendor_plan->status == '1')
                                    <h2 class="d-inline-block"><span class="badge badge-success">Active</span></h2>
                                @else
                                    <h2 class="d-inline-block"><span class="badge badge-danger">Expired</span></h2>
                                @endif
                            </td>
                             <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.vendor_management.vendor_plan_detail', ['id' => $vendor_plan->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>
                                
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
                
              {{ $vendor_plans->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection
