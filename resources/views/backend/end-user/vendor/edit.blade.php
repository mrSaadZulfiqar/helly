@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Vendor') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Vendor') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Vendor') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Vendor') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm"
                action="{{ route('admin.vendor_management.vendor.update_vendor', ['id' => $vendor->id]) }}"
                method="post">
                @csrf
                <h2>Details</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($vendor->photo != null)
                          <img src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">{{ __('Image Size 80x80') }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $vendor->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $vendor->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Phone') }} (Assigned Number)</label><br>
                      <input type="tel" value="{{ $vendor->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <!-- code by AG start -->
                  <div class="col-lg-12">
                    
                    <div class="agcd-additional-contacts agcd-repeater">
                        <?php if( !empty( $additional_contacts ) ){ ?>
                            <?php foreach($additional_contacts as $contact){ ?>
                                <div class="agcd-repeater-item">
                                    <span class="agcd-remove-item">Remove</span>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" value="<?php echo $contact['email']??''; ?>" data-group="additional_contacts" data-name="email" class="form-control" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" value="<?php echo $contact['phone_full']??''; ?>" data-group="additional_contacts" data-name="phone" class="form-control">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Fax No</label>
                                                <input type="text" value="<?php echo $contact['fax_no']??''; ?>" data-group="additional_contacts" data-name="fax_no" class="form-control" placeholder="Enter Fax No">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="agcd-repeater-dummy" style="display:none;">
                        <div class="agcd-repeater-item">
                            <span class="agcd-remove-item">Remove</span>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" data-group="additional_contacts" data-name="email" class="form-control" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" data-group="additional_contacts" data-name="phone" class="form-control">
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fax No</label>
                                        <input type="text" data-group="additional_contacts" data-name="fax_no" class="form-control" placeholder="Enter Fax No">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mt-3 d-none">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="compnayNameinput" class="form-label">Radius</label>
                                    <input type="number" class="form-control" name="radius" value="{{ $vendor_settings['provide_service']??'' }}" placeholder="00">
                                    <input type="hidden" value="{{ $vendor_settings['longitude']??'' }}" name="longitude" id="longitude">
                                    <input type="hidden" value="{{ $vendor_settings['latitude']??'' }}" name="latitude" id="latitude">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Unit</label>
                                    <select name="unit" class="form-control">
                                        <option value="km" <?php echo (isset($vendor_settings['unit']) && $vendor_settings['unit'] == 'km')?'selected':''; ?>>Km</option>
                                        <option value="mile" <?php echo (isset($vendor_settings['unit']) && $vendor_settings['unit'] == 'mile')?'selected':''; ?>>Mile</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="compnayNameinput" class="form-label">Address</label>
                                    <input type="text" value="{{ $vendor_settings['location']??'' }}" class="form-control address pac-target-input" name="location" id="location">
                                    <span class="help-block" style="color: #b31212;">Please click on geolocate address button after adding address.</span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="codeAddress()"><i class="fa fa-map-marker"></i> Geolocate Address</a>
                            </div>

                            <div class="col-lg-6"></div>

                            <div class="col-lg-12 mt-2">
                                <div id="form-map" style="height: 300px;"></div>
                            </div>

                        </div>
                  </div>
                  <!-- code by AG end -->

                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_email_addresss == 1 ? 'checked' : '' }}
                              name="show_email_addresss" class="custom-control-input" id="show_email_addresss">
                            <label class="custom-control-label"
                              for="show_email_addresss">{{ __('Show Email Address in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_phone_number == 1 ? 'checked' : '' }}
                              name="show_phone_number" class="custom-control-input" id="show_phone_number">
                            <label class="custom-control-label"
                              for="show_phone_number">{{ __('Show Phone Number in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_contact_form == 1 ? 'checked' : '' }}
                              name="show_contact_form" class="custom-control-input" id="show_contact_form">
                            <label class="custom-control-label"
                              for="show_contact_form">{{ __('Show Contact Form') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div id="accordion" class="mt-5">
                      @foreach ($languages as $language)
                        <div class="version">
                          <div class="version-header" id="heading{{ $language->id }}">
                            <h5 class="mb-0">
                              <button type="button"
                                class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $language->id }}">
                                {{ $language->name . __(' Language') }}
                                {{ $language->is_default == 1 ? '(Default)' : '' }}
                              </button>
                            </h5>
                          </div>

                          @php
                            $vendor_info = App\Models\VendorInfo::where('vendor_id', $vendor->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body">
                              <div class="row">
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Name*') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->name : '' }}"
                                      class="form-control" name="{{ $language->code }}_name"
                                      placeholder="{{ __('Enter Name') }}">
                                    <p id="editErr_{{ $language->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Company Name*') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->shop_name : '' }}"
                                      class="form-control" name="{{ $language->code }}_shop_name"
                                      placeholder="{{ __('Enter Company Name') }}">
                                    <p id="editErr_{{ $language->code }}_shop_name" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control" placeholder="{{ __('Enter Address') }}">{{ !empty($vendor_info) ? $vendor_info->address : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_email" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->city : '' }}"
                                      class="form-control" name="{{ $language->code }}_city"
                                      placeholder="{{ __('Enter City') }}">
                                    <p id="editErr_{{ $language->code }}_city" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->state : '' }}"
                                      class="form-control" name="{{ $language->code }}_state"
                                      placeholder="{{ __('Enter State') }}">
                                    <p id="editErr_{{ $language->code }}_state" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->zip_code : '' }}"
                                      class="form-control" name="{{ $language->code }}_zip_code"
                                      placeholder="{{ __('Enter Zip Code') }}">
                                    <p id="editErr_{{ $language->code }}_zip_code" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->country : '' }}"
                                      class="form-control" name="{{ $language->code }}_country"
                                      placeholder="{{ __('Enter Country') }}">
                                    <p id="editErr_{{ $language->code }}_country" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" class="form-control" rows="5"
                                      placeholder="{{ __('Enter Details') }}">{{ !empty($vendor_info) ? $vendor_info->details : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_details" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-lg-12">
                                  @php $currLang = $language; @endphp

                                  @foreach ($languages as $language)
                                    @continue($language->id == $currLang->id)

                                    <div class="form-check py-0">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox"
                                          onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                        <span class="form-check-sign">{{ __('Clone for') }} <strong
                                            class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                          {{ __('language') }}</span>
                                      </label>
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <div class="card-header">
              <h2 class="mt-3 text-warning">{{ __('Vendor Balance') }}
                ({{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                {{ $vendor->amount }}
                {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }})
              </h2>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.vendor_management.update_vendor_balance', ['id' => $vendor->id]) }}"
                id="ajaxEditForm2" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Vendor Balance') }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="amount_status" value="1" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Add') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="amount_status" value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Subtract') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Amount') }} {{ $currencyInfo->base_currency_symbol }}</label>
                      <input type="number" name="amount" class="form-control">
                    </div>
                  </div>
                  <hr>
                  <div class="col-12 text-center mt-3">
                    <button type="submit" id="updateBtn2" class="btn btn-success">
                      {{ __('Update') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
  @section('script')
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places"></script>
<script>
     $(document).ready(function(){
        $('input[name="phone"]').attr('id','phone_helper')
        var input__ = document.querySelector("#phone_helper");
        $("#phone_helper").after('<input type="hidden" name="phone" id="phone">');
        $("#phone_helper").attr('name','');
        var iti = window.intlTelInput(input__, {
            separateDialCode: true,
        });
        var fullNumber = iti.getNumber();
        $("#phone").val(fullNumber);
        $("#phone_helper").on('change countrychange', function() {
            var fullNumber = iti.getNumber();
            $("#phone").val(fullNumber);
        });
        refresh_repeater_c();
        
  
    });
    
    $(document).ready(function(){
        
        $('.agcd-repeater').after('<button type="button" class="btn btn-primary btn-sm agcd-add-repeater-item">Add More Contact</button>');
        agcd_reset_repeater_keys();

        $(document).on('click', '.agcd-add-repeater-item', function(){
            var repeater_item = $('.agcd-repeater-dummy').html();
            $('.agcd-repeater').append(repeater_item);
            agcd_reset_repeater_keys();

            refresh_repeater_c();
        });

        $(document).on('click','.agcd-remove-item', function(){
            $(this).parent().remove();
            agcd_reset_repeater_keys();
            refresh_repeater_c();
        });

        function agcd_reset_repeater_keys(){
            var i = 0;
            $('.agcd-additional-contacts .agcd-repeater-item').each(function(){
                $(this).find('input').each(function(){
                    $(this).attr('name',$(this).attr('data-group')+'['+i+']['+$(this).attr('data-name')+']');
                });

                i++;
            });
        }
    });
    
    function refresh_repeater_c(){
        $('input[data-name="phone_full"]').remove();
        $('.agcd-additional-contacts input[data-group="additional_contacts"][data-name="phone"]').each(function(index){
            
            $(this).attr('id','additional_contact_phone_'+index);
           
            $(this).after('<input id="additional_contact_phone_full_'+index+'" type="hidden" data-group="additional_contacts" data-name="phone_full" name="additional_contacts['+index+'][phone_full]" class="form-control">');
             var iti = window.intlTelInput(this, {
                separateDialCode: true,
            });
            var fullNumber = iti.getNumber();
            $("#additional_contact_phone_full_"+index).val(fullNumber);
            
            $("#additional_contact_phone_"+index).on('change countrychange', function() {
                console.log('dhhda');
                var fullNumber = iti.getNumber();
                $("#additional_contact_phone_full_"+index).val(fullNumber);
            });
        });
    }

    $(document).ready(function(){
        $(document).on('blur','#location',function(){
            var addressValue = $(this).val();
            getStateFromAddress(addressValue);
          
        });
      
    });

    var markers = [];
    var map;
    initMap(-34.397, 150.644);
    function initMap(lat = -34.397, lng = 150.644) {
      const myLatLng = {
        lat: lat,
        lng: lng
      };
      var ex_latitude = $('#latitude').val();
      var ex_longitude = $('#longitude').val();
    
      var map = new google.maps.Map(document.getElementById("form-map"), {
        zoom: 13,
        center: myLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      });
    
      var marker = new google.maps.Marker({
        position: myLatLng,
        map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(ex_latitude, ex_longitude)
      });
    
      google.maps.event.addListener(marker, 'dragend', function() {
        var marker_positions = marker.getPosition();
        $('#latitude').val(marker_positions.lat());
        $('#longitude').val(marker_positions.lng());
      });
    
    }

    function codeAddress() {
      geocoder = new google.maps.Geocoder();
      var main_address = $('#location').val();
      var addressCity = $('#location').val();
    
      var address = [addressCity,addressCity].join();
    
      //if (city != '' && street) {
        setAllMap(null); //Clears the existing marker
        geocoder.geocode({
          address: address
        }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            var lat = results[0].geometry.location.lat();
            var lng = results[0].geometry.location.lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#latitude').removeClass('is-invalid');
            $('#longitude').removeClass('is-invalid');
            $('#latitude').addClass('is-valid');
              $('#longitude').addClass('is-valid');
    
            initMap(lat, lng)
            window.initMap = initMap;
          } else {
            // alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      // } else {
      //   alert("You must enter an address");
    
      // }
    }

    function setAllMap(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
    }
    $(document).ready(function () {
      codeAddress();
    })

    function getStateFromAddress(address) {
        // Create a Geocoder instance
        var geocoder = new google.maps.Geocoder();

        // Use the Geocoder to obtain the latitude and longitude of the address
        geocoder.geocode({ address: address }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                // Get the first result
                var result = results[0];

                // Find the state in the address_components array
                var state = null;
                result.address_components.forEach(function(component) {
                    if (component.types.indexOf('administrative_area_level_1') !== -1) {
                        state = component.short_name;
                    }
                });

                // Output the state
                console.log(state);
                
                $('#stateA').val(state);
            } else {
                // Handle errors
                console.error('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
</script>

<script>
        
    google.maps.event.addDomListener(window, 'load', initialize);
  
      function initialize() {
  
        var input = document.getElementById('location');
  
        var autocomplete = new google.maps.places.Autocomplete(input);
  
        autocomplete.addListener('place_changed', function () {
  
        var place = autocomplete.getPlace();
  
        // place variable will have all the information you are looking for.
  
        $('#lat').val(place.geometry['location'].lat());
  
        $('#long').val(place.geometry['location'].lng());
  
      });
  
    }
  
  </script>
@endsection