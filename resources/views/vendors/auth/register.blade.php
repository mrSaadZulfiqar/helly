@extends('frontend.auth-layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_signup }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_signup }}
  @endif
@endsection

@section('content')

  <!--====== Start Signup Area Section ======-->
  <!-- code by AG start -->
  <style>
  .form_group.lab.mb-4.equipments_handel_box {
    text-align: left;
    /*color:#fff;*/
}

.equipments_handel_fields label {
    /*text-align: left !important;*/
    color:#fff;
}
/*.equipments_handel_fields_box label {*/
/*    color: #000 !important;*/
/*    background: unset !IMPORTANT;*/
/*    text-align: left !important;*/
/*    width: 100% !IMPORTANT;*/
/*}*/

/*.equipments_handel_fields_box {*/
/*    border: 2px solid #0e2b5c;*/
/*    padding: 15px 15px;*/
/*    margin: 5px 0px;*/
/*}*/
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
  <!-- code by AG end -->
  <div class="user-area-section pt-60 pb-60">
    <div class="container">
         
      <div class="row justify-content-center" style="align-items: center;">
         
        <div class="col-lg-6">
           
          <div class="user-form">
			<img src="{{ asset('assets/images/logo.png') }}" style="max-width: 70px; display:block"
            class="mb-4 mx-auto text-center d-block" alt="logo">
          <h4 class="text-center">Hi There 👋 </h4>
          <p class="text-center mb-4">Sign up for a Vendor Account</p>

            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <form action="{{ route('vendor.signup_submit') }}" method="POST">
              @csrf
              <div class="form_group mb-4">
				<label>{{ __('Name') . '*' }}</label>
                <input type="text" class="form_control" name="name" placeholder="Name" value="{{ old('name') }}">
                @error('name')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>
              <div class="form_group mb-4">
                <label>{{ __('Username') . '*' }}</label>
                <input type="text" class="form_control" name="username"  placeholder="Username" value="{{ old('username') }}">
                @error('username')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
                @if (Session::has('username_error'))
                  <p class="text-danger mt-2">{{ Session::get('username_error') }}</p>
                @endif
              </div>

              <div class="form_group mb-4">
                <label>{{ __('Email Address') . '*' }}</label>
                <input type="email" class="form_control" name="email" placeholder="Email Address" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>
              <div class="form_group mb-4">
                <label>{{ __('Phone') . '*' }}</label>
                <input type="tel" class="form_control" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                @error('phone')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>


              <div class="form_group mb-4">
                <label>{{ __('Password') . '*' }}</label>
                <input type="password" class="form_control" placeholder="Password" name="password" value="{{ old('password') }}">
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
					<a class="mr-3" href="{{ route('vendor.login') }}">{{ __('Login to your Account') }}</a>
				  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Signup Area Section ======-->
@endsection

@section('script')
<script>
$(document).ready(function(){
	$('.equipments_handel').on('change', function(){
		if($(this).prop('checked')){
			$(this).parent().next().show();
		}
		else{
			$(this).parent().next().hide();
		}
	});
});
</script>
@endsection
