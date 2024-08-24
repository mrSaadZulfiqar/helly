<style>
    .side-sb{
        height:100vh!important;
        max-height:100vh!important;
    }
</style>

<div class="sidebar sidebar-style-2"
  data-background-color="dark2" >
  <div class="sidebar-wrapper scrollbar scrollbar-inner side-sb" style="background:var(--primary-color);">
    <div class="sidebar-content">
      
      <ul class="nav nav-primary" style="overflow-x: hidden;">
        {{-- search --}}
        {{-- <div class="row mb-3">
          <div class="col-12">
            <form>
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="{{ __('Search Menu Here...') }}">
              </div>
            </form>
          </div>
        </div> --}}

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
          <a href="{{ route('vendor.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>
        
        <li
          class="nav-item @if (request()->routeIs('vendor.equipment_booking.settings.locations')) active 
            @elseif (request()->routeIs('vendor.equipment_booking.settings.shipping_methods'))  active @endif">
          <a data-toggle="collapse" href="#equipment_setting">
            <i class="fal fa-cog"></i>
            <p>{{ __('Delivery Settings') }}</p>
            <span class="caret"></span>
          </a>

          <div id="equipment_setting"
            class="collapse 
              @if (request()->routeIs('vendor.equipment_booking.settings.locations')) show 
              @elseif (request()->routeIs('vendor.equipment_booking.settings.shipping_methods')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.equipment_booking.settings.locations') ? 'active' : '' }}">
                <a
                  href="{{ route('vendor.equipment_booking.settings.locations', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Warehouse') }}</span>
                </a>
              </li>

              <!--<li-->
              <!--  class="{{ request()->routeIs('vendor.equipment_booking.settings.shipping_methods') ? 'active' : '' }}">-->
              <!--  <a href="{{ route('vendor.equipment_booking.settings.shipping_methods') }}">-->
              <!--    <span class="sub-item">{{ __('Shipping Methods') }}</span>-->
              <!--  </a>-->
              <!--</li>-->
            </ul>
          </div>
        </li>
        
        <li
          class="nav-item @if (request()->routeIs('vendor.driver_management.create_driver')) active 
            @elseif (request()->routeIs('vendor.driver_management.all_drivers')) active 
            @elseif (request()->routeIs('vendor.driver_management.create_driver')) active 
            @elseif (request()->routeIs('vendor.driver_management.edit_driver')) active @endif">
          <a data-toggle="collapse" href="#driver">
            <i class="fal fa-truck-container"></i>
            <p>{{ __('Drivers') }}</p>
            <span class="caret"></span>
          </a>

          <div id="driver"
            class="collapse 
              @if (request()->routeIs('vendor.driver_management.create_driver')) show 
              @elseif (request()->routeIs('vendor.driver_management.all_drivers')) show 
              @elseif (request()->routeIs('vendor.driver_management.create_driver')) show 
              @elseif (request()->routeIs('vendor.driver_management.edit_driver')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.driver_management.create_driver') ? 'active' : '' }}">
                <a href="{{ route('vendor.driver_management.create_driver') }}">
                  <span class="sub-item">{{ __('Add Driver') }}</span>
                </a>
              </li>

              <li
                class="@if (request()->routeIs('vendor.driver_management.all_drivers')) active 
                  @elseif (request()->routeIs('vendor.driver_management.edit_driver')) active @endif">
                <a href="{{ route('vendor.driver_management.all_drivers', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('All Drivers') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        
        <!-- code by AG start -->
        
        <li
          class="nav-item @if (request()->routeIs('vendor.customer_management.create_customer')) active 
            @elseif (request()->routeIs('vendor.customer_management.all_customer')) active 
            @elseif (request()->routeIs('vendor.customer_management.create_customer')) active 
            @elseif (request()->routeIs('vendor.customer_management.edit_customer')) active @endif">
          <a data-toggle="collapse" href="#customer">
            <i class="fal fa-user"></i>
            <p>{{ __('Customer') }}</p>
            <span class="caret"></span>
          </a>

          <div id="customer"
            class="collapse 
              @if (request()->routeIs('vendor.customer_management.create_customer')) show 
              @elseif (request()->routeIs('vendor.customer_management.all_customer')) show 
              @elseif (request()->routeIs('vendor.customer_management.create_customer')) show 
              @elseif (request()->routeIs('vendor.customer_management.edit_customer')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.customer_management.create_customer') ? 'active' : '' }}">
                <a href="{{ route('vendor.customer_management.create_customer') }}">
                  <span class="sub-item">{{ __('Add Customer') }}</span>
                </a>
              </li>

              <li
                class="@if (request()->routeIs('vendor.customer_management.all_customer')) active 
                  @elseif (request()->routeIs('vendor.customer_management.edit_customer')) active @endif">
                <a href="{{ route('vendor.customer_management.all_customer') }}">
                  <span class="sub-item">{{ __('All Customer') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <!-- code by AG end -->

        <li
          class="nav-item @if (request()->routeIs('vendor.equipment_management.create_equipment')) active 
            @elseif (request()->routeIs('vendor.equipment_management.all_equipment')) active 
            @elseif (request()->routeIs('vendor.equipment_management.create_equipment')) active 
            @elseif (request()->routeIs('vendor.equipment_management.edit_equipment')) active @endif">
          <a data-toggle="collapse" href="#equipment">
            <i class="fal fa-truck-container"></i>
            <p>{{ __('Equipment') }}</p>
            <span class="caret"></span>
          </a>

          <div id="equipment"
            class="collapse 
              @if (request()->routeIs('vendor.equipment_management.create_equipment')) show 
              @elseif (request()->routeIs('vendor.equipment_management.all_equipment')) show 
              @elseif (request()->routeIs('vendor.equipment_management.create_equipment')) show 
              @elseif (request()->routeIs('vendor.equipment_management.edit_equipment')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.equipment_management.create_equipment') ? 'active' : '' }}">
                <a href="{{ route('vendor.equipment_management.create_equipment') }}">
                  <span class="sub-item">{{ __('Add Equipment') }}</span>
                </a>
              </li>

              <li
                class="@if (request()->routeIs('vendor.equipment_management.all_equipment')) active 
                  @elseif (request()->routeIs('vendor.equipment_management.edit_equipment')) active @endif">
                <a href="{{ route('vendor.equipment_management.all_equipment', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('All Equipment') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        
        
        
        <li class="nav-item @if (request()->routeIs('vendor.advanceroute')) active @endif">
          <a href="{{ route('vendor.advanceroute') }}">
            <i class="fal fa-map"></i>
            <p>{{ __('Route Management') }}</p>
          </a>
        </li>
        
        <?php
        // $email = Auth::guard('vendor')->user()->email;
        // $password = Auth::guard('vendor')->user()->password;
        // $username = Auth::guard('vendor')->user()->username;
        // $phone = Auth::guard('vendor')->user()->phone;
        
        // $vendor_info = \App\Models\VendorInfo::where('vendor_id', Auth::guard('vendor')->user()->id)->where('language_id',8)->first();
        // if($vendor_info->name != ''){
        //     $explode_name = explode(" ", $vendor_info->name);
        //     $f_name = $explode_name[0];
        //     unset($explode_name[0]);
        //     $l_name = implode(" ", $explode_name);
        // }
        
        
        // $access_details = array(
        //     'email' => $email,
        //     'password' => 'Invoice@'.$username,
        //     'first_name' => $f_name??'-',
        //     'last_name' => $l_name??'-',
        //     'contact' => $phone
        //     );
        // $encrypted_access_token = base64_encode(serialize($access_details));
        // echo '<pre>';
        // print_r( unserialize(base64_decode($eny)) ); die;
        ?>

        {{-- <li class="nav-item ">            
          <a target="_blank" href="">
            <i class="fal fa-file-spreadsheet"></i>
            <p>{{ __('Invoice System') }}</p>
          </a>
        </li> --}}

        <!-- <li
        class="nav-item @if (request()->routeIs('vendor.invoice-system.*')) active  @endif">
        <a data-toggle="collapse" href="#invoice-system">
          <i class="fal fa-file-spreadsheet"></i>
          <p>{{ __('Invoice System') }}</p>
          <span class="caret"></span>
        </a>

        <div id="invoice-system"
          class="collapse 
            @if (request()->routeIs('vendor.customer_management.create_customer')) show 
            @elseif (request()->routeIs('vendor.customer_management.all_customer')) show 
            @elseif (request()->routeIs('vendor.customer_management.create_customer')) show 
            @elseif (request()->routeIs('vendor.customer_management.edit_customer')) show @endif">
          <ul class="nav nav-collapse">

            <li class="{{ request()->routeIs('vendor.invoice-system.categories.*') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice-system.categories.index') }}">
                <span class="sub-item">{{ __('Categories') }}</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('vendor.invoice-system.products.*') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice-system.products.index') }}">
                <span class="sub-item">{{ __('Products') }}</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('vendor.invoice-system.taxes.*') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice-system.taxes.index') }}">
                <span class="sub-item">{{ __('Taxes') }}</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('vendor.invoice-system.quotations.*') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice-system.quotations.index') }}">
                <span class="sub-item">{{ __('Quotations') }}</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('vendor.invoice-system.invoices.*') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice-system.invoices.index') }}">
                <span class="sub-item">{{ __('Invoices') }}</span>
              </a>
            </li>

          </ul>
        </div>
      </li> -->
	  
	  <!-- code by AG start -->
	  <li
        class="nav-item @if (request()->routeIs('vendor.invoice.*')) active  @endif">
        <a data-toggle="collapse" href="#invoice-system">
          <i class="fal fa-file-spreadsheet"></i>
          <p>{{ __('Invoices') }}</p>
          <span class="caret"></span>
        </a>

        <div id="invoice-system"
          class="collapse 
            @if (request()->routeIs('vendor.invoice.*')) show @endif">
          <ul class="nav nav-collapse">

            <li class="{{ request()->routeIs('vendor.invoice') ? 'active' : '' }}">
              <a href="{{ route('vendor.invoice') }}">
                <span class="sub-item">{{ __('Invoices') }}</span>
              </a>
            </li>

          </ul>
        </div>
      </li>
	  <!-- code by AG end -->
        

        

        

        <li
          class="nav-item @if (request()->routeIs('vendor.equipment_booking.bookings')) active
          @elseif (request()->routeIs('vendor.equipment_booking.details')) active @endif">
          <a href="{{ route('vendor.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-calendar-check"></i>
            <p>{{ __('Equipment Bookings') }}</p>
          </a>
        </li>
        <li
          class="nav-item @if (request()->routeIs('vendor.equipment_inventory')) active @endif">
          <a href="{{ route('vendor.equipment_inventory', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-calendar-check"></i>
            <p>{{ __('Inventory Management') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.equipment_booking.report')) active @endif">
          <a href="{{ route('vendor.equipment_booking.report') }}">
            <i class="fal fa-file-spreadsheet"></i>
            <p>{{ __('Booking Report') }}</p>
          </a>
        </li>

        {{-- <li
          class="nav-item @if (request()->routeIs('vendor.withdraw')) active 
            @elseif (request()->routeIs('vendor.withdraw.create'))  active @endif">
          <a data-toggle="collapse" href="#Withdrawals">
            <i class="fal fa-donate"></i>
            <p>{{ __('Withdrawals') }}</p>
            <span class="caret"></span>
          </a>

          <div id="Withdrawals"
            class="collapse 
              @if (request()->routeIs('vendor.withdraw')) show 
              @elseif (request()->routeIs('vendor.withdraw.create')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.withdraw') ? 'active' : '' }}">
                <a href="{{ route('vendor.withdraw', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Withdrawal Requests') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.withdraw.create') ? 'active' : '' }}">
                <a href="{{ route('vendor.withdraw.create', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Make a Request') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li> --}}

        <li class="nav-item @if (request()->routeIs('vendor.transcation')) active @endif">
          <a href="{{ route('vendor.transcation') }}">
            <i class="fal fa-exchange-alt"></i>
            <p>{{ __('Transactions') }}</p>
          </a>
        </li>
        @php
          $support_status = DB::table('support_ticket_statuses')->first();
        @endphp
        @if ($support_status->support_ticket_status == 'active')
          {{-- Support Ticket --}}
          <li
            class="nav-item @if (request()->routeIs('vendor.support_tickets')) active
            @elseif (request()->routeIs('vendor.support_tickets.message')) active
            @elseif (request()->routeIs('vendor.support_ticket.create')) active @endif">
            <a data-toggle="collapse" href="#support_ticket">
              <i class="la flaticon-web-1"></i>
              <p>{{ __('Support Tickets') }}</p>
              <span class="caret"></span>
            </a>

            <div id="support_ticket"
              class="collapse
              @if (request()->routeIs('vendor.support_tickets')) show
              @elseif (request()->routeIs('vendor.support_tickets.message')) show
              @elseif (request()->routeIs('vendor.support_ticket.create')) show @endif">
              <ul class="nav nav-collapse">

                <li
                  class="{{ request()->routeIs('vendor.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_tickets') }}">
                    <span class="sub-item">{{ __('All Tickets') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('vendor.support_ticket.create') ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_ticket.create') }}">
                    <span class="sub-item">{{ __('Add a Ticket') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif


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
