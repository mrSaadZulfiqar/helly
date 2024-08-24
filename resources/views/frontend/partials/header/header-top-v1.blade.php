<div class="header-top-bar">
  <div class="container-fluid">
      
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="top-left">
          @if (!empty($basicInfo->address))
            <span><i class="fas fa-map-marker-alt"></i> {{ $basicInfo->address }}</span>
          @endif

          @if (!empty($basicInfo->contact_number))
            <span><a href="{{ 'tel:' . $basicInfo->contact_number }}"><i
                  class="fas fa-phone"></i>{{ $basicInfo->contact_number }}</a></span>
          @endif

          @if (!empty($basicInfo->email_address))
            <span><a href="{{ 'mailTo:' . $basicInfo->email_address }}"><i
                  class="fas fa-envelope"></i>{{ $basicInfo->email_address }}</a></span>
          @endif
        </div>
      </div>

      <div class="col-lg-5">
        <div class="top-right">
          <ul class="d-flex align-items-center justify-content-end">
            <!-- <li>
              <div class="lang-dropdown">
                <div class="lang">
                  <img data-src="{{ asset('assets/img/languages.png') }}" alt="languages" width="25" class="lazy">
                </div>
                <form action="{{ route('change_language') }}" method="GET">
                  <select name="lang_code" onchange="this.form.submit()">
                    @foreach ($allLanguageInfos as $languageInfo)
                      <option value="{{ $languageInfo->code }}"
                        {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                        {{ $languageInfo->name }}
                      </option>
                    @endforeach
                  </select>
                </form>
              </div>
            </li> -->

            <li>
              <a href="{{ route('shop.cart') }}" class="cart-btn">
                <i class="fas fa-shopping-cart"></i><span id="product-count">{{ count($cartItemInfo) }}</span>
              </a>
            </li>

            <!-- code by AG start -->
            @if(!Auth::guard('vendor')->user() && !Auth::guard('driver')->user() && !Auth::guard('web')->user() && !Auth::guard('admin')->user())
            <li>
              <a href="{{ route('user.login') }}" class="" style="color: #fff;">
              {{ __('Login') }}
              </a>
            </li>
            @endguest

            @auth('admin')
            <li class="dropdown">
              <button class="dropdown-toggle" type="button" id="adminDropdown" data-toggle="dropdown"
                aria-expanded="false">
                {{ __('Hi').'! '. Auth::guard('admin')->user()->username }}
              </button>
              <div
                class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                aria-labelledby="adminDropdown">
                
                @auth('admin')
                  <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                  <a class="dropdown-item" href="{{ route('admin.logout') }}">{{ __('Logout') }}</a>
                @endauth
              </div>
            </li>
            @endauth

            <!-- code by AG end -->
            
            @auth('vendor')
            <li class="dropdown">
              <button class="dropdown-toggle" type="button" id="vendorDropdown" data-toggle="dropdown"
                aria-expanded="false">
                {{ __('Hi').'! '. Auth::guard('vendor')->user()->username }}
              </button>
              <div
                class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                aria-labelledby="vendorDropdown">
                <!-- @guest('vendor')
                  <a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Login') }}</a>
                  <a class="dropdown-item" href="{{ route('vendor.signup') }}">{{ __('Signup') }}</a>
                @endguest -->
                @auth('vendor')
                  <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a>
                  <a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a>
                @endauth
              </div>
            </li>
            @endauth

            @auth('driver')
            <!-- code by AG start -->
            <li class="dropdown">
              <button class="dropdown-toggle" type="button" id="driverDropdown" data-toggle="dropdown"
                aria-expanded="false">
                {{ __('Driver') }}
              </button>
              <div
                class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                aria-labelledby="driverDropdown">
                <!-- @guest('driver')
                  <a class="dropdown-item" href="{{ route('driver.login') }}">{{ __('Login') }}</a>
                @endguest -->
                @auth('driver')
                  <a class="dropdown-item" href="{{ route('driver.dashboard') }}">{{ __('Dashboard') }}</a>
                  <a class="dropdown-item" href="{{ route('driver.logout') }}">{{ __('Logout') }}</a>
                @endauth
              </div>
            </li>
            @endauth
            
            <!-- code by AG end -->
            @auth('web')
            <li class="dropdown">
              <button class="dropdown-toggle" type="button" id="customerDropdown" data-toggle="dropdown"
                aria-expanded="false">
                {{ __('Customer') }}
              </button>
              <div
                class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                aria-labelledby="customerDropdown">
                <!-- @guest('web')
                  <a class="dropdown-item" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                  <a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Signup') }}</a>
                @endguest -->
                @auth('web')
                  <a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                  <a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a>
                @endauth
              </div>
            </li>
            @endauth

          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

