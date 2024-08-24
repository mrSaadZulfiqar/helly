@extends('frontend.auth-layout')

@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->login_page_title }}
@endif
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_login }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_login }}
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
<!--====== Start Login Area Section ======-->
<div class="user-area-section pt-60 pb-60 login-gradi-bg">
  <div class="container">
    <div class="row justify-content-center" style="align-items:center">

      <div class="col-lg-6">
        @isset($digitalProductStatus)
        @if ($digitalProductStatus == 'no')
        <a href="{{ route('shop.checkout', ['checkout_as' => 'guest']) }}"
          class="btn btn-block btn-warning mb-4 py-3 border-0">
          {{ __('Checkout as Guest') }}
        </a>

        <div class="mb-4 text-center">
          <h3><strong>{{ __('OR') }}</strong></h3>
        </div>
        @endif
        @endisset


        <!-- code by AG start -->



        <!-- code by AG end -->


        <!-- @if ($bs->facebook_login_status == 1 || $bs->google_login_status == 1)
            <div class="mb-5">
              <div class="btn-group btn-group-toggle d-flex">
                @if ($bs->facebook_login_status == 1)
                  <a class="btn py-2 facebook-login-btn" href="{{ route('user.login.facebook') }}">
                    <i
                      class="fab fa-facebook-f {{ $currentLanguageInfo->direction == 0 ? 'mr-2' : 'ml-2' }}"></i>{{ __('Login via Facebook') }}
                  </a>
                @endif

                @if ($bs->google_login_status == 1)
                  <a class="btn py-2 google-login-btn" href="{{ route('user.login.google') }}">
                    <i
                      class="fab fa-google {{ $currentLanguageInfo->direction == 0 ? 'mr-2' : 'ml-2' }}"></i>{{ __('Login via Google') }}
                  </a>
                @endif
              </div>
            </div>
          @endif -->

        <div class="user-form">
          <img src="{{ asset('assets/images/logo.png') }}" style="max-width: 70px; display:block" class="mb-4 mx-auto text-center d-block" alt="logo">
          <h4 class="text-center">Welcome Back 👋 </h4>
          <p class="text-center mb-4">Sign into Customer Account</p>
          <form action="{{ route('user.login_submit') }}" method="POST">
            @csrf
            
            <div class="form_group mb-4">
              <label>{{ __('Email Address') . '*' }}</label>
              <input type="email" class="form_control text-left" placeholder="Enter email address here..." name="email"
                value="{{ old('email') }}">
              @error('email')
              <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="form_group mb-4">
              <label>{{ __('Password') . '*' }}</label>
              <input type="password" class="form_control text-left" placeholder="Enter password here..." name="password"
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
              <button type="submit" class="main-btn {{ $currentLanguageInfo->direction == 1 ? 'ml-4' : 'mr-4' }}">{{__('Login') }}</button>
              <div class="mt-3 text-center">
                <a class="mr-3" href="{{ route('user.signup') }}">{{ __('Create an Account') }}</a>
                <a href="{{ route('user.forget_password') }}">{{ __('Lost your password?') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!--<div class="col-md-7">-->
      <!--          <div class="mb-4 text-center">-->
      <!--        <h3><strong>{{ __('Are You A Vendor Or Driver?') }}</strong></h3>-->
      <!--        </div>-->
      <!--     <div class="row">-->

      <!--        <div class="col-md-6">-->
      <!--            <a href="{{ route('vendor.login') }}"-->
      <!--            class="btn btn-block btn-warning mb-4 py-3 border-0" style="font-weight: 900;font-size: 20px;"> -->
      <!--             <img src="/assets/img/user-cat.png">-->
      <!--            {{ __('Login as Vendor') }}-->

      <!--            </a>-->
      <!--        </div>-->
      <!--        <div class="col-md-6">-->
      <!--            <a href="{{ route('driver.login') }}"-->
      <!--            class="btn btn-block btn-warning mb-4 py-3 border-0" style="font-weight: 900;font-size: 20px;"> -->
      <!--            <img src="/assets/img/driver-cat.png">-->
      <!--            {{ __('Login as Driver') }}-->

      <!--            </a>-->
      <!--        </div>-->
      <!--    </div>-->
      <!--  </div>-->

    </div>
  </div>
</div>
<!--====== End Login Area Section ======-->

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