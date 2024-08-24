<div class="header-navigation">
  <div class="container-fluid pl-0 pr-0">
    <div class="primary-menu d-flex align-items-center justify-content-between">

      <div class="site-branding">
        @if (!empty($websiteInfo->logo))
          <a href="{{ route('index') }}" class="brand-logo">
            <img data-src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="website logo" class="lazy" style="width:70px">
          </a>
        @endif
      </div>
         <style>
         ul.login-options-menu li:first-child {
    /*box-shadow: 0px 0px 12px #000;*/
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    border:1px solid #ddd;
}

ul.login-options-menu li:last-child {
    /*box-shadow: 0px 12px 15px #0000006e;*/
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    border:1px solid #ddd;
}
         li.login-options-menu-handel:hover>ul {
    display: block ;
}

ul.login-options-menu {
    position: absolute;
    z-index: 999;
    left: -90px;
    width: max-content;
    border-radius: 5px;
    top: 25px;
    display: none;
    padding: 15px 0;
}

li.login-options-menu-handel {
    position: relative;
}

ul.login-options-menu li {
        padding: 4px 15px;
    border: 1px solid #ddd;
    background: #fff;
}

ul.login-options-menu li:hover {
    background: #64ad42;
    color: #fff !important;
}

ul.login-options-menu:before {
    content: "";
    display: block;
    width: 15px;
    height: 15px;
    background: #fff;
    position: absolute;
    rotate: 45deg;
    left: 60%;
    top: 7px;
    box-shadow: -2px -2px 4px #0000003d;
    z-index:-2;
}
        .header-area-one .header-navigation .primary-menu .location-inputs .location-inp
        {
            border: 0.5px solid #c1c1c1;
            padding: 2px 13px;
            color: #c1c1c1;
            width: 250px;
            font-size: 13px;
            cursor:pointer;
        }
        .header-area-one .header-navigation .primary-menu .location-inputs .location-inp span
        {
            overflow:hidden;
            width:100%;
        }
        .dropdown-menus{
            width:320px;
        }
        .d_loca{
            background-color:#3387e2;
        }
        .abc::after{
            display:none;
        }
        .mbl-menu-btm{
            display:none;
        }
        @media screen and (max-width: 1200px) {
             .mbl-menu-btm{
            display:block;
        }
          .mbl-menu-btm .cart-btn{
            margin-right:0;
            }
            ul.login-options-menu{
                left:0;
                top:45px;
                width:100%;
            }
            ul.login-options-menu li,ul.login-options-menu li:last-child ,ul.login-options-menu li:first-child {
                border:none;
            }
            ul.login-options-menu:before{
                display:none;
            }
            ul.login-options-menu li:hover {
                 background: transparent;
            }
            /*.active-menu{*/
            /*    display:block!important;*/
            /*}*/
        }
        
      </style>
      <!--<div class='location-inputs ml-5'>-->
      <!--    <div class="d-flex">-->
                <!--<div class="pick-ship">-->
                <!--    <input type="checkbox" class="pick-ship-checkbox d-none" id="pscheckbox">-->
                <!--    <p class="pick-ship-p"><label for="pscheckbox">Pickup At <i class="fa fa-angle-down"></i></label></p>-->
                <!--</div>-->
      <!--          <div class="location-inp">-->
      <!--               <div class="dropdown show w-100">-->
      <!--                  <a class="dropdown-toggle w-100 d-flex align-items-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
      <!--                      <i class="fa fa-map-marker mr-2" style="color: #3387e2;"></i> <span class="place_name">Location</span>-->
      <!--                  </a>-->
      <!--                    <div class="dropdown-menu dropdown-menus" aria-labelledby="dropdownMenuLink">-->
      <!--                      <div class="dropdown-item pb-2"><button class="btn w-100 d_loca text-white" onclick="getLocation()"><i class="fas fa-location mr-2"></i>Detect my Location</button></div>-->
      <!--                      <div class="dropdown-item pt-2"><input type="text" class="form-control" id="location_field_2"></div>-->
      <!--                    </div>-->
      <!--                  </div>-->
      <!--          </div>-->
      <!--    </div>-->
      <!--</div>-->
      <div class="nav-menu">
        <div class="navbar-close"><i class="fal fa-times"></i></div>

        @php 
            $settings = \App\Models\BasicSettings\Basic::where('id', 2)->first('subscription_enable');
        @endphp
        
        
        <nav class="main-menu">
          <ul>
            <?php
                $current_vendor_plan = null;
                if(auth()->guard('vendor')->check()) {
                    $id = auth()->guard('vendor')->user()->id;
                    $vendor = \App\Models\Vendor::with('membership_plans')->find($id);
                    $current_vendor_plan = $vendor->membership_plans()->wherePivot('status',1)->first();
                }
            ?>
            @php $menuDatas = json_decode($menuInfos); @endphp
            
            @foreach ($menuDatas as $menuData)
            @php $href = get_href($menuData); @endphp
               
                
              @if (!property_exists($menuData, 'children'))
              
                    @if(auth()->guard('vendor')->check())
                        @if($menuData->text == "Subscription" && $settings->subscription_enable == "1" && $current_vendor_plan) 
                            <li class="menu-item">
                              <a href="{{ $href }}?upgrade=upgrade">{{ $menuData->text }}</a>
                            </li>
                        @else
                            <li class="menu-item">
                              <a href="{{ $href }}">{{ $menuData->text }}</a>
                            </li>
                        @endif
                    @else
                            <li class="menu-item">
                              <a href="{{ $href }}">{{ $menuData->text }}</a>
                            </li>
                        @if($menuData->text == "Subscription" && $settings->subscription_enable == "0") 
                            @continue
                        @endif
                    @endif
                    
                
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
           <ul class="align-items-center justify-content-center mbl-menu-btm">
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
            <li class="login-options-menu-handel">
              <a href="{{ route('user.login') }}" class="" style="color: #000;">
              {{ __('Login') }}
              </a>
              <ul class="login-options-menu">
                    <li>
                      <a href="{{ route('user.login') }}" class="" style="color: #000;">
                      {{ __('Login as Customer') }}
                      </a>
                      
                    </li>
                    <li>
                      <a href="{{ route('driver.login') }}" class="" style="color: #000;">
                      {{ __('Login as Driver') }}
                      </a>
                      
                    </li>
                    <li>
                      <a href="{{ route('vendor.login') }}" class="" style="color: #000;">
                      {{ __('Login as Vendor') }}
                      </a>
                      
                    </li>
              </ul>
            </li>
            @endguest

            @auth('admin')
            <li class="dropdown" style="padding: 15px;">
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
            <li class="dropdown" style="padding: 15px;">
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
                    @if($settings->subscription_enable == "1")
                        <a class="dropdown-item" href="{{ route('subscription.show') }}">{{ __('My Subscription') }}</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a>
                @endauth
              </div>
            </li>
            @endauth

            @auth('driver')
            <!-- code by AG start -->
            <li class="dropdown" style="padding: 15px;">
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
            <li class="dropdown" style="padding: 15px;">
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
        </nav>
      </div>

      <div class="navbar-toggler">
        <span></span><span></span><span></span>
      </div>

      <div class="header-right-nav">
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
            <li class="login-options-menu-handel">
              <a href="{{ route('user.login') }}" class="" style="color: #000;">
              {{ __('Login') }}
              </a>
              <ul class="login-options-menu">
                    <li>
                      <a href="{{ route('user.login') }}" class="" style="color: #000;">
                      {{ __('Login as Customer') }}
                      </a>
                      
                    </li>
                    <li>
                      <a href="{{ route('driver.login') }}" class="" style="color: #000;">
                      {{ __('Login as Driver') }}
                      </a>
                      
                    </li>
                    <li>
                      <a href="{{ route('vendor.login') }}" class="" style="color: #000;">
                      {{ __('Login as Vendor') }}
                      </a>
                      
                    </li>
              </ul>
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
                    @if($settings->subscription_enable == "1")
                        <a class="dropdown-item" href="{{ route('subscription.show') }}">{{ __('My Subscription') }}</a>
                    @endif
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
        <!--@if (count($socialMediaInfos) > 0)-->
        <!--  <div class="social-box">-->
        <!--    <ul class="social-link">-->
        <!--      @foreach ($socialMediaInfos as $socialMediaInfo)-->
        <!--        <li>-->
        <!--          <a href="{{ $socialMediaInfo->url }}" target="_blank">-->
        <!--            <i class="{{ $socialMediaInfo->icon }}"></i>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--      @endforeach-->
        <!--    </ul>-->
        <!--  </div>-->
        <!--@endif-->
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>


