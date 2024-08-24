@extends('vendors.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('vendors.partials.rtl-style')

@section('content')
<style>
    .pac-container {
    z-index: 999999;
    left: 29% !IMPORTANT;
    top: 57% !important;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places,geometry"></script>
  <div class="page-header">
    <h4 class="page-title">{{ __('Warehouse') }}</h4>
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
        <a href="#">{{ __('Equipment Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Warehouse') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Warehouse') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('vendors.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.equipment_booking.settings.bulk_delete_location') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        @php
          $currency = $currencyInfo->base_currency_text;
          $symbolPosition = $currencyInfo->base_currency_symbol_position;
          $symbol = $currencyInfo->base_currency_symbol;
        @endphp

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($locations) == 0)
                <h3 class="text-center mt-2">{{ __('NO LOCATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Location') }}</th>

                        @if ($twoWayDeliveryStatus == 1)
                          <th scope="col">{{ __('Charge') }}</th>
                        @endif

                        <!--<th scope="col">{{ __('Serial Number') }}</th>-->
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($locations as $location)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $location->id }}">
                          </td>
                          <td>
                            {{ $location->location_name }}
                          </td>
                          <td>
                            {{ strlen($location->name) > 50 ? mb_substr($location->name, 0, 50, 'UTF-8') . '...' : $location->name }}
                          </td>

                          @if ($twoWayDeliveryStatus == 1)
                            <td>
                                @if($location->rate_type == 'flat_rate')
                                     @if (empty($location->charge))
                                        -
                                      @else
                                        {{ $symbolPosition == 'left' ? $symbol : '' }}{{ $location->charge }}{{ $symbolPosition == 'right' ? $symbol : '' }}
                                      @endif
                                      ({{ 'Flat Rate' }})
                                @endif
                                
                                @if($location->rate_type == 'rate_by_distance')
                                     @if (empty($location->distance_rate))
                                        -
                                      @else
                                        {{ $symbolPosition == 'left' ? $symbol : '' }}{{ $location->distance_rate }}{{ $symbolPosition == 'right' ? $symbol : '' }}
                                      @endif
                                       Per Mile ({{ 'Rate By Distance' }})
                                @endif
                             
                            </td>
                          @endif

                          <!--<td>{{ $location->serial_number }}</td>-->
                          <td>
                              
                            <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $location->id }}" data-name="{{ $location->name }}"
                              data-charge="{{ $location->charge }}" data-serial_number="{{ $location->serial_number }}"
                              data-rate_type="{{ $location->rate_type }}" data-radius="{{ $location->radius }}"
                              data-latitude="{{ $location->latitude }}" data-longitude="{{ $location->longitude }}" 
                              data-equipment_category_id="{{ $location->equipment_category_id }}" data-distance_rate="{{ $location->distance_rate }}"
                              data-location_name="{{ $location->location_name }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                              {{ __('Edit') }}
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('vendor.equipment_booking.settings.delete_location', ['id' => $location->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>
    <script>
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
function findZipCodes() {
    var country = document.getElementById("country").value;
    var radius = parseInt(document.getElementById("radius").value);
    var geocoder = new google.maps.Geocoder();
    var zipcodes = [];

    geocoder.geocode({ address: country }, function (results, status) {
        if (status === "OK" && results.length > 0) {
            var countryBounds = results[0].geometry.viewport;
            var latLngs = [];

            for (var lat = countryBounds.getSouthWest().lat(); lat <= countryBounds.getNorthEast().lat(); lat += 0.1) {
                for (var lng = countryBounds.getSouthWest().lng(); lng <= countryBounds.getNorthEast().lng(); lng += 0.1) {
                    var latLng = new google.maps.LatLng(lat, lng);
                    latLngs.push(latLng);
                }
            }

            var geocoderRequests = latLngs.map(function (latLng) {
                return new Promise(function (resolve) {
                    geocoder.geocode({ location: latLng }, function (results, status) {
                        if (status === "OK" && results.length > 0) {
                            for (var i = 0; i < results[0].address_components.length; i++) {
                                if (results[0].address_components[i].types.includes("postal_code")) {
                                    zipcodes.push(results[0].address_components[i].short_name);
                                    break;
                                }
                            }
                        }
                        resolve();
                    });
                });
            });

            Promise.all(geocoderRequests).then(function () {
                document.getElementById("zipcodes").value = zipcodes.join(", ");
            });
        }
    });
}

$(document).ready(function(){
    // $(document).on('keyup', '#radius, #in_radius', function(){
    //     findZipCodes();
    // });

    // $(document).on('change', '#country, #in_name', function(){
    //     findZipCodes();
    // });
    
    initializeAutocomplete(document.getElementById('in_name'), 
        document.getElementById('in_latitude'),
        document.getElementById('in_longitude'));
        
        
        initializeAutocomplete(document.getElementById('in_name_c'), 
        document.getElementById('in_latitude_c'),
        document.getElementById('in_longitude_c'));
        
    $(document).on('change','select[name="rate_type"]', function(){
        var rate_type = $(this).val();
        if(rate_type == 'flat_rate'){
            $('.for_flat_rate').removeClass('d-none');
            $('.for_rate_by_distance').addClass('d-none');
        }
        if(rate_type == 'rate_by_distance'){
            $('.for_rate_by_distance').removeClass('d-none');
            $('.for_flat_rate').addClass('d-none');
        }
    });
});

    </script>
  {{-- create modal --}}
  @include('vendors.location.create')

  {{-- edit modal --}}
  @include('vendors.location.edit')
@endsection
