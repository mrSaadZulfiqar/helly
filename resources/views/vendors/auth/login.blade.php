@extends('frontend.auth-layout')

@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->vendor_login_page_title }}
@endif
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keywords_vendor_login }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_vendor_login }}
@endif
@endsection

@section('content')

<style>
  .login-gradi-bg {
    background-image: linear-gradient(to left, #0e2b5c42, #ffffff42);
  }

  .back-btn {
    padding: 10px 20px;
    font-weight: 900;
  }

  .back-btn:before {
    content: '\2190';
    font-size: 22px;
    padding-right: 10px;
  }
</style>
<!--====== Start Login Area Section ======-->
<div class="user-area-section pt-60 pb-60">
  <div class="container">


    <div class="row justify-content-center">


      <div class="col-lg-6">
        <div class="user-form">
          <img src="{{ asset('assets/images/logo.png') }}" style="max-width: 70px; display:block"
            class="mb-4 mx-auto text-center d-block" alt="logo">
            <h4 class="text-center">Welcome Back 👋 </h4>
            <p class="text-center mb-4">Sign into Vendor Account</p>
          <form action="{{ route('vendor.login_submit') }}" method="POST">
            @csrf
            <div class="form_group mb-4">
              <label>{{ __('Email Address') . '*' }}</label>
              <input type="email" class="form_control" placeholder="Email Address" name="email"
                value="{{ old('email') }}">
              @error('email')
              <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="form_group mb-4">
              <label>{{ __('Password') . '*' }}</label>
              <input type="password" class="form_control" placeholder="Password" name="password"
                value="{{ old('password') }}">
              @error('password')
              <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            @if($recaptchaInfo->google_recaptcha_status == 1)
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="form_group my-4">
              <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.RECAPTCHA_SITE_KEY') }}"></div>
              @error('g-recaptcha-response')
              <p class="mt-1 text-danger">{{ $message }}</p>
              @enderror
            </div>
            @endif
            <div class="form_group form-submit-btn">
              <button type="submit" class="main-btn mr-4">{{ __('Login') }}</button>
              <div class="mt-3 text-center">
                <a class="mr-3" href="{{ route('vendor.signup') }}">{{ __('Create an Account') }}</a>
                <a href="{{ route('vendor.forget.password') }}">{{ __('Lost your password?') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--====== End Login Area Section ======-->
@endsection