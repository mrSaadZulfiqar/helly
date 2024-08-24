@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Quotation Details') }}</h4>
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
        <a href="#">{{ __('Equipment Quotation') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Quotations') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Quotation Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Quotation No.') . ' ' . '#' . $details->id }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Quotation Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ date_format($details->created_at, 'M d, Y') }}</div>
            </div>
            @php
              $vendor = $details->vusername;
            @endphp
            @if ($vendor)
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Vendor') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">
                  <a target="_blank"
                    href="{{ route('admin.vendor_management.vendor_details', ['id' => $details->vid, 'language' => 'en']) }}">{{ $vendor }}</a>
                </div>
              </div>
            @else
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Vendor') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">
                  <span class="badge badge-success">{{ __('Admin') }}</span>
                </div>
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Quotation Information') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Equipment') . ' :' }}</strong>
              </div>


              <div class="col-lg-8"><a target="_blank"
                  href="{{ route('equipment_details', $equipmentSlug) }}">{{ strlen($equipmentTitle) > 20 ? mb_substr($equipmentTitle, 0, 20, 'UTF-8') . '...' : $equipmentTitle }}</a>
              </div>

            </div>
			
			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->first_name }} {{ $details->last_name }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->email }}</div>
            </div>

			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Company Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->company_name }}</div>
            </div>
			
			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Project Country') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->project_country }}</div>
            </div>
			
			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Project City') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->project_city }}</div>
            </div>

			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Project State') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->project_state }}</div>
            </div>

			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Project Zipcode') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->project_zipcode }}</div>
            </div>

			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Project Start Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->project_startdate }}</div>
            </div>
			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Worker Count') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->worker_count }}</div>
            </div>
			<div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Details') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->details }}</div>
            </div>
            
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Equipment Needed') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->equipment_needed??'' }}</div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection
