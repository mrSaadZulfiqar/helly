<div class="header-navigation">
  <div class="primary-menu d-flex align-items-center justify-content-between">
    <div class="site-branding">
      @if (!empty($websiteInfo->logo))
        <a href="{{ route('index') }}" class="brand-logo">
          <img data-src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="website logo" class="lazy">
        </a>
      @endif
    </div>

    <div class="nav-menu">
      <div class="navbar-close"><i class="fal fa-times"></i></div>

      <nav class="main-menu">
        <ul>
          @php $menuDatas = json_decode($menuInfos); @endphp

          @foreach ($menuDatas as $menuData)
            @php $href = get_href($menuData); @endphp

            @if (!property_exists($menuData, 'children'))
              <li class="menu-item">
                <a href="{{ $href }}">{{ $menuData->text }}</a>
              </li>
            @else
              <li class="menu-item menu-item-has-children">
                <a href="{{ $href }}">{{ $menuData->text }}</a>
                <ul class="sub-menu">
                  @php $childMenuDatas = $menuData->children; @endphp

                  @foreach ($childMenuDatas as $childMenuData)
                    @php $child_href = get_href($childMenuData); @endphp

                    <li><a href="{{ $child_href }}">{{ $childMenuData->text }}</a></li>
                  @endforeach
                </ul>
              </li>
            @endif
          @endforeach
        </ul>
      </nav>
    </div>

    <div class="header-right-nav">
      <div class="right-nav-tool d-flex align-items-center justify-content-end">
        <div class="cart-button">
          <a href="{{ route('shop.cart') }}" class="cart-btn">
            <i class="fas fa-shopping-cart"></i><span id="product-count">{{ count($cartItemInfo) }}</span>
          </a>
        </div>

        <ul class="user-info">
          <li class="dropdown">
            <button class="dropdown-toggle" type="button" id="vendorDropdown" data-toggle="dropdown"
              aria-expanded="false">
              {{ __('Vendor') }}
            </button>
            <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif" aria-labelledby="vendorDropdown">
              @guest('vendor')
                <a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Login') }}</a>
                <a class="dropdown-item" href="{{ route('vendor.signup') }}">{{ __('Signup') }}</a>
              @endguest
              @auth('vendor')
                <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a>
                <a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a>
              @endauth
            </div>
          </li>
          <li class="dropdown">
            <button class="dropdown-toggle" type="button" id="customerDropdown" data-toggle="dropdown"
              aria-expanded="false">
              {{ __('Customer') }}
            </button>
            <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif" aria-labelledby="customerDropdown">
              @guest('web')
                <a class="dropdown-item" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                <a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Signup') }}</a>
              @endguest
              @auth('web')
                <a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                <a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a>
              @endauth
            </div>
          </li>
        </ul>

        <div class="navbar-toggler">
          <span></span><span></span><span></span>
        </div>
      </div>
    </div>
  </div>
</div>
