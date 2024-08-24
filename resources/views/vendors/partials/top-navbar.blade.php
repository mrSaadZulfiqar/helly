<style>
  .bold-name {
    font-weight: 900;
    font-size: 30px;
  }

  .welcome {
    display: inline-block;
    padding: 5px 20px;
    border-radius: 5px;
    background: #0e2b5c;
    color: #bfbf2b !important;
  }

  .main-panel {
    width: 100%;
  }

  .cs-container {
    margin: 0 30px;
  }

  .top-nav-cstm {
    align-items: center;
    flex-wrap: nowrap;
  }

  .t-btn-active .icon-menu:before {
    content: '\2190';
    font-size: 30px;
  }

  .main-header{
    border-bottom: 1px solid #d9d9d9;
    position: fixed;
  }

  @media only screen and (min-width: 991px) {

    .top-nav-cstm .sidenav-toggler {
      display: none;
    }

    .main-panel>.content {
      /* margin-top: 0; */
      /* background: #eee; */
    }

    .sidebar {
      /* position: absolute !important;
      top: 0px !important; */

      /*left: 200px;*/
    }

  }

  .welcome{
    display: none !important;
  }

  @media only screen and (max-width: 990px) {
    body[data-background-color="white2"] {
      background: #1a2035;
    }

    .nav-toggle-side-cs,
    .mbl-top-right {
      display: none;
    }

    .cs-container {
      margin: 0px;
    }

  }



  .drop-notifications {}

  ul.navbar-nav.topbar-nav {
    align-items: center;
    -ms-flex-direction: row;
    flex-direction: row !important;
  }

  li.nav-item.dropdown.drop-notifications .dropdown-toggle::after {
    display: none;
  }

  li.nav-item.dropdown.drop-notifications a {
    color: #000;
    margin-right: 10px;
  }

  .bell_icon {
    color: var(--primary-color) !important;
    font-size: 20px;
    padding: 15px;
    margin-right: 10px;
  }

  span.noti-count {
    background: white;
    display: block;
    width: 18px;
    height: 18px;
    font-size: 14px;
    text-align: center;
    line-height: 1.2;
    border-radius: 10px;
    color: var(--primary-color);
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
    color: #fff !important;
  }

  li.dropdown.drop-notifications:hover>.dropdown-notification {
    display: block;
  }



  ul.dropdown-notification .noti_mark_read_action {
    position: sticky;
    background: #fff;
    color: #3387e2;
    border: 1px solid;
    bottom: 0;
    border-radius: 5px;
  }

  ul.dropdown-notification .noti_mark_read_action a {
    color: #3387e2 !important;
    font-size: 15px !important;
  }

  ul.dropdown-notification .noti_mark_read_action:hover a {
    color: #fff !important;
  }

  .dropdown-user {
    left: -85px !important;
  }
</style>

<div class="main-header" style="positon:fixed !important;">
  <div class="cs-container">

    <div class="row top-nav-cstm">
      {{-- <div class="col-md-2">
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
          data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon">
            <i class="icon-menu"></i>
          </span>
        </button>
        <div class="nav-toggle-side-cs">
          <button class="btn btn-toggle t-btn">
            <i class="icon-menu"></i>
          </button>
        </div>
      </div> --}}
      <div class="col-md-10">
        <div>
          @if (!empty($websiteInfo->logo))
          <a href="{{ route('index') }}" class="logo d-flex align-items-center" target="_blank">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="navbar-brand" width="40px">
            <h3 style="font-weight: 900" class="text-dark m-0">VENDOR</h3>
          </a>
          @endif
        </div>
      </div>
      <div class="col-md-2">
        <ul style="justify-content: end;width : 100%" class="navbar-nav topbar-nav mbl-top-right">
          <li class="nav-item dropdown drop-notifications">
            <span class="noti-count">{{count(Auth::guard('vendor')->user()->unreadNotifications)}}</span>
            <a class="dropdown-toggle bell_icon" data-toggle="dropdown" href="#" aria-expanded="false">
              <i class="fa fa-bell" aria-hidden="true"></i>

            </a>
            <ul class="dropdown-notification">
              @foreach(Auth::guard('vendor')->user()->unreadNotifications as $notification)
              <li>
                <div class="w-100 d-flex">
                  <?php echo $notification->data['msg']; ?>
                  <a href="{{route('read.notification', $id = $notification->id)}}">Mark Read</a>
                </div>
              </li>
              @endforeach
              <li class="noti_mark_read_action text-center"><a class="text-center"
                  href="{{ route('notifications.mark.read') }}">Mark All As Read</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown hidden-caret">
            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
              <div class="avatar-sm">
                @if (Auth::guard('vendor')->user()->photo != null)
                <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
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
                      @if (Auth::guard('vendor')->user()->photo != null)
                      <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
                        alt="Vendor Image" class="avatar-img rounded-circle">
                      @else
                      <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">
                      @endif
                    </div>

                    <div class="u-text">
                      <h4>
                        {{ Auth::guard('vendor')->user()->username }}
                      </h4>
                      <p class="text-muted">{{ Auth::guard('vendor')->user()->email }}</p>
                    </div>
                  </div>
                </li>

                <li>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item"
                    href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}">
                    {{ __('Edit Profile') }}
                  </a>

                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('vendor.change_password') }}">
                    {{ __('Change Password') }}
                  </a>

                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('vendor.logout') }}">
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