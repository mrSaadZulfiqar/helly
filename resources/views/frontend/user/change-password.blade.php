@extends('frontend.layout')

@section('pageHeading')
{{ __('Change Password') }}
@endsection

@section('content')

<!-- Start Change Password Section -->
<section class="user-dashboard">
  <div class="container-fluid">

    <div style="min-height: 100vh" class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div style="padding-block: 20px">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Change Password') }}</h4>
                  </div>
  
                  <div class="edit-info-area">
                    <form action="{{ route('user.update_password') }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{ __('Current Password') }}"
                            name="current_password">
                          @error('current_password')
                          <p class="text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>
  
                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{ __('New Password') }}"
                            name="new_password">
                          @error('new_password')
                          <p class="text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>
  
                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{ __('Confirm New Password') }}"
                            name="new_password_confirmation">
                          @error('new_password_confirmation')
                          <p class="text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>
  
                      <div class="row">
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
<!-- End Change Password Section -->
@endsection