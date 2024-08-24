@extends('vendors.layout')

@section('content')
<style>
    .vendor-interest-form-main {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background: #ffffff8a;
}

.vendor-interest-form-main form {
    width: 50%;
    background: #fff;
    margin: 0 auto;
    margin-top: 5%;
    box-shadow: 0px 0px 10px #0009;
    border-radius: 10px;
    padding: 2%;
        height: 75%;
    overflow: auto;
}
a.vendor-interest-close {
    position: sticky;
    font-size: 25px;
    right: 0;
    top: 0;
    float: right;
    background: #fff;
    padding: 15px;
    border: 1px solid;
    border-radius: 10px;
    line-height: 1;
}
.vendor-interest-form-main .equipments_handel_fields>label:first-child {font-size: 20px !IMPORTANT;font-weight: 700;}

.vendor-interest-form-main .equipments_handel_fields label {
    width: 100%;
}

.vendor-interest-form-main .equipments_handel_fields input, .vendor-interest-form-main .equipments_handel_fields select {
    width: 100%;
    padding: 10px 5px;
}
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Category') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Category') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Category') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Category') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Category') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.invoice-system.categories.index', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
            @include('vendors.categories.fields')          
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="categoryForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