// function getLocation() {
//       if (navigator.geolocation) {
//         navigator.geolocation.getCurrentPosition(showPosition);
//       } else {
//         x.innerHTML = "Geolocation is not supported by this browser.";
//       }
//     }
    
//     function showPosition(position) {
//   localStorage.setItem('Latitude', position.coords.latitude);
//   localStorage.setItem('Longitude', position.coords.longitude);
//   var geocodingUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.coords.latitude},${position.coords.longitude}&key=AIzaSyBd6MwjJtrW3U8p_M3VIFnxMZsnqfh-aWc`;

//   fetch(geocodingUrl)
//     .then(response => response.json())
//     .then(data => {
//       if (data.status === "OK" && data.results.length > 0) {
//         var addressComponents = data.results[0].address_components;

//         var country = null;
//         var state = null;
//         var zipCode = null;

//         for (var i = 0; i < addressComponents.length; i++) {
//           var types = addressComponents[i].types;

//           if (types.includes('country')) {
//             country = addressComponents[i].long_name;
//           } else if (types.includes('administrative_area_level_1')) {
//             state = addressComponents[i].long_name;
//           } else if (types.includes('postal_code')) {
//             zipCode = addressComponents[i].long_name;
//           }
//         }

//         if (country) {
//           localStorage.setItem('Country', country);
//         }else{
//                 localStorage.setItem('Country', "");
//             }

