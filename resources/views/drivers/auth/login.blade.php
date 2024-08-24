@extends('frontend.layout')

@section('pageHeading')
  {{ 'Driver Login' }}
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
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' =>  'Driver Login',
  ])
<style>
   .login-gradi-bg{
    background-image: linear-gradient(to left, #0e2b5c42, #ffffff42);
}
.back-btn{
    padding: 10px 20px;
    font-weight: 900;
}
.back-btn:before{
    content:'\2190';
font-size: 22px;
    padding-right: 10px;
}
</style>
  <!--====== Start Login Area Section ======-->
  <div class="user-area-section pt-120 pb-120 login-gradi-bg">
    <div class="container">
        <div class="row justify-content-center" style="margin-bottom: 60px;">
                 <!--code by AG start-->
            <a href="{{ route('user.login') }}" class="btn btn-warning mb-2 back-btn">Back to Customer Login</a>
            <!--code by AG end-->
        </div>
      <div class="row justify-content-center">
         <div class="col-md-6">
              <img src="https://helly.co/assets/img/hely-log-vectr-fin-removebg.png" style="">
          </div>
        <div class="col-md-6">
          <div class="user-form">
            <form action="{{ route('driver.login_submit') }}" method="POST">
              @csrf
              <div class="form_group lab mb-4">
                <!--<label>{{ __('Email Address') . '*' }}</label>-->
                <input type="email" class="form_control" placeholder="Email Address" name="email" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group lab mb-4">
                <!--<label>{{ __('Password') . '*' }}</label>-->
                <input type="password" class="form_control"  placeholder="Password"  name="password" value="{{ old('password') }}">
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
                  
                <a style="color:#fff" href="{{ route('driver.forget.password') }}">{{ __('Lost your password?') }}</a>
                <button type="submit" class="main-btn mr-4">{{ __('Login') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Login Area Section ======-->
@endsection
