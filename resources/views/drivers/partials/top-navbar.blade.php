<style>
.bold-name{
    font-weight:900;
    font-size:30px;
}
.welcome{
        display: inline-block;
    padding: 5px 20px;
    border-radius: 5px;
    background: #0e2b5c;
    color: #bfbf2b!important;
}
.main-panel{
    width:100%;
}
.cs-container{
    margin:0 30px;
}
.top-nav-cstm{
    align-items:center;
    flex-wrap: nowrap;
}
.t-btn-active .icon-menu:before{
    content:'\2190';
    font-size:30px;
}
@media only screen and (min-width: 991px){
     body[data-background-color="white"] {
 
        /*margin: 50px 200px;*/
          box-shadow: 0px 0px 50px 20px #dddfe7;
    /*    background-image: url('/assets/img/dump-truck-6733577_1280.webp');*/
    /*background-size: contain;*/
    /*background-repeat: repeat-y;*/
    /*background-position: center;*/
    }
    .top-nav-cstm .sidenav-toggler{
        display:none;
    }
        .main-panel>.content {
  
    margin-top: 0;
    background: #fefefe;
    
        box-shadow: 0px 50px 50px 20px #dddfe7;
    
}
.sidebar{
    position:absolute!important;
        top: 0px!important;
   
    /*left: 200px;*/
}

}
@media only screen and (max-width: 990px){
    body[data-background-color="white"] {
        background:#1a2035;
    }
    .nav-toggle-side-cs,.mbl-top-right{
        display:none;
    }
    .cs-container{
     margin:0px;
    }
   
}



.drop-notifications {
}

ul.navbar-nav.topbar-nav{
    align-items: center;
    -ms-flex-direction: row;
    flex-direction: row !important;
}

li.nav-item.dropdown.drop-notifications .dropdown-toggle::after {
    display: none;
}

li.nav-item.dropdown.drop-notifications .bellicon {
    font-size: 20px;
    color: #fff;
    padding: 15px;
    margin-right: 10px;
}
li.nav-item.dropdown.drop-notifications a {
    color: #000;
    margin-right: 10px;
}
span.noti-count {
    background: #3387e2;
    display: block;
    width: 18px;
    height: 18px;
    font-size: 14px;
    text-align: center;
    line-height: 1.2;
    border-radius: 10px;
    color: #fff;
    position: absolute;
    right: 15px;
}
ul.dropdown-notification {
    width: 400px;
    padding: 0;
    height: 300px;
    overflow: auto;
    position: absolute;
    z-index: 9999;
    background: #fff;
    border-radius: 5px;
    left: -300px;
    display: none;
}

ul.dropdown-notification li {
    padding: 10px 20px;
    cursor: pointer;
    border-bottom: 1px solid;
}

ul.dropdown-notification li:hover {
    background: #3387e2;
    color: #fff;
}
li.dropdown.drop-notifications:hover>.dropdown-notification {
    display: block;
}
</style>
<div class="main-header" style="background:#1a2035;    border-radius: 6px 6px 0 0;">
    <div class="cs-container">
    
<div class="row top-nav-cstm">
    <div class="col-md-2">
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
              </span>
            </button>
         <div class="nav-toggle-side-cs">
          <button class="btn btn-toggle t-btn">
            <i class="icon-menu"></i>
          </button>
        </div>
    </div>
    <div class="col-md-8">
             <div class="logo-header"
                data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white2' : 'dark2' }}">
                @if (!empty($websiteInfo->logo))
                  <a href="{{ route('index') }}" class="logo" target="_blank">
                    <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="navbar-brand" width="40px">
                  </a>
                @endif
            </div>
    
    </div>
    <div class="col-md-2">
      <ul class="navbar-nav topbar-nav mbl-top-right" style="float:right">
          
          <li class="nav-item dropdown drop-notifications">
              <span class="noti-count">{{count(Auth::guard('driver')->user()->unreadNotifications)}}</span>
              <a class="dropdown-toggle bellicon" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fal fa-bell" aria-hidden="true"></i>
                
              </a>
              <ul class="dropdown-notification">
                  @foreach(Auth::guard('driver')->user()->unreadNotifications as $notification)
                    <li>
                        <div class="w-100 d-flex">
                            <?php echo $notification->data['msg']; ?>    
                            <a href="{{route('read.notification', $id = $notification->id)}}">Mark Read</a>
                        </div>
                    </li>
                  @endforeach
                
              </ul>
          </li>
          
        <li class="nav-item dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
            <div class="avatar-sm">
              @if (Auth::guard('driver')->user()->image != null)
                <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('driver')->user()->image) }}"
                  alt="Vendor Image" class="avatar-img rounded-circle">
              @else
                <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">
              @endif
            </div>
          </a>

          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if (Auth::guard('driver')->user()->image != null)
                      <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('driver')->user()->image) }}"
                        alt="Vendor Image" class="avatar-img rounded-circle">
                    @else
                      <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                        class="avatar-img rounded-circle">
                    @endif
                  </div>

                  <div class="u-text">
                    <h4>
                      {{ Auth::guard('driver')->user()->username }}
                    </h4>
                    <p class="text-muted">{{ Auth::guard('driver')->user()->email }}</p>
                  </div>
                </div>
              </li>

              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('driver.edit.profile', ['language' => $defaultLang->code]) }}">
                  {{ __('Edit Profile') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('driver.change_password') }}">
                  {{ __('Change Password') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('driver.logout') }}">
                  {{ __('Logout') }}
                </a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>




<!--<div class="main-header">-->
  <!-- Logo Header Start -->
