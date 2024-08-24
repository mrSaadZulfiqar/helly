@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Assign To Vendor') }}</h4>
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
        <a href="#">{{ __('Customers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Assign To Vendor') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Assign To Vendor') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form  action="{{ route('admin.user_management.update_assign_to_vendor', ['id' => $user->id]) }}" method="post">
                @csrf
                <div class="form-group">
                  <label>{{ __('Select Vendor') . '*' }}</label>
                  <select name="vendor_id" class="form-control select2">
                      <option @empty($user->vendor_id) selected @endempty value=" ">No Vendor</option>
                      @foreach($vendors as $vendor)
                      <option value="{{ $vendor->id }}" @if($user->vendor_id == $vendor->id) selected @endif>{{ $vendor->username }}</option>
                      @endforeach
                  </select>
                  <p id="editErr_new_password" class="mt-2 mb-0 text-danger em"></p>
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
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection