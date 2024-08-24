@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->contact_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_contact }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_contact }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->contact_page_title : '',
  ])

  <!--====== Start Contact Section ======-->
  <section class="contact-area pt-130 pb-120">
    <div class="container">
      <div class="row">
          <div class="col-lg-2">
              </div>
        <!--<div class="col-lg-4">-->
        <!--  <div class="nformation-list-item">-->
        <!--    @if (!empty($info->address))-->
        <!--      <div class="information-item mb-50">-->
        <!--        <div class="icon">-->
        <!--          <i class="fas fa-map-marker-alt"></i>-->
        <!--        </div>-->
        <!--        <div class="info">-->
        <!--          <p>{{ $info->address }}</p>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    @endif-->

        <!--    @if (!empty($info->contact_number))-->
        <!--      <div class="information-item mb-50">-->
        <!--        <div class="icon">-->
        <!--          <i class="fas fa-phone"></i>-->
        <!--        </div>-->
        <!--        <div class="info">-->
        <!--          <p><a href="{{ 'tel:' . $info->contact_number }}">{{ $info->contact_number }}</a></p>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    @endif-->

        <!--    @if (!empty($info->email_address))-->
        <!--      <div class="information-item mb-50">-->
        <!--        <div class="icon">-->
        <!--          <i class="fas fa-envelope"></i>-->
        <!--        </div>-->
        <!--        <div class="info">-->
        <!--          <p><a href="{{ 'mailTo:' . $info->email_address }}">{{ $info->email_address }}</a></p>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    @endif-->
        <!--  </div>-->
        <!--</div>-->

        <div class="col-lg-8">
          <div class="contact-wrapper mb-50">
            <div class="section-title text-center mb-30">
              <h2>{{ __('Contact Us') }}</h2>
              <p>Feel Free to Contact Us</p>
            </div>

            <div class="contact-form">
              <form action="{{ route('contact.send_mail') }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-6 mb-4">
                    <div class="form_group">
                      <input name="name" type="text" class="form_control"
                        placeholder="{{ __('Enter Your Full Name') }}">
                    </div>
                    @error('name')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="col-lg-6 mb-4">
                    <div class="form_group">
                      <input name="email" type="email" class="form_control"
                        placeholder="{{ __('Enter Your Email') }}">
                    </div>
                    @error('email')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="col-lg-12 mb-4">
                    <div class="form_group">
                      <input name="subject" type="text" class="form_control"
                        placeholder="{{ __('Enter Email Subject') }}">
                    </div>
                    @error('subject')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="col-lg-12 mb-4">
                    <div class="form_group">
                      <textarea name="message" class="form_control" placeholder="{{ __('Write Your Message') }}"></textarea>
                    </div>
                    @error('message')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                    @if($info->google_recaptcha_status == 1)
                        <div class="col-lg-12 mb-4">
                          <div class="form_group">
                            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                            <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.RECAPTCHA_SITE_KEY') }}"></div>
                          </div>
                          @error('g-recaptcha-response')
                            <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                    @endif


                  <div class="col-lg-12">
                    <button class="main-btn">{{ __('Send') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
           <div class="col-lg-2">
              </div>
      </div>

      <div class="text-center mt-30">
        {!! showAd(3) !!}
      </div>
    </div>
  </section>
  <!--====== End Contact Section ======-->
@endsection
