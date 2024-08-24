@extends('frontend.auth-layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->forget_password_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_forget_password }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_forget_password }}
  @endif
@endsection

@section('content')

<style>
    .back-btn{
        padding: 10px 20px;
    font-weight: 900;
    }
    .back-btn:before {
    content: '\2190';
    font-size: 22px;
    padding-right: 10px;
}
</style>
  <!--====== Start Forget Password Area Section ======-->
  <div class="user-area-section pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
            <!--code by AG start-->
            <!--code by AG end-->
          <div class="user-form">
            <img src="{{ asset('assets/images/logo.png') }}" style="max-width: 70px; display:block" class="mb-4 mx-auto text-center d-block" alt="logo">
          <h4 class="text-center">Welcome Back 👋 </h4>
          <p class="text-center mb-4">Reset your password</p>
            <form action="{{ route('user.send_forget_password_mail') }}" method="POST">
              @csrf
              <div class="form_group mb-4">
                <label>{{ __('Email Address') . '*' }}</label>
                <input type="email" class="form_control" placeholder="Enter Your Email Address" name="email" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group form-submit-btn">
                <button type="submit" class="main-btn">{{ __('Proceed') }}</button>
                <div class="mt-3 text-center">
                  <a class="mr-3" href="{{ route('user.signup') }}">{{ __('Create an Account') }}</a>
                  <a href="{{ route('user.login') }}">{{ __('Login to your Account') }}</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Forget Password Area Section ======-->
@endsection