//         if (state) {
//           localStorage.setItem('State', state);
//         }else{
//                 localStorage.setItem('State', "");
//             }

//         if (zipCode) {
//           localStorage.setItem('ZipCode', zipCode);
//         }
//         else
//         {
//             localStorage.setItem('ZipCode', "");
//         }
//         var locationText = "";
//             if (zipCode) {
//                 locationText += zipCode;
//             }
//             if (state) {
//                 if (locationText) {
//                     locationText += " " + state;
//                 } else {
//                     locationText = state;
//                 }
//             }
//             if (country) {
//                 if (locationText) {
//                     locationText += " " + country;
//                 } else {
//                     locationText = country;
//                 }
//             }
//             $('.place_name').html(locationText);
//             $('#location_field').val(locationText);
            
//         if (country || state || zipCode) {
//         } else {
//           console.log("Country, state, and zip code information not found");
//         }
//       } else {
//         console.log("Location not found");
//       }
//     })
//     .catch(error => {
//       console.error("Error fetching geocoding data: " + error);
//     });
// }



//     var searchInput2 = 'location_field_2';

//     $(document).ready(function () {
        
//         var lat = localStorage.getItem("Latitude");
//         var long = localStorage.getItem("Longitude");
//         var state = localStorage.getItem("State");
//         var zipCode = localStorage.getItem("ZipCode");
//         var country = localStorage.getItem("Country");
        
//         if(zipCode != null)
//         {
//             $('.place_name').html(zipCode+" "+state+" "+country); 
//             $('#location_field').val(zipCode+" "+state+" "+country);
//         }else if(zipCode == null){
//             $('.place_name').html(state+" "+country); 
//             $('#location_field').val(state+" "+country);
//         }else{
//             $('.place_name').html("Location ");
//         }
        
//         if(state != null)
//         {
//             $('.place_name').html(zipCode+" "+state+" "+country);
//             $('#location_field').val(zipCode+" "+state+" "+country);
//         }else{
//             $('.place_name').html(zipCode+" "+country); 
//             $('#location_field').val(zipCode+" "+country);
//         }
        
        
//         var autocomplete2;
// autocomplete2 = new google.maps.places.Autocomplete((document.getElementById(searchInput2)), {
//     // types: ['geocode'],
// });
// autocomplete2.addListener('place_changed', function() {
//     var place = autocomplete2.getPlace();

//     if (place.name) {
//         var selectedLocations = document.getElementsByClassName('place_name');
//         for (var i = 0; i < selectedLocations.length; i++) {

//             localStorage.setItem('Latitude', place.geometry.location.lat());
//             localStorage.setItem('Longitude', place.geometry.location.lng());

//             var addressComponents = place.address_components;
//             var country = null;
//             var state = null;
//             var zipCode = null;

//             for (var j = 0; j < addressComponents.length; j++) {
//                 var types = addressComponents[j].types;
//                 if (types.includes('country')) {
//                     country = addressComponents[j].long_name;
//                 } else if (types.includes('administrative_area_level_1')) {
//                     state = addressComponents[j].long_name;
//                 } else if (types.includes('postal_code')) {
//                     zipCode = addressComponents[j].long_name;
//                 }
//             }

//             if (country) {
//                 localStorage.setItem('Country', country);
//             }else{
//                 localStorage.setItem('Country', "");
//             }
//             if (state) {
//                 localStorage.setItem('State', state);
//             }else{
//                 localStorage.setItem('State', "");
//             }
//             if (zipCode) {
//                 localStorage.setItem('ZipCode', zipCode);
//             }else{
//                 localStorage.setItem('ZipCode', "");
//             }
            
//             var locationText = "";
//             if (zipCode) {
//                 locationText += zipCode;
//             }
//             if (state) {
//                 if (locationText) {
//                     locationText += " " + state;
//                 } else {
//                     locationText = state;
//                 }
//             }
//             if (country) {
//                 if (locationText) {
//                     locationText += " " + country;
//                 } else {
//                     locationText = country;
//                 }
//             }
//             $('.place_name').html(locationText);
//             //$('#location_field').val(locationText);

//         }
//     }
// });

//     });
</script>