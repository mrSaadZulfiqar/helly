<div class="header-top-bar">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="top-left">
          @if (!empty($basicInfo->address))
            <span><i class="fas fa-map-marker-alt"></i> {{ $basicInfo->address }}</span>
          @endif

          @if (!empty($basicInfo->contact_number))
            <span><a href="{{ 'tel:' . $basicInfo->contact_number }}"><i class="fas fa-phone"></i>{{ $basicInfo->contact_number }}</a></span>
          @endif

          @if (!empty($basicInfo->email_address))
            <span><a href="{{ 'mailTo:' . $basicInfo->email_address }}"><i class="fas fa-envelope"></i>{{ $basicInfo->email_address }}</a></span>
          @endif
        </div>
      </div>

      <div class="col-lg-5">
        <div class="top-right d-flex justify-content-end align-items-center">
          <div class="lang-dropdown">
            <div class="lang">
              <img data-src="{{ asset('assets/img/languages.png') }}" alt="languages" width="25" class="lazy">
            </div>
            <form action="{{ route('change_language') }}" method="GET">
              <select name="lang_code" onchange="this.form.submit()">
                @foreach ($allLanguageInfos as $languageInfo)
                  <option value="{{ $languageInfo->code }}" {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                    {{ $languageInfo->name }}
                  </option>
                @endforeach
              </select>
            </form>
          </div>

          @if (count($socialMediaInfos) > 0)
            <ul class="social-link">
              
              @foreach ($socialMediaInfos as $socialMediaInfo)
                <li>
                  <a href="{{ $socialMediaInfo->url }}" target="_blank">
                    <i class="{{ $socialMediaInfo->icon }}"></i>
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
