@extends('frontend.auth-layout')

@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->signup_page_title }}
@endif
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_signup }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_signup }}
@endif
@endsection

@section('content')

<style>
  /* .login-gradi-bg{
    background-image: linear-gradient(to left, #0e2b5c42, #ffffff42);
}
      .login-gradi-bg{
    background-image: linear-gradient(to left, #0e2b5c42, #ffffff42);
} */

  .back-btn {
    padding: 10px 20px;
    font-weight: 900;
  }

  .back-btn:before {
    content: '\2190';
    font-size: 22px;
    padding-right: 10px;
  }

  .form_group label {
    cursor: pointer;
  }

  .account_type_main {
    border: 1px solid #e5e5e5;
    background-color: #0000002b;
    color: #fff;
    border-radius: 5px;
  }

  .cus_label {
    font-size: 12px !important;
  }

  .selected-label {
    color: #000 !important;
    background-color: #fff;
    border-radius: 5px;
  }
</style>
<!--====== Start Signup Area Section ======-->
<div class="user-area-section pt-60 pb-60">
  <div class="container">
    <div class="row justify-content-center" style="align-items: center;">

      <div class="col-lg-6">
        <div class="user-form">
          <img src="{{ asset('assets/images/logo.png') }}" style="max-width: 70px; display:block"
            class="mb-4 mx-auto text-center d-block" alt="logo">
          <h4 class="text-center">Hi There 👋 </h4>
          <p class="text-center mb-4">Sign up for a Customer Account</p>
          <form action="{{ route('user.signup_submit') }}" method="POST">
            @csrf
          
            <div class="form_group mb-4">
              <label>{{ __('Username') . '*' }}</label>
              <input type="text" class="form_control" name="username" placeholder="Username"
                value="{{ old('username') }}">
              @error('username')
              <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            <div class="form_group mb-4">
              <label>{{ __('Email Address') . '*' }}</label>
              <input type="email" class="form_control" name="email" placeholder="Email Address"
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

            <div class="form_group mb-4">
              <label>{{ __('Confirm Password') . '*' }}</label>
              <input type="password" class="form_control" placeholder="Confirm Password" name="password_confirmation"
                value="{{ old('password_confirmation') }}">
              @error('password_confirmation')
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
              <button type="submit" class="main-btn">{{ __('Signup') }}</button>
              <div class="mt-3 text-center">
                <a class="mr-3" href="{{ route('user.login') }}">{{ __('Login to your Account') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--====== End Signup Area Section ======-->

@section('script')
<script>
  $(document).on('change','.account_type',function(){
        let value = $(this).val();
        $('.cus_label').removeClass('selected-label'); // Remove the class from all labels
        if ($(this).is(':checked')) {
            $(this).closest('.form_group').find('.cus_label').addClass('selected-label'); // Add the class to the label associated with the checked radio button
        }
        if(value == 'corperate_account')
        {
            $('#company_name').removeClass('d-none')
        }else{
            $('#company_name').addClass('d-none')
        }
    });
    
</script>
@endsection
@endsection