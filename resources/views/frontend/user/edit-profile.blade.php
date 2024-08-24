@extends('frontend.layout')

@section('pageHeading')
  {{ __('Edit Profile') }}
@endsection

@section('content')

  <!-- Start User Edit-Profile Section -->
  <section class="user-dashboard">
    <div class="container-fluid">
       
      <div style="min-height:100vh" class="row">
        <div class="col-lg-3">
          @includeIf('frontend.user.side-navbar')
        </div>
        <div class="col-lg-9">
          <div style="padding-block:20px">
            <div class="row">
              <div class="col-lg-12">
                <div class="user-profile-details">
                  <div class="account-info">
                    <div class="title">
                      <h4>{{ __('Edit Your Profile') }}</h4>
                    </div>
  
                    <div class="edit-info-area">
                      <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-img">
                          <div class="img-box">
                            @if ($authUser->image != null)
                              <img data-src="{{ asset('assets/img/users/' . $authUser->image) }}" alt="user image"
                                class="user-photo lazy">
                            @else
                              <img data-src="{{ asset('assets/img/user.png') }}" alt="user image" class="user-photo lazy">
                            @endif
                          </div>
  
                          <div class="file-upload-area">
                            <div class="upload-file">
                              <input type="file" name="image" class="upload">
                              <span>{{ __('Upload') }}</span>
                            </div>
                          </div>
                        </div>
                        @error('image')
                          <p class="mb-3 text-danger">{{ $message }}</p>
                        @enderror
  
                        <div class="row">
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('First Name') }}"
                              name="first_name" value="{{ $authUser->first_name }}">
                            @error('first_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('Last Name') }}" name="last_name"
                              value="{{ $authUser->last_name }}">
                            @error('last_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-6">
                            <input type="email" class="form_control" placeholder="{{ __('Email') }}"
                              value="{{ $authUser->email }}" readonly>
                          </div>
  
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('Contact Number') }}"
                              name="contact_number" value="{{ $authUser->contact_number }}">
                            @error('contact_number')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-6">
                            <textarea class="form_control" placeholder="{{ __('Address') }}" name="address">{{ $authUser->address }}</textarea>
                            @error('address')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-6">
                              @php
                                  $company = \App\Models\Company::where('customer_id',$authUser->id)->first();
                              @endphp
                              @if(auth()->user()->account_type == 'corperate_account')
                                <input type="text" class="form_control" placeholder="{{ __('Company Name') }}" name="company_name"
                                  value="{{ $company->name }}">
                                @error('company_name')
                                  <p class="text-danger">{{ $message }}</p>
                                @enderror
                              @endif
                            
                            <input type="text" class="form_control" placeholder="{{ __('City') }}" name="city"
                              value="{{ $authUser->city }}">
                            @error('city')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('State') }}" name="state"
                              value="{{ $authUser->state }}">
                          </div>
  
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('Country') }}" name="country"
                              value="{{ $authUser->country }}">
                            @error('country')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
  
                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn form-btn">{{ __('Submit') }}</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End User Edit-Profile Section -->
@endsection
