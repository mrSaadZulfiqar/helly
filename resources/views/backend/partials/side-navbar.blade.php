<style>
  .side-sb {
    height: 100vh !important;
    max-height: 100vh !important;
  }
</style>


<div class="sidebar sidebar-style-2" data-background-color="dark2">
  <div class="sidebar-wrapper scrollbar scrollbar-inner side-sb" style="background-color:var(--primary-color)">
    <div class="sidebar-content">

      {{-- <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('admin')->user()->image != null)
          <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}" alt="Admin Image"
            class="avatar-img rounded-circle">
          @else
          <img src="{{ asset('assets/img/blank_user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}

              @if (is_null($roleInfo))
              <span class="user-level">{{ 'Super Admin' }}</span>
              @else
              <span class="user-level">{{ $roleInfo->name }}</span>
              @endif

              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('admin.edit_profile') }}">
                  <span class="link-collapse">{{ 'Edit Profile' }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.change_password') }}">
                  <span class="link-collapse">{{ 'Change Password' }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.logout') }}">
                  <span class="link-collapse">{{ 'Logout' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div> --}}

      @php
      if (!is_null($roleInfo)) {
      $rolePermissions = json_decode($roleInfo->permissions);
      }
      @endphp

      <ul class="nav nav-primary" style="overflow-x: hidden;">
        {{-- search --}}
        {{-- <div class="row mb-3">
          <div class="col-12">
            <form action="">
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="Search Menu Here...">
              </div>
            </form>
          </div>
        </div> --}}

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
          <a href="{{ route('admin.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ 'Dashboard' }}</p>
          </a>
        </li>
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Route Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.advanceroute')) active @endif">
          <a href="{{ route('admin.advanceroute') }}">
            <i class="fal fa-location"></i>
            <p>{{ 'Route Management' }}</p>
          </a>
        </li>
        @endif
        {{-- menu builder --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Menu Builder', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.menu_builder')) active @endif">
          <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-bars"></i>
            <p>{{ 'Menu Builder' }}</p>
          </a>
        </li>
        @endif

        {{-- equipment --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Equipment', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.equipment_management.categories')) active 
            @elseif (request()->routeIs('admin.equipment_management.create_equipment')) active 
            @elseif (request()->routeIs('admin.equipment_management.all_equipment')) active 
            @elseif (request()->routeIs('admin.equipment_management.create_equipment')) active 
            @elseif (request()->routeIs('admin.equipment_management.edit_equipment')) active @endif">
          <a data-toggle="collapse" href="#equipment">
            <i class="fal fa-truck-container"></i>
            <p>{{ 'Equipment' }}</p>
            <span class="caret"></span>
          </a>

          <div id="equipment" class="collapse 
              @if (request()->routeIs('admin.equipment_management.categories')) show 
              @elseif (request()->routeIs('admin.equipment_management.create_equipment')) show 
              @elseif (request()->routeIs('admin.equipment_management.all_equipment')) show 
              @elseif (request()->routeIs('admin.equipment_management.create_equipment')) show 
              @elseif (request()->routeIs('admin.equipment_management.edit_equipment')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.equipment_management.categories') ? 'active' : '' }}">
                <a href="{{ route('admin.equipment_management.categories', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Categories' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.equipment_management.create_equipment') ? 'active' : '' }}">
                <a href="{{ route('admin.equipment_management.create_equipment') }}">
                  <span class="sub-item">{{ 'Add Equipment' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.equipment_management.all_equipment')) active 
                  @elseif (request()->routeIs('admin.equipment_management.edit_equipment')) active @endif">
                <a href="{{ route('admin.equipment_management.all_equipment', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'All Equipment' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Equipment Quotations', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.equipment_quotations.quotations')) active @endif">
          <a href="{{ route('admin.equipment_quotations.quotations') }}">
            <i class="la flaticon-round"></i>
            <p>{{ 'Equipment Quotations' }}</p>
          </a>
        </li>
        @endif --}}

        {{-- equipment booking --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Equipment Booking', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.equipment_booking.settings.tax_amount')) active 
            @elseif (request()->routeIs('admin.equipment_booking.settings.coupons')) active 
            @elseif (request()->routeIs('admin.equipment_booking.settings.shippingstatus')) active 
            @elseif (request()->routeIs('admin.equipment_booking.settings.shipping_methods')) active 
            @elseif (request()->routeIs('admin.equipment_booking.settings.locations')) active 
            @elseif (request()->routeIs('admin.equipment_booking.settings.guest_checkout_status')) active 
            @elseif (request()->routeIs('admin.equipment_booking.bookings')) active 
            @elseif (request()->routeIs('admin.equipment_booking.details')) active 
            @elseif (request()->routeIs('admin.equipment_booking.report')) active @endif">
          <a data-toggle="collapse" href="#equipment-booking">
            <i class="fal fa-calendar-alt"></i>
            <p>{{ 'Equipment Booking' }}</p>
            <span class="caret"></span>
          </a>

          <div id="equipment-booking" class="collapse 
              @if (request()->routeIs('admin.equipment_booking.settings.tax_amount')) show 
              @elseif (request()->routeIs('admin.equipment_booking.settings.coupons')) show 
              @elseif (request()->routeIs('admin.equipment_booking.settings.shippingstatus')) show 
              @elseif (request()->routeIs('admin.equipment_booking.settings.shipping_methods')) show 
              @elseif (request()->routeIs('admin.equipment_booking.settings.locations')) show 
              @elseif (request()->routeIs('admin.equipment_booking.settings.guest_checkout_status')) show 
              @elseif (request()->routeIs('admin.equipment_booking.bookings')) show 
              @elseif (request()->routeIs('admin.equipment_booking.details')) show 
              @elseif (request()->routeIs('admin.equipment_booking.report')) show @endif">
            <ul class="nav nav-collapse">
              <li class="submenu">
                <a data-toggle="collapse" href="#booking-settings">
                  <span class="sub-item">{{ 'Settings' }}</span>
                  <span class="caret"></span>
                </a>

                <div id="booking-settings"
                  class="collapse 
                    @if (request()->routeIs('admin.equipment_booking.settings.tax_amount')) show 
                    @elseif (request()->routeIs('admin.equipment_booking.settings.coupons')) show 
                    @elseif (request()->routeIs('admin.equipment_booking.settings.shippingstatus')) show
                    @elseif (request()->routeIs('admin.equipment_booking.settings.shipping_methods')) show 
                    @elseif (request()->routeIs('admin.equipment_booking.settings.locations')) show 
                    @elseif (request()->routeIs('admin.equipment_booking.settings.guest_checkout_status')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.equipment_booking.settings.tax_amount') ? 'active' : '' }}">
                      <a href="{{ route('admin.equipment_booking.settings.tax_amount') }}">
                        <span class="sub-item">{{ 'Tax & Commission' }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.equipment_booking.settings.coupons') ? 'active' : '' }}">
                      <a href="{{ route('admin.equipment_booking.settings.coupons') }}">
                        <span class="sub-item">{{ 'Coupons' }}</span>
                      </a>
                    </li>

                    <!-- code by AG start -->
                    <li
                      class="{{ request()->routeIs('admin.equipment_booking.settings.shippingstatus') ? 'active' : '' }}">
                      <a href="{{ route('admin.equipment_booking.settings.shippingstatus') }}">
                        <span class="sub-item">{{ 'Shipping Status' }}</span>
                      </a>
                    </li>
                    <!-- code by AG end -->

                    <li
                      class="{{ request()->routeIs('admin.equipment_booking.settings.shipping_methods') ? 'active' : '' }}">
                      <a href="{{ route('admin.equipment_booking.settings.shipping_methods') }}">
                        <span class="sub-item">{{ 'Shipping Methods' }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.equipment_booking.settings.locations') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.equipment_booking.settings.locations', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ 'Locations' }}</span>
                      </a>
                    </li>

                    <li
                      class="{{ request()->routeIs('admin.equipment_booking.settings.guest_checkout_status') ? 'active' : '' }}">
                      <a href="{{ route('admin.equipment_booking.settings.guest_checkout_status') }}">
                        <span class="sub-item">{{ 'Guest Checkout' }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="@if (request()->routeIs('admin.equipment_booking.bookings')) active 
                  @elseif (request()->routeIs('admin.equipment_booking.details')) active @endif">
                <a href="{{ route('admin.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Bookings' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.equipment_booking.report') ? 'active' : '' }}">
                <a href="{{ route('admin.equipment_booking.report') }}">
                  <span class="sub-item">{{ 'Report' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Security Deposit Refunds', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.security-deposit-refunds')) active @endif">
          <a href="{{ route('admin.security-deposit-refunds') }}">
            <i class="fal fa-undo"></i>
            <p>{{ 'Security Deposit Refunds' }}</p>
          </a>
        </li>
        @endif
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Dispute Request', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.security-deposit.dispute-request')) active @endif">
          <a href="{{ route('admin.security-deposit.dispute-request') }}">
            <i class="fal fa-gavel"></i>
            <p>{{ 'Dispute Request' }}</p>
          </a>
        </li>
        @endif
        {{-- shop --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.shop_management.tax_amount')) active 
            @elseif (request()->routeIs('admin.shop_management.shipping_charges')) active 
            @elseif (request()->routeIs('admin.shop_management.coupons')) active 
            @elseif (request()->routeIs('admin.shop_management.product.categories')) active 
            @elseif (request()->routeIs('admin.shop_management.products')) active 
            @elseif (request()->routeIs('admin.shop_management.select_product_type')) active 
            @elseif (request()->routeIs('admin.shop_management.create_product')) active 
            @elseif (request()->routeIs('admin.shop_management.edit_product')) active 
            @elseif (request()->routeIs('admin.shop_management.orders')) active 
            @elseif (request()->routeIs('admin.shop_management.order.details')) active 
            @elseif (request()->routeIs('admin.shop_management.report')) active @endif">
          <a data-toggle="collapse" href="#shop">
            <i class="fal fa-store-alt"></i>
            <p>{{ 'Shop Management' }}</p>
            <span class="caret"></span>
          </a>

          <div id="shop" class="collapse 
              @if (request()->routeIs('admin.shop_management.tax_amount')) show 
              @elseif (request()->routeIs('admin.shop_management.shipping_charges')) show 
              @elseif (request()->routeIs('admin.shop_management.coupons')) show 
              @elseif (request()->routeIs('admin.shop_management.product.categories')) show 
              @elseif (request()->routeIs('admin.shop_management.products')) show 
              @elseif (request()->routeIs('admin.shop_management.select_product_type')) show 
              @elseif (request()->routeIs('admin.shop_management.create_product')) show 
              @elseif (request()->routeIs('admin.shop_management.edit_product')) show 
              @elseif (request()->routeIs('admin.shop_management.orders')) show 
              @elseif (request()->routeIs('admin.shop_management.order.details')) show 
              @elseif (request()->routeIs('admin.shop_management.report')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.shop_management.tax_amount') ? 'active' : '' }}">
                <a href="{{ route('admin.shop_management.tax_amount') }}">
                  <span class="sub-item">{{ 'Tax Amount' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.shop_management.shipping_charges') ? 'active' : '' }}">
                <a href="{{ route('admin.shop_management.shipping_charges', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Shipping Charges' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.shop_management.coupons') ? 'active' : '' }}">
                <a href="{{ route('admin.shop_management.coupons') }}">
                  <span class="sub-item">{{ 'Coupons' }}</span>
                </a>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#product">
                  <span class="sub-item">{{ 'Manage Products' }}</span>
                  <span class="caret"></span>
                </a>

                <div id="product" class="collapse 
                    @if (request()->routeIs('admin.shop_management.product.categories')) show 
                    @elseif (request()->routeIs('admin.shop_management.products')) show 
                    @elseif (request()->routeIs('admin.shop_management.select_product_type')) show 
                    @elseif (request()->routeIs('admin.shop_management.create_product')) show 
                    @elseif (request()->routeIs('admin.shop_management.edit_product')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.shop_management.product.categories') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.shop_management.product.categories', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ 'Categories' }}</span>
                      </a>
                    </li>

                    <li class="@if (request()->routeIs('admin.shop_management.products')) active 
                        @elseif (request()->routeIs('admin.shop_management.select_product_type')) active 
                        @elseif (request()->routeIs('admin.shop_management.create_product')) active 
                        @elseif (request()->routeIs('admin.shop_management.edit_product')) active @endif">
                      <a href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ 'Products' }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="@if (request()->routeIs('admin.shop_management.orders')) active 
                  @elseif (request()->routeIs('admin.shop_management.order.details')) active @endif">
                <a href="{{ route('admin.shop_management.orders') }}">
                  <span class="sub-item">{{ 'Orders' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.shop_management.report') ? 'active' : '' }}">
                <a href="{{ route('admin.shop_management.report') }}">
                  <span class="sub-item">{{ 'Report' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif --}}

        {{-- user --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Customers Management', $rolePermissions)))
        {{-- dashboard --}}
        <!--<li-->
        <!--  class="nav-item-->
        <!--@if (request()->routeIs('admin.user_management.registered_users')) active -->
        <!--    @elseif (request()->routeIs('admin.user_management.user.details')) active -->
        <!--    @elseif (request()->routeIs('admin.user_management.user.change_password')) active @endif-->
        <!--">-->
        <!--  <a href="{{ route('admin.user_management.registered_users') }}">-->
        <!--    <i class="fal fa-users"></i>-->
        <!--    <p>{{ 'Customers Management' }}</p>-->
        <!--  </a>-->
        <!--</li>-->

        <li class="nav-item  @if (request()->routeIs('admin.user_management.registered_users')) active 
              @elseif (request()->routeIs('admin.user_management.user.details')) active 
              @elseif (request()->routeIs('admin.user_management.user.change_password')) active
              @elseif (request()->routeIs('admin.user_management.corporate_accounts')) active
              @elseif (request()->routeIs('admin.user_management.branches')) active
              @elseif (request()->routeIs('admin.user_management.managers')) active
              @endif">
          <a data-toggle="collapse" href="#customer_management">
            <i class="fal fa-users-crown"></i>
            <p>{{ 'Customers Management' }}</p>
            <span class="caret"></span>
          </a>

          <div id="customer_management" class="collapse
               @if (request()->routeIs('admin.user_management.registered_users')) show 
              @elseif (request()->routeIs('admin.user_management.user.details')) show 
              @elseif (request()->routeIs('admin.user_management.user.change_password')) show
              @elseif (request()->routeIs('admin.user_management.corporate_accounts')) show
              @elseif (request()->routeIs('admin.user_management.branches')) show
              @elseif (request()->routeIs('admin.user_management.managers')) show
              @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.user_management.registered_users')) active @endif">
                <a href="{{ route('admin.user_management.registered_users') }}">
                  <span class="sub-item">{{ 'All Customers' }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.user_management.corporate_accounts')) active @endif">
                <a href="{{ route('admin.user_management.corporate_accounts') }}">
                  <span class="sub-item">{{ 'Corporate Accounts' }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.user_management.branches')) active @endif">
                <a href="{{ route('admin.user_management.branches') }}">
                  <span class="sub-item">{{ 'Branches' }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.user_management.managers')) active @endif">
                <a href="{{ route('admin.user_management.managers') }}">
                  <span class="sub-item">{{ 'Managers' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- vendor --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.vendor_management.registered_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.add_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
            @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
            @elseif (request()->routeIs('admin.vendor_management.settings')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active
			@elseif (request()->routeIs('admin.vendor_management.leads')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor_plans')) active
            @elseif (request()->routeIs('admin.vendor_management.drivers')) active
             @elseif (request()->routeIs('admin.vendor_management.plan.assign')) active  @endif">
          <a data-toggle="collapse" href="#vendor">
            <i class="fal fa-users-crown"></i>
            <p>{{ 'Vendors Management' }}</p>
            <span class="caret"></span>
          </a>

          <div id="vendor" class="collapse
              @if (request()->routeIs('admin.vendor_management.registered_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor_details')) show
              @elseif (request()->routeIs('admin.edit_management.vendor_edit')) show
              @elseif (request()->routeIs('admin.vendor_management.add_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.settings')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) show
			  @elseif (request()->routeIs('admin.vendor_management.leads')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor_plans')) show
              @elseif (request()->routeIs('admin.vendor_management.drivers')) show
              @elseif (request()->routeIs('admin.vendor_management.plan.assign')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.vendor_management.settings')) active @endif">
                <a href="{{ route('admin.vendor_management.settings') }}">
                  <span class="sub-item">{{ 'Settings' }}</span>
                </a>
              </li>

              <!-- code by AG start -->
              <li class="@if (request()->routeIs('admin.vendor_management.leads')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                  @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                <a href="{{ route('admin.vendor_management.leads') }}">
                  <span class="sub-item">{{ 'Leads' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.vendor_management.drivers')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                  @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                <a href="{{ route('admin.vendor_management.drivers') }}">
                  <span class="sub-item">{{ 'Drivers' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin_dialer.call')) active @endif">
                <a href="{{ route('admin_dialer.call') }}">
                  <span class="sub-item">{{ 'Dialer' }}</span>
                </a>
              </li>
              <!-- code by AG end -->

              <li class="@if (request()->routeIs('admin.vendor_management.registered_vendor')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                  @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                <a href="{{ route('admin.vendor_management.registered_vendor') }}">
                  <span class="sub-item">{{ 'Registered vendors' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.vendor_management.vendor_plans')) active @endif">
                <a href="{{ route('admin.vendor_management.vendor_plans') }}">
                  <span class="sub-item">{{ 'Vendors Plan' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.vendor_management.add_vendor')) active
                           @elseif(request()->routeIs('admin.vendor_management.plan.assign')) active @endif">
                <a href="{{ route('admin.vendor_management.add_vendor') }}">
                  <span class="sub-item">{{ 'Add vendor' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- Membership Plan --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
        <li hidden class="nav-item @if (request()->routeIs('admin.plans.index')) active
            @elseif(request()->routeIs('admin.plans.create')) active
            @endif
            ">
          <a data-toggle="collapse" href="#plans">
            <i class="fal fa-users-crown"></i>
            <p>{{ 'Membership Plan' }}</p>
            <span class="caret"></span>
          </a>

          <div id="plans" class="collapse @if (request()->routeIs('admin.plans.index')) show
            @elseif(request()->routeIs('admin.plans.create')) show
            @endif">
            <ul class="nav nav-collapse ">
              <li class="@if (request()->routeIs('admin.plans.index')) active @endif">
                <a href="{{ route('admin.plans.index') }}">
                  <span class="sub-item">{{ 'All Plans' }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.plans.create')) active @endif">
                <a href="{{ route('admin.plans.create') }}">
                  <span class="sub-item">{{ 'Add Plans' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- Plan Features --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
        <li hidden class="nav-item @if (request()->routeIs('admin.features.index')) active
            @elseif(request()->routeIs('admin.features.index')) active
            @endif
            ">
          <a data-toggle="collapse" href="#plan_features">
            <i class="fal fa-users-crown"></i>
            <p>{{ 'Plan Features' }}</p>
            <span class="caret"></span>
          </a>

          <div id="plan_features" class="collapse @if (request()->routeIs('admin.features.index')) show
            @elseif(request()->routeIs('admin.features.index')) show
            @endif">
            <ul class="nav nav-collapse ">
              <li class="@if (request()->routeIs('admin.features.index')) active @endif">
                <a href="{{ route('admin.features.index') }}">
                  <span class="sub-item">{{ 'All Feature' }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.features.create')) active @endif">
                <a href="{{ route('admin.features.create') }}">
                  <span class="sub-item">{{ 'Add Feature' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- subscriber --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscribers', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.user_management.subscribers')) active 
                  @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
          <a href="{{ route('admin.user_management.subscribers') }}">
            <i class="fal fa-user-clock"></i>
            <p>{{ 'Subscribers' }}</p>
          </a>
        </li>
        @endif --}}
        {{-- user --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Push Notification', $rolePermissions)))
        <li
          class="nav-item @if (request()->routeIs('admin.user_management.push_notification.settings')) active 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
          <a data-toggle="collapse" href="#push_notification">
            <i class="fal fa-bell"></i>
            <p>{{ 'Push Notification' }}</p>
            <span class="caret"></span>
          </a>

          <div id="push_notification"
            class="collapse 
              @if (request()->routeIs('admin.user_management.push_notification.settings')) show 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.user_management.push_notification.settings') }}">
                  <span class="sub-item">{{ 'Settings' }}</span>
                </a>
              </li>

              <li
                class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                <a href="{{ route('admin.user_management.push_notification.notification_for_visitors') }}">
                  <span class="sub-item">{{ 'Send Notification' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif --}}


        {{-- withdraw method --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Withdraw', $rolePermissions)))
        <li class="nav-item
          @if (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active
          @elseif (request()->routeIs('admin.withdraw.withdraw_request')) active @endif">
          <a data-toggle="collapse" href="#withdraw_method">
            <i class="fal fa-credit-card"></i>
            <p>{{ 'Withdraw' }}</p>
            <span class="caret"></span>
          </a>

          <div id="withdraw_method" class="collapse
            @if (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) show
            @elseif (request()->routeIs('admin.withdraw.withdraw_request')) show @endif">
            <ul class="nav nav-collapse">
              <li
                class="{{ request()->routeIs('admin.withdraw.payment_method') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Payment Methods' }}</span>
                </a>
              </li>

              <li
                class="{{ request()->routeIs('admin.withdraw.withdraw_request') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Withdraw Requests' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif --}}

        {{-- announcement popup --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transactions', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.transcation')) active @endif">
          <a href="{{ route('admin.transcation') }}">
            <i class="fal fa-exchange-alt"></i>
            <p>{{ 'Transactions' }}</p>
          </a>
        </li>
        @endif

        {{-- home page --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Home Page', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.home_page.hero_section.slider_version')) active 
            @elseif (request()->routeIs('admin.home_page.hero_section.static_version')) active 
            @elseif (request()->routeIs('admin.home_page.about_section')) active 
            @elseif (request()->routeIs('admin.home_page.work_process_section')) active 
            @elseif (request()->routeIs('admin.home_page.feature_section')) active 
            @elseif (request()->routeIs('admin.home_page.counter_section')) active 
            @elseif (request()->routeIs('admin.home_page.equipment_section')) active 
            @elseif (request()->routeIs('admin.home_page.testimonial_section')) active 
            @elseif (request()->routeIs('admin.home_page.product_section')) active 
            @elseif (request()->routeIs('admin.home_page.call_to_action_section')) active 
            @elseif (request()->routeIs('admin.home_page.blog_section')) active 
            @elseif (request()->routeIs('admin.home_page.section_customization')) active 
            @elseif (request()->routeIs('admin.home_page.partners')) active @endif">
          <a data-toggle="collapse" href="#home_page">
            <i class="fal fa-layer-group"></i>
            <p>{{ 'Home Page' }}</p>
            <span class="caret"></span>
          </a>

          <div id="home_page" class="collapse 
              @if (request()->routeIs('admin.home_page.hero_section.slider_version')) show 
              @elseif (request()->routeIs('admin.home_page.hero_section.static_version')) show 
              @elseif (request()->routeIs('admin.home_page.about_section')) show 
              @elseif (request()->routeIs('admin.home_page.work_process_section')) show 
              @elseif (request()->routeIs('admin.home_page.feature_section')) show 
              @elseif (request()->routeIs('admin.home_page.counter_section')) show 
              @elseif (request()->routeIs('admin.home_page.equipment_section')) show 
              @elseif (request()->routeIs('admin.home_page.testimonial_section')) show 
              @elseif (request()->routeIs('admin.home_page.product_section')) show 
              @elseif (request()->routeIs('admin.home_page.call_to_action_section')) show 
              @elseif (request()->routeIs('admin.home_page.blog_section')) show 
              @elseif (request()->routeIs('admin.home_page.section_customization')) show 
              @elseif (request()->routeIs('admin.home_page.partners')) show @endif">
            <ul class="nav nav-collapse">
              <li class="submenu">
                <a data-toggle="collapse" href="#hero_section">
                  <span class="sub-item">{{ 'Hero Section' }}</span>
                  <span class="caret"></span>
                </a>

                <div id="hero_section" class="collapse 
                    @if (request()->routeIs('admin.home_page.hero_section.slider_version')) show 
                    @elseif (request()->routeIs('admin.home_page.hero_section.static_version')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.home_page.hero_section.slider_version') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.home_page.hero_section.slider_version', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ 'Slider Version' }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.home_page.hero_section.static_version') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.home_page.hero_section.static_version', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ 'Static Version' }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.about_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.about_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'About Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.work_process_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.work_process_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Work Process Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.feature_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.feature_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Feature Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.counter_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.counter_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Counter Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.equipment_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.equipment_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Equipment Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.testimonial_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.testimonial_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Testimonial Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.product_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.product_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Product Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.call_to_action_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.call_to_action_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Call To Action Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.blog_section') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.blog_section', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Blog Section' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.section_customization') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.section_customization') }}">
                  <span class="sub-item">{{ __('Section Show') . '/' . __('Hide') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.home_page.partners') ? 'active' : '' }}">
                <a href="{{ route('admin.home_page.partners') }}">
                  <span class="sub-item">{{ 'Partners' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- Support Ticket --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.support_ticket.setting')) active
            @elseif (request()->routeIs('admin.support_tickets')) active
            @elseif (request()->routeIs('admin.support_tickets.message')) active active @endif">
          <a data-toggle="collapse" href="#support_ticket">
            <i class="la flaticon-web-1"></i>
            <p>{{ 'Support Tickets' }}</p>
            <span class="caret"></span>
          </a>

          <div id="support_ticket" class="collapse
              @if (request()->routeIs('admin.support_ticket.setting')) show
              @elseif (request()->routeIs('admin.support_tickets')) show
              @elseif (request()->routeIs('admin.support_tickets.message')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.support_ticket.setting')) active @endif">
                <a href="{{ route('admin.support_ticket.setting') }}">
                  <span class="sub-item">{{ 'Setting' }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets') }}">
                  <span class="sub-item">{{ 'All Tickets' }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 1 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 1]) }}">
                  <span class="sub-item">{{ 'Pending Tickets' }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 2 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 2]) }}">
                  <span class="sub-item">{{ 'Open Tickets' }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 3 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 3]) }}">
                  <span class="sub-item">{{ 'Closed Tickets' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- footer --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Footer', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.footer.logo_and_image')) active 
            @elseif (request()->routeIs('admin.footer.content')) active 
            @elseif (request()->routeIs('admin.footer.quick_links')) active @endif">
          <a data-toggle="collapse" href="#footer">
            <i class="fal fa-shoe-prints"></i>
            <p>{{ 'Footer' }}</p>
            <span class="caret"></span>
          </a>

          <div id="footer" class="collapse @if (request()->routeIs('admin.footer.logo_and_image')) show 
              @elseif (request()->routeIs('admin.footer.content')) show 
              @elseif (request()->routeIs('admin.footer.quick_links')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.footer.logo_and_image') ? 'active' : '' }}">
                <a href="{{ route('admin.footer.logo_and_image') }}">
                  <span class="sub-item">{{ 'Logo & Image' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.footer.content') ? 'active' : '' }}">
                <a href="{{ route('admin.footer.content', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Content' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.footer.quick_links') ? 'active' : '' }}">
                <a href="{{ route('admin.footer.quick_links', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Quick Links' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- custom page --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Custom Pages', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.custom_pages')) active 
            @elseif (request()->routeIs('admin.custom_pages.create_page')) active 
            @elseif (request()->routeIs('admin.custom_pages.edit_page')) active @endif">
          <a href="{{ route('admin.custom_pages', ['language' => $defaultLang->code]) }}">
            <i class="la flaticon-file"></i>
            <p>{{ 'Custom Pages' }}</p>
          </a>
        </li>
        @endif

        {{-- blog --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.blog_management.categories')) active 
            @elseif (request()->routeIs('admin.blog_management.blogs')) active 
            @elseif (request()->routeIs('admin.blog_management.create_blog')) active 
            @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
          <a data-toggle="collapse" href="#blog">
            <i class="fal fa-blog"></i>
            <p>{{ 'Blog Management' }}</p>
            <span class="caret"></span>
          </a>

          <div id="blog" class="collapse 
              @if (request()->routeIs('admin.blog_management.categories')) show 
              @elseif (request()->routeIs('admin.blog_management.blogs')) show 
              @elseif (request()->routeIs('admin.blog_management.create_blog')) show 
              @elseif (request()->routeIs('admin.blog_management.edit_blog')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.blog_management.categories') ? 'active' : '' }}">
                <a href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Categories' }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.blog_management.blogs')) active 
                  @elseif (request()->routeIs('admin.blog_management.create_blog')) active 
                  @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
                <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Blogs' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif --}}

        {{-- faq --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('FAQ Management', $rolePermissions)))
        <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
          <a href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
            <i class="la flaticon-round"></i>
            <p>{{ 'FAQ Management' }}</p>
          </a>
        </li>
        @endif

        {{-- faq --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Contact Page', $rolePermissions)))
        <li class="nav-item {{ request()->routeIs('admin.basic_settings.contact_page') ? 'active' : '' }}">
          <a href="{{ route('admin.basic_settings.contact_page') }}">
            <i class="fas fa-address-book"></i>
            <p>{{ 'Contact Page' }}</p>
          </a>
        </li>
        @endif

        {{-- advertise --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Advertise', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.advertise.settings')) active 
            @elseif (request()->routeIs('admin.advertise.all_advertisement')) active @endif">
          <a data-toggle="collapse" href="#advertise">
            <i class="fab fa-buysellads"></i>
            <p>{{ 'Advertise' }}</p>
            <span class="caret"></span>
          </a>

          <div id="advertise" class="collapse @if (request()->routeIs('admin.advertise.settings')) show 
              @elseif (request()->routeIs('admin.advertise.all_advertisement')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.advertise.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.advertise.settings') }}">
                  <span class="sub-item">{{ 'Settings' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.advertise.all_advertisement') ? 'active' : '' }}">
                <a href="{{ route('admin.advertise.all_advertisement') }}">
                  <span class="sub-item">{{ 'All Advertisement' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- announcement popup --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Announcement Popups', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.announcement_popups')) active 
            @elseif (request()->routeIs('admin.announcement_popups.select_popup_type')) active 
            @elseif (request()->routeIs('admin.announcement_popups.create_popup')) active 
            @elseif (request()->routeIs('admin.announcement_popups.edit_popup')) active @endif">
          <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-bullhorn"></i>
            <p>{{ 'Announcement Popups' }}</p>
          </a>
        </li>
        @endif

        {{-- payment gateway --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Payment Gateways', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.payment_gateways.online_gateways')) active 
            @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) active @endif">
          <a data-toggle="collapse" href="#payment_gateways">
            <i class="la flaticon-paypal"></i>
            <p>{{ 'Payment Gateways' }}</p>
            <span class="caret"></span>
          </a>

          <div id="payment_gateways" class="collapse 
              @if (request()->routeIs('admin.payment_gateways.online_gateways')) show 
              @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.payment_gateways.online_gateways') ? 'active' : '' }}">
                <a href="{{ route('admin.payment_gateways.online_gateways') }}">
                  <span class="sub-item">{{ 'Online Gateways' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.payment_gateways.offline_gateways') ? 'active' : '' }}">
                <a href="{{ route('admin.payment_gateways.offline_gateways') }}">
                  <span class="sub-item">{{ 'Offline Gateways' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- basic settings --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Basic Settings', $rolePermissions)))
        <li class="nav-item 
            @if (request()->routeIs('admin.basic_settings.mail_from_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_templates')) active
            @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active
            @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) active
            @elseif (request()->routeIs('admin.basic_settings.page_headings')) active
            @elseif (request()->routeIs('admin.basic_settings.plugins')) active
            @elseif (request()->routeIs('admin.basic_settings.seo')) active
            @elseif (request()->routeIs('admin.pwa')) active
            @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) active
            @elseif (request()->routeIs('admin.basic_settings.general_settings')) active
            @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) active
            @elseif (request()->routeIs('admin.basic_settings.social_medias')) active 
            @elseif (request()->routeIs('admin.basic_settings.communication_settings')) active @endif">
          <a data-toggle="collapse" href="#basic_settings">
            <i class="la flaticon-settings"></i>
            <p>{{ 'Basic Settings' }}</p>
            <span class="caret"></span>
          </a>

          <div id="basic_settings" class="collapse 
              @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
              @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show
              @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) show
              @elseif (request()->routeIs('admin.basic_settings.page_headings')) show
              @elseif (request()->routeIs('admin.basic_settings.plugins')) show
              @elseif (request()->routeIs('admin.basic_settings.seo')) show
              @elseif (request()->routeIs('admin.pwa')) show
              @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) show
              @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) show
              @elseif (request()->routeIs('admin.basic_settings.general_settings')) show
              @elseif (request()->routeIs('admin.basic_settings.social_medias')) show 
              @elseif (request()->routeIs('admin.basic_settings.communication_settings')) show @endif">
            <ul class="nav nav-collapse">

              <!-- code by AG start -->
              <li class="{{ request()->routeIs('admin.basic_settings.communication_settings') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.communication_settings') }}">
                  <span class="sub-item">{{ 'Communication Settings' }}</span>
                </a>
              </li>
              <!-- code by AG end -->

              <li class="{{ request()->routeIs('admin.basic_settings.general_settings') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.general_settings') }}">
                  <span class="sub-item">{{ 'General Settings' }}</span>
                </a>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#mail-settings">
                  <span class="sub-item">{{ 'Email Settings' }}</span>
                  <span class="caret"></span>
                </a>

                <div id="mail-settings" class="collapse 
                    @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show 
                    @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
                    @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.basic_settings.mail_from_admin') ? 'active' : '' }}">
                      <a href="{{ route('admin.basic_settings.mail_from_admin') }}">
                        <span class="sub-item">{{ 'Mail From Admin' }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.basic_settings.mail_to_admin') ? 'active' : '' }}">
                      <a href="{{ route('admin.basic_settings.mail_to_admin') }}">
                        <span class="sub-item">{{ 'Mail To Admin' }}</span>
                      </a>
                    </li>

                    <li class="@if (request()->routeIs('admin.basic_settings.mail_templates')) active 
                        @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active @endif">
                      <a href="{{ route('admin.basic_settings.mail_templates') }}">
                        <span class="sub-item">{{ 'Mail Templates' }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.recaptcha') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.recaptcha') }}">
                  <span class="sub-item">{{ 'Recaptcha Setting' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.breadcrumb') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.breadcrumb') }}">
                  <span class="sub-item">{{ 'Breadcrumb' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.pwa') ? 'active' : '' }}">
                <a href="{{ route('admin.pwa') }}">
                  <span class="sub-item">{{ 'PWA Setting' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.page_headings') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.page_headings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Page Headings' }}</span>
                </a>
              </li>

              <!--<li class="{{ request()->routeIs('admin.basic_settings.plugins') ? 'active' : '' }}">-->
              <!--  <a href="{{ route('admin.basic_settings.plugins') }}">-->
              <!--    <span class="sub-item">{{ 'Plugins' }}</span>-->
              <!--  </a>-->
              <!--</li>-->

              <li class="{{ request()->routeIs('admin.basic_settings.seo') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.seo', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'SEO Informations' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                  <span class="sub-item">{{ 'Maintenance Mode' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.cookie_alert') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ 'Cookie Alert' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.basic_settings.social_medias') ? 'active' : '' }}">
                <a href="{{ route('admin.basic_settings.social_medias') }}">
                  <span class="sub-item">{{ 'Social Medias' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- admin --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Admin Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif">
          <a data-toggle="collapse" href="#admin">
            <i class="fal fa-users-cog"></i>
            <p>{{ 'Admin Management' }}</p>
            <span class="caret"></span>
          </a>

          <div id="admin" class="collapse 
              @if (request()->routeIs('admin.admin_management.role_permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.role.permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.admin_management.role_permissions')) active 
                  @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif">
                <a href="{{ route('admin.admin_management.role_permissions') }}">
                  <span class="sub-item">{{ 'Role & Permissions' }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                <a href="{{ route('admin.admin_management.registered_admins') }}">
                  <span class="sub-item">{{ 'Registered Admins' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- language --}}
        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Language Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.language_management')) active 
            @elseif (request()->routeIs('admin.language_management.edit_keyword')) active @endif">
          <a href="{{ route('admin.language_management') }}">
            <i class="fal fa-language"></i>
            <p>{{ 'Language Management' }}</p>
          </a>
        </li>
        @endif --}}
      </ul>
    </div>
  </div>
</div>