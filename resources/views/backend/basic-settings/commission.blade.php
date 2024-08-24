@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Commission') }}</h4>
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
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Commission') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card">
        <form action="{{ route('admin.basic_setting.update_commission') }}" method="POST">

          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Update Commission') }}</div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 mx-auto">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Equipment Commission') }} (%)</label>
                      <input type="number" class="form-control" name="equipment_commission"
                        value="{{ $commission ? $commission->equipment_commission : '' }}"
                        placeholder="{{ __('Enter Equipment Commission') }}">
                      @if ($errors->has('equipment_commission'))
                        <p class="mb-0 text-danger">{{ $errors->first('equipment_commission') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
