@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_forget_password_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_forget_password }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_descriptions_vendor_forget_password }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->vendor_forget_password_page_title : '',
  ])
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
        <div class="col-lg-8">
            <!--code by AG start-->
            <a href="{{ route('user.login') }}" class="btn btn-warning mb-2 back-btn">Back to Customer Login</a>
            <!--code by AG end-->
          <div class="user-form">
            <form action="{{ route('vendor.forget.mail') }}" method="POST">
              @csrf
              <div class="form_group mb-4">
                <!--<label>{{ __('Email Address') . '*' }}</label>-->
                <input type="email" class="form_control" placeholder="Enter your Email Address" name="email" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group">
                <button type="submit" class="main-btn">{{ __('Proceed') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Forget Password Area Section ======-->
@endsection