<!--  <div class="logo-header"-->
<!--    data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white' : 'dark2' }}">-->
<!--    @if (!empty($websiteInfo->logo))-->
<!--      <a href="{{ route('index') }}" class="logo" target="_blank">-->
<!--        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="navbar-brand" width="120">-->
<!--      </a>-->
<!--    @endif-->

<!--    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"-->
<!--      aria-expanded="false" aria-label="Toggle navigation">-->
<!--      <span class="navbar-toggler-icon">-->
<!--        <i class="icon-menu"></i>-->
<!--      </span>-->
<!--    </button>-->
<!--    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>-->

<!--    <div class="nav-toggle">-->
<!--      <button class="btn btn-toggle toggle-sidebar">-->
<!--        <i class="icon-menu"></i>-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
  <!-- Logo Header End -->

  <!-- Navbar Header Start -->
<!--  <nav class="navbar navbar-header navbar-expand-lg"-->
<!--    data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white2' : 'dark' }}">-->
      
<!--    <div class="container-fluid jc-fluid">-->
<!--      <ul class="nav nav-primary">-->
<!--         <li class="nav-item @if (request()->routeIs('driver.dashboard')) active @endif">-->
<!--          <a href="{{ route('driver.dashboard') }}">-->
<!--            <i class="la flaticon-paint-palette"></i>-->
<!--            <p>{{ __('Dashboard') }}</p>-->
<!--          </a>-->
<!--        </li>-->
        
<!--        <li-->
<!--          class="nav-item @if (request()->routeIs('driver.equipment_booking.bookings')) active-->
<!--          @elseif (request()->routeIs('driver.equipment_booking.details')) active @endif">-->
<!--          <a href="{{ route('driver.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">-->
<!--            <i class="fal fa-calendar-check"></i>-->
<!--            <p>{{ __('Equipment Bookings') }}</p>-->
<!--          </a>-->
<!--        </li>-->
<!--</ul>-->
<!--</div>-->
        <!--  {{-- search --}}-->
        <!--<div class="row mb-6">-->
        <!--  <div class="col-12">-->
        <!--    <form class="search-form-cstm">-->
        <!--      <div class="form-group py-0">-->
        <!--        <input name="term" type="text" class="form-control sidebar-search text-center ltr"-->
        <!--          placeholder="{{ __('Search Menu Here...') }}">-->
        <!--      </div>-->
        <!--    </form>-->
        <!--  </div>-->
        <!--</div>-->
<!--      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">-->
        <!--<form action="{{ route('vendor.change_theme') }}" class="form-inline mr-3" method="POST">-->

        <!--  @csrf-->
        <!--  <div class="form-group">-->
        <!--    <div class="selectgroup selectgroup-secondary selectgroup-pills">-->
        <!--      <label class="selectgroup-item">-->
        <!--        <input type="radio" name="vendor_theme_version" value="light" class="selectgroup-input"-->
        <!--          {{ Session::get('vendor_theme_version') == 'light' ? 'checked' : '' }} onchange="this.form.submit()">-->
        <!--        <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-sun"></i></span>-->
        <!--      </label>-->

        <!--      <label class="selectgroup-item">-->
        <!--        <input type="radio" name="vendor_theme_version" value="dark" class="selectgroup-input"-->
        <!--          {{ Session::get('vendor_theme_version') == 'dark' ? 'checked' : '' }} onchange="this.form.submit()">-->
        <!--        <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-moon"></i></span>-->
        <!--      </label>-->
        <!--    </div>-->
        <!--  </div>-->
        <!--</form>-->

<!--        <li class="nav-item dropdown hidden-caret">-->
<!--          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">-->
<!--            <div class="avatar-sm">-->
<!--              @if (Auth::guard('driver')->user()->image != null)-->
<!--                <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('driver')->user()->image) }}"-->
<!--                  alt="Vendor Image" class="avatar-img rounded-circle">-->
<!--              @else-->
<!--                <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">-->
<!--              @endif-->
<!--            </div>-->
<!--          </a>-->

<!--          <ul class="dropdown-menu dropdown-user animated fadeIn">-->
<!--            <div class="dropdown-user-scroll scrollbar-outer">-->
<!--              <li>-->
<!--                <div class="user-box">-->
<!--                  <div class="avatar-lg">-->
<!--                    @if (Auth::guard('driver')->user()->image != null)-->
<!--                      <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('driver')->user()->image) }}"-->
<!--                        alt="Vendor Image" class="avatar-img rounded-circle">-->
<!--                    @else-->
<!--                      <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""-->
<!--                        class="avatar-img rounded-circle">-->
<!--                    @endif-->
<!--                  </div>-->

<!--                  <div class="u-text">-->
<!--                    <h4>-->
<!--                      {{ Auth::guard('driver')->user()->username }}-->
<!--                    </h4>-->
<!--                    <p class="text-muted">{{ Auth::guard('driver')->user()->email }}</p>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </li>-->

<!--              <li>-->
<!--                <div class="dropdown-divider"></div>-->
<!--                <a class="dropdown-item" href="{{ route('driver.edit.profile', ['language' => $defaultLang->code]) }}">-->
<!--                  {{ __('Edit Profile') }}-->
<!--                </a>-->

<!--                <div class="dropdown-divider"></div>-->
<!--                <a class="dropdown-item" href="{{ route('driver.change_password') }}">-->
<!--                  {{ __('Change Password') }}-->
<!--                </a>-->

<!--                <div class="dropdown-divider"></div>-->
<!--                <a class="dropdown-item" href="{{ route('driver.logout') }}">-->
<!--                  {{ __('Logout') }}-->
<!--                </a>-->
<!--              </li>-->
<!--            </div>-->
<!--          </ul>-->
<!--        </li>-->
<!--      </ul>-->
<!--    </div>-->
<!--  </nav>-->
  <!-- Navbar Header End -->
<!--</div>-->
