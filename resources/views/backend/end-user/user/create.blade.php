@extends('backend.layout')

@section('content')
 <section class="user-dashboard pt-130 pb-120">
    <div class="container">

       <div class="row">

        <div class="col-lg-12">
          <form  action="{{ route('admin.user_management.store') }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('First Name*') }}</label>
                      <input type="text" value="" class="form-control" name="first_name"
                        placeholder="{{ __('Enter First Name') }}">
                        @error('first_name')
                            <p id="editErr_first_name" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Last Name*') }}</label>
                      <input type="text" value="" class="form-control" name="last_name"
                        placeholder="{{ __('Enter Last Name') }}">
                        @error('last_name')
                            <p id="editErr_last_name" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="" class="form-control" name="username"
                        placeholder="{{ __('Enter Username') }}">
                        @error('username')
                            <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Password *') }}</label>
                      <input type="password" value="" class="form-control" name="password"
                        placeholder="{{ __('Enter Password') }} ">
                        @error('password')
                            <p id="editErr_password" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Confirm Password *') }}</label>
                      <input type="password" value="" class="form-control" name="password_confirmation"
                        placeholder="{{ __('Enter Confirm Password') }} ">
                        @error('password')
                            <p id="password_confirmation" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="email" value="" class="form-control" name="email"
                        placeholder="{{ __('Enter Email') }}">
                        @error('email')
                            <p id="editErr_email" class="mt-1 mb-0 text-danger em">{{ $message  }}</p>
                        @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Vendor') }}</label>
                      <select class="form-control select2" name="vendor_id">
                          @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->username }}</option>
                          @endforeach
                      </select>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="" class="form-control" name="contact_number">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  
                  
                  
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <textarea name="address" class="form-control" placeholder="{{ __('Enter Address') }}"></textarea>
                        <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('City') }}</label>
                        <input type="text" value="" class="form-control"
                          name="city" placeholder="{{ __('Enter City') }}">
                        <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('State') }}</label>
                        <input type="text" value="" class="form-control" name="state"
                          placeholder="{{ __('Enter State') }}">
                        <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Country') }}</label>
                        <input type="text" value="" class="form-control"
                          name="country" placeholder="{{ __('Enter Country') }}">
                        <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    
                    

                </div>
                <button type="submit" class="btn btn-primary">Add</button>
              </form>

        </div>
      </div>
    </div>
  </section>

@endsection