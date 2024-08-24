@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Recaptcha') }}</h4>
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
        <a href="#">{{ __('Recaptcha') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Recaptcha') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="recaptchaForm" action="{{ route('admin.basic_settings.store_recaptcha') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Recaptcha Site Key') . '*' }}</label>
                  <br>
                  <input class="form-control" value="{{ $data->google_recaptcha_site_key }}" name="google_recaptcha_site_key">
                 </div>
                <div class="form-group">
                  <label for="">{{ __('Recaptcha Secret Key') . '*' }}</label>
                  <br>
                  <input class="form-control" value="{{ $data->google_recaptcha_secret_key }}" name="google_recaptcha_secret_key">
                 </div>
                <div class="form-group">
                  <label for="">{{ __('Recaptcha Status') . '*' }}</label>
                  <br>
                    <select class="form-control" name="status">
                        <option value="1" @if($data->google_recaptcha_status == 1) selected @endif>Enable</option>
                        <option value="0" @if($data->google_recaptcha_status == 0) selected @endif>Disable</option>
                    </select>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="recaptchaForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection