<style>
    .user-dashboard .user-sidebar .links{
            display: flex;
    justify-content: space-around;
    }
    .user-sidebar:before {
    content: '';
    background-image: url(https://dev2.catdump.com/assets/img/users/trwb.png);
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    height: 80px;
    width: 80px;
    position: absolute;
    top: -25%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    display: block;
}
@media only screen and (max-width: 1200px) {
    .user-dashboard .user-sidebar .links{
         display: block;
    }
 
}
@media only screen and (max-width: 1024px) {

        .user-sidebar:before{
         display:none;
     }
}
</style>

<div style="height: calc(100% - 40px)" class="user-sidebar">
    <div>
        <img style="max-width: 80px" class="mb-4" src="{{ asset("assets/images/logo.png") }}" alt="Logo">
    </div>
    <ul class="links flex-column">
      <li><a href="{{ route('user.dashboard') }}"
          class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">{{ __('Dashboard') }}</a></li>

      <li><a href="{{ route('user.equipment_bookings') }}"
          class="{{ request()->routeIs('user.equipment_bookings') || request()->routeIs('user.equipment_booking.details') ? 'active' : '' }}">{{ __('Equipment Bookings') }}</a>
      </li>

      <!--<li><a href="{{ route('user.product_orders') }}"-->
      <!--    class="{{ request()->routeIs('user.product_orders') || request()->routeIs('user.product_order.details') ? 'active' : '' }}">{{ __('Product Orders') }}</a>-->
      <!--</li>-->
      <li><a href="{{ route('user.edit_profile') }}"
          class="{{ request()->routeIs('user.edit_profile') ? 'active' : '' }}">{{ __('Edit Profile') }}</a></li>

      <li><a href="{{ route('user.change_password') }}"
          class="{{ request()->routeIs('user.change_password') ? 'active' : '' }}">{{ __('Change Password') }}</a>
      </li>
      @if(auth()->user()->account_type == 'corperate_account')
      <li><a href="{{ route('user.branch') }}"
          class="{{ request()->routeIs('user.branch') ? 'active' : '' }}">{{ __('Branches') }}</a>
      </li>
      <li><a href="{{ route('user.manager') }}"
          class="{{ request()->routeIs('user.manager') ? 'active' : '' }}">{{ __('Managers') }}</a>
      </li>
      @endif
      <li><a href="{{ route('user.payment_methods') }}"
          class="{{ request()->routeIs('user.payment_methods') ? 'active' : '' }}">{{ __('Payment Methods') }}</a>
      </li>

      <li><a href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
    </ul>
  </div>

