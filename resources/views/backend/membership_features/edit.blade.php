@extends('backend.layout')

@section('content')
 <div class="page-header">
    <h4 class="page-title">{{ __('Edit Plan Features') }}</h4>
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
        <a href="#">{{ __('Membership') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Plan Features') }}</a>
      </li>
    </ul>
  </div>
  
 <section class="user-dashboard pt-130 pb-120">
    <div class="container">

       <div class="row">

        <div class="col-lg-12">
          <form  action="{{ route('admin.features.update' , $id = $feature->id ) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Feature Name*') }}</label>
                      <input type="text"  class="form-control" name="name"
                        placeholder="{{ __('Enter Feature Name') }}" value="{{ $feature->name }}">
                        @error('name')
                            <p id="editErr_first_name" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>

                   <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('URL*') }}</label>
                      <div class="input-group mb-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">{{ url('/') }}</div>
                        </div>
                        <input type="text" class="form-control" id="inlineFormInputGroup" value="{{ $feature->url }}"  name="url">
                      </div>
                      
                        @error('url')
                            <p id="editErr_last_name" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Status*') }}</label>
                      <select class="form-control" name="status" required>
                          <option value="1" @if($feature->status == '1') selected @endif>Enable</option>
                          <option value="0" @if($feature->status == '0') selected @endif>Disable</option>
                      </select>
                    </div>
                  </div>
                  
                </div>
                <br>
                <div class="d-flex justify-content-end w-100">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>

        </div>
      </div>
    </div>
  </section>

@endsection