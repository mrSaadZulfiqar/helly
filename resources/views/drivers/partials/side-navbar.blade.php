<style>
    .side-sb{
        height:100vh!important;
        max-height:100vh!important;
    }
</style>


<div class="sidebar sidebar-style-2"
  data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white' : 'dark2' }}">
  <div class="sidebar-wrapper scrollbar scrollbar-inner side-sb" style="background:#1a2035;">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('driver')->user()->image != null)
            <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('driver')->user()->image) }}"
              alt="Vendor Image" class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('driver')->user()->username }}
              <span class="user-level">{{ __('Driver') }}</span>
              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('driver.edit.profile', ['language' => $defaultLang->code]) }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('driver.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('driver.logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>


      <ul class="nav nav-primary" style="overflow-x: hidden;">
        {{-- search --}}
        <div class="row mb-3">
          <div class="col-12">
            <form>
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="{{ __('Search Menu Here...') }}">
              </div>
            </form>
          </div>
        </div>

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('driver.dashboard')) active @endif">
          <a href="{{ route('driver.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>


        <!--<li-->
        <!--  class="nav-item @if (request()->routeIs('driver.equipment_booking.bookings')) active-->
        <!--  @elseif (request()->routeIs('driver.equipment_booking.details')) active @endif">-->
        <!--  <a href="{{ route('driver.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">-->
        <!--    <i class="fal fa-calendar-check"></i>-->
        <!--    <p>{{ __('Equipment Bookings') }}</p>-->
        <!--  </a>-->
        <!--</li>-->


        <!--<li class="nav-item @if (request()->routeIs('vendor.edit.profile')) active @endif">-->
        <!--  <a href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}">-->
        <!--    <i class="fal fa-user-edit"></i>-->
        <!--    <p>{{ __('Edit Profile') }}</p>-->
        <!--  </a>-->
        <!--</li>-->
        <!--<li class="nav-item @if (request()->routeIs('vendor.change_password')) active @endif">-->
        <!--  <a href="{{ route('vendor.change_password') }}">-->
        <!--    <i class="fal fa-key"></i>-->
        <!--    <p>{{ __('Change Password') }}</p>-->
        <!--  </a>-->
        <!--</li>-->

        <!--<li class="nav-item @if (request()->routeIs('vendor.logout')) active @endif">-->
        <!--  <a href="{{ route('vendor.logout') }}">-->
        <!--    <i class="fal fa-sign-out"></i>-->
        <!--    <p>{{ __('Logout') }}</p>-->
        <!--  </a>-->
        <!--</li>-->
      </ul>
    </div>
  </div>
</div>
