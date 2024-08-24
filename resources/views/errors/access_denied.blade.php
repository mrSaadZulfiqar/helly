@extends('frontend.layout')

@php
  $misc = new App\Http\Controllers\FrontEnd\MiscellaneousController();

  $language = $misc->getLanguage();
  $pageHeading = $language->pageName()->select('error_page_title')->first();
  $bgImg = $misc->getBreadcrumb();
@endphp

@section('pageHeading')
 {{"403 Access Denied"}}
@endsection

@section('content')
  @php $pageTitle = !empty($pageHeading) ? $pageHeading->error_page_title : ''; @endphp

  <!--====== 404 PART START ======-->
  <section class="error-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <img src="{{ asset('assets/img/404.png') }}" alt="404" class="not-found-image">
        </div>

        <div class="col-lg-6">
          <div class="error-txt">
            <div class="oops-img-section">
              <img src="{{ asset('assets/img/oops.png') }}" alt="oops">
            </div>

            <h2>{{ __('Access Denied') . '.' }}</h2>
            <p>
              {{ __('upgrade your plan to get access this feature')}}<br>
              </p>

            <a href="{{ route('subscription.index') }}" class="main-btn mt-30">{{ __('Upgrade') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== 404 PART END ======-->
@endsection
