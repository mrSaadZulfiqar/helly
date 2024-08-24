<style>
    .footer-area .social-box{
            display: flex;
    justify-content: end;
    gap: 20px;
    align-items: center;
    color: #fff;
    }
    .footer-area ul.social-link li{
        margin:0 5px;
    }
</style>

@if ($footerSectionStatus == 1)
  <footer class="footer-area footer-area-one bg_cover lazy @if (!request()->routeIs('index')) inner-footer @endif" @if (!empty($basicInfo->footer_background_image)) data-bg="{{ asset('assets/img/' . $basicInfo->footer_background_image) }}" @endif>
    <div class="footer-widget">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4">
            <div class="widget about-widget mb-40">
              @if (!empty($basicInfo->footer_logo))
                <a href="{{ route('index') }}" class="brand-logo">
                  <img data-src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}" alt="footer logo" class="lazy" style="width:75px">
                </a>
              @endif

              <p>{{ !empty($footerInfo) ? $footerInfo->about_company : '' }}</p>

          
            </div>
          </div>

          <div class="col-lg-4 col-md-4">
            <div class="widget footer-widget-nav mb-40">
              <h4 class="widget-title">{{ __('Useful Links') }}</h4>

              @if (count($quickLinkInfos) == 0)
                <h6 class="text-light">{{ __('No Link Found') . '!' }}</h6>
              @else
                <ul class="widget-nav">
                  @foreach ($quickLinkInfos as $quickLinkInfo)
                    <li><a href="{{ $quickLinkInfo->url }}">{{ $quickLinkInfo->title }}</a></li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>

          <div class="col-lg-4 col-md-4">
            <div class="widget contact-info-widget mb-40">
              <h4 class="widget-title">{{ __('Contact Us') }}</h4>
              <ul class="contact-info-list">
                @if (!empty($basicInfo->email_address))
                  <li>
                    <div class="icon">
                      <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info">
                      <p><a href="{{ 'mailTo:' . $basicInfo->email_address }}">{{ $basicInfo->email_address }}</a></p>
                    </div>
                  </li>
                @endif

                @if (!empty($basicInfo->contact_number))
                  <li>
                    <div class="icon">
                      <i class="fas fa-phone"></i>
                    </div>
                    <div class="info">
                      <p><a href="{{ 'tel:' . $basicInfo->contact_number }}">{{ $basicInfo->contact_number }}</a></p>
                    </div>
                  </li>
                @endif

                @if (!empty($basicInfo->address))
                  <li>
                    <div class="icon">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info">
                      <p>{{ $basicInfo->address }}</p>
                    </div>
                  </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if (!empty($footerInfo))
      <div class="copyright-area">
        <div class="container">
          <div class="row">
              <div class="col-lg-4">
                   <div class="copyright-text">
                <p>{{ $footerInfo->copyright_text }}</p>
              </div>
                  </div>
            <div class="col-lg-4">
             
            </div>
           <div class="col-lg-4">
                    @if (count($socialMediaInfos) > 0)
                <div class="social-box">
                  <h5 style="color: #fff;    font-size: 12px;font-weight: 400;">{{ __('Follow Us') }}:</h5>
                  <ul class="social-link">
                    @foreach ($socialMediaInfos as $socialMediaInfo)
                      <li>
                        <a href="{{ $socialMediaInfo->url }}" target="_blank">
                          <i class="{{ $socialMediaInfo->icon }}"></i>
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endif
              
            </div>
          </div>
        </div>
      </div>
    @endif
  </footer>
@endif
