@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Profile') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm" action="{{ route('vendor.update_profile') }}" method="post">
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
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $vendor->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <!-- code by AG start -->
                  
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor_settings->weekends_delivery == 1 ? 'checked' : '' }}
                              name="weekends_delivery" value="1" class="custom-control-input" id="weekends_delivery">
                            <label class="custom-control-label"
                              for="weekends_delivery">{{ __('will you be providing deliveries on weekends?') }}</label>
                          </div>
                        </div>
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
                              for="show_contact_form">{{ __('Show  Contact Form') }}</label>
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
                                      class="form-control" name="{{ $language->code }}_name" placeholder="Enter Name">
                                    <p id="editErr_{{ $language->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Shop Name*') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->shop_name : '' }}"
                                      class="form-control" name="{{ $language->code }}_shop_name"
                                      placeholder="Enter Shop Name">
                                    <p id="editErr_{{ $language->code }}_shop_name" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control" placeholder="Enter Address">{{ !empty($vendor_info) ? $vendor_info->address : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_email" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->city : '' }}"
                                      class="form-control" name="{{ $language->code }}_city" placeholder="Enter City">
                                    <p id="editErr_{{ $language->code }}_city" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->state : '' }}"
                                      class="form-control" name="{{ $language->code }}_state"
                                      placeholder="Enter State">
                                    <p id="editErr_{{ $language->code }}_state" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->zip_code : '' }}"
                                      class="form-control" name="{{ $language->code }}_zip_code"
                                      placeholder="Enter Zip Code">
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
                                      placeholder="Enter Country">
                                    <p id="editErr_{{ $language->code }}_country" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                
                                  <!--code by rz start-->
                                
                                <!--<div class="col-lg-12">-->
                                <!--    <div class="all_additional_fields">-->
                                <!--    @if(isset($additional_addresses))-->
                                <!--        @if(!empty($additional_addresses))-->
                                <!--        @foreach($additional_addresses as $add_add)-->
                                <!--        <div class="row">-->
                                <!--            <div class="col-lg-9 mr-0 pr-0 d-flex">-->
                                <!--                <div class="form-group w-75">-->
                                <!--                    <label>{{ __('Additional Address') }}</label>-->
                                <!--                    <input type="text" value="{{$add_add->address}}" class="form-control additional_address{{$add_add->id}}" name="additional_address[]"-->
                                <!--                    placeholder="Enter Your Additional Address">-->
                                                    
                                <!--                    <input type="hidden" name="latitude[]" value="{{$add_add->latitude}}" id="location_lat{{$add_add->id}}">-->
                                <!--                    <input type="hidden" name="longitude[]" value="{{$add_add->longitude}}" id="location_long{{$add_add->id}}">-->
                                <!--                </div>-->
                                                
                                <!--                <div class="form-group w-25">-->
                                <!--                    <label>{{ __('Radius') }}</label>-->
                                <!--                    <input type="number" min="0" class="form-control" step="1" name="add_add_radius[]" value="{{$add_add->radius}}" id="add_add_radius{{$add_add->id}}">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-lg-3 d-flex align-items-end m-0 p-0">-->
                                <!--                <div class="form-group">-->
                                <!--                    <button type="button" class="btn btn-primary btn-md remove-address-input"><i class="fa-solid fa-trash"></i></button>-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--        @endforeach-->
                                <!--        @endif-->
                                <!--        @endif-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="col-lg-12">-->
                                <!--    <div class="form-group">-->
                                <!--        <button type="button" class="btn btn-primary btn-md add-address-input">Add</button>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--code by rz end-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" class="form-control" rows="5" placeholder="Enter Details">{{ !empty($vendor_info) ? $vendor_info->details : '' }}</textarea>
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
    </div>
       <!--code by rz start-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd6MwjJtrW3U8p_M3VIFnxMZsnqfh-aWc&libraries=places"></script>
    <script>
      $(document).ready(function () {
    var a = 0;

    // Function to initialize Google Places Autocomplete for an input field
    function initializeAutocomplete(input, latField, longField) {
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                console.error("No location data found for input.");
                return;
            }
            latField.value = place.geometry.location.lat();
            longField.value = place.geometry.location.lng();
        });

        // Handle input change
        $(input).on('change', function () {
            latField.value = '';
            longField.value = '';
        });
    }

    $('.add-address-input').click(function () {
        a++;
        var newAddressField = `<div class="row">
            <div class="col-lg-9 mr-0 pr-0 d-flex">
                <div class="form-group w-75">
                    <label>{{ __('Additional Address') }}</label>
                    <input type="text" value="" class="form-control additional_address" name="additional_address[]"
                        placeholder="Enter Your Additional Address">
                    <input type="hidden" name="latitude[]" class="location_lat" id="location_lat${a}">
                    <input type="hidden" name="longitude[]" class="location_long" id="location_long${a}">
                </div>
                
                <div class="form-group w-25">
                    <label>{{ __('Radius') }}</label>
                    <input type="number" min="0" class="form-control additional_radius" step="1" name="add_add_radius[]" id="add_add_radius${a}">
                </div>
            </div>
            <div class="col-lg-3 d-flex align-items-end m-0 p-0">
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-md remove-address-input"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>`;

        var newField = $(newAddressField);
        $('.all_additional_fields').append(newField);

        // Initialize autocomplete for the newly added address field
        var input = newField.find('.additional_address')[0];
        var latField = newField.find('.location_lat')[0];
        var longField = newField.find('.location_long')[0];
        initializeAutocomplete(input, latField, longField);
    });

    $('body').on('click', '.remove-address-input', function () {
        $(this).closest('.row').remove();
    });

    @if(isset($additional_addresses))
    @if(!empty($additional_addresses))
    @foreach($additional_addresses as $add_add)
    var searchInput = 'additional_address{{$add_add->id}}';

    // Initialize autocomplete for existing addresses
    initializeAutocomplete(document.getElementsByClassName(searchInput)[0], 
        document.getElementById(`location_lat{{$add_add->id}}`),
        document.getElementById(`location_long{{$add_add->id}}`));

    @endforeach
    @endif
    @endif
});

    </script>
    <!--code by rz end-->
  @endsection
