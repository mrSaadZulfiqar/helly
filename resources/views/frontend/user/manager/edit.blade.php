@extends('frontend.layout')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Dashboard')])

  <!--====== Start Dashboard Section ======-->
  <section class="user-dashboard pt-130 pb-120">
    <div class="container">
        <div class="row">
        @includeIf('frontend.user.side-navbar')
        </div>

       <div class="row">

        <div class="col-lg-12">
          <form id=""
                action="{{ route('user.manager.update',[ 'id' => $manager->id ]) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                
                <h2>Edit Manager</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($manager->image != null)
                          <img src="{{ asset('assets/img/users/' . $manager->image) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                        <!--<p class="mt-2 mb-0 text-warning">{{ __('Image Size 80x80') }}</p>-->
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('First Name*') }}</label>
                      <input type="text" value="{{ $manager->first_name }}" class="form-control" name="first_name"
                        placeholder="{{ __('Enter First Name') }}">
                      <p id="editErr_first_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Last Name*') }}</label>
                      <input type="text" value="{{ $manager->last_name }}" class="form-control" name="last_name"
                        placeholder="{{ __('Enter Last Name') }}">
                      <p id="editErr_last_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" readonly value="{{ $manager->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" readonly value="{{ $manager->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Password*') }}</label>
                      <input type="password"   class="form-control" name="password">
                      @error('password')
                        <p id="editErr_email" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Confirm Password*') }}</label>
                      <input type="password"  class="form-control" name="password_confirmation">
                      @error('password_confirmation')
                        <p id="editErr_email" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $manager->contact_number }}" class="form-control" name="contact_number">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <textarea name="address" class="form-control" placeholder="{{ __('Enter Address') }}">{{ $manager->address }}</textarea>
                        <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('City') }}</label>
                        <input type="text" value="{{ $manager->city }}" class="form-control"
                          name="city" placeholder="{{ __('Enter City') }}">
                        <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('State') }}</label>
                        <input type="text" value="{{ $manager->state }}" class="form-control" name="state"
                          placeholder="{{ __('Enter State') }}">
                        <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Country') }}</label>
                        <input type="text" value="{{ $manager->country }}" class="form-control"
                          name="country" placeholder="{{ __('Enter Country') }}">
                        <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                    

                </div>
                <button class="btn btn-primary" type="submit">Update</button>
              </form>

        </div>
      </div>
    </div>
  </section>
  @endsection