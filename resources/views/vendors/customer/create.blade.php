@extends('vendors.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Add Customer') }}</h4>
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
      <a href="#">{{ __('Customer') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('All Customer') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Add Customer') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">{{ __('Add Customer') }}</div>
        <a class="btn btn-info btn-sm float-right d-inline-block"
          href="{{ route('vendor.customer_management.all_customer') }}">
          <span class="btn-label">
            <i class="fas fa-backward"></i>
          </span>
          {{ __('Back') }}
        </a>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <ul></ul>
            </div>

            <form id="equipmentForm" action="{{ route('vendor.customer_management.store_customer') }}"
              enctype="multipart/form-data" method="POST">
              @csrf
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="">{{ __('Photo') }}</label>
                    <br>
                    <div class="thumb-preview">
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    </div>
                    <div class="mt-3">
                      <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Photo') }}
                        <input type="file" class="img-input" name="photo">
                      </div>
                      <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('First Name*') }}</label>
                    <input type="text" value="" class="form-control" name="first_name"
                      placeholder="{{ __('Enter First Name') }}">
                    <p id="editErr_first_name" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('Last Name*') }}</label>
                    <input type="text" value="" class="form-control" name="last_name"
                      placeholder="{{ __('Enter Last Name') }}">
                    <p id="editErr_last_name" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('Email*') }}</label>
                    <input type="email" value="" class="form-control" name="email"
                      placeholder="{{ __('Enter Email') }}">
                    <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label style="display: block" class="form-label">{{ __('Phone') }}</label>
                    <input type="tel" value="" class="form-control" name="contact_number">
                    <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group">
                    <label>{{ __('Address') }}</label>
                    <input name="address" id="location_eq" class="form-control" placeholder="{{ __('Enter Address') }}" />
                    <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('City') }}</label>
                    <input type="text" value="" class="form-control" name="city" placeholder="{{ __('Enter City') }}">
                    <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('State') }}</label>
                    <input type="text" value="" class="form-control" name="state" placeholder="{{ __('Enter State') }}">
                    <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ __('Country') }}</label>
                    <input type="text" value="" class="form-control" name="country"
                      placeholder="{{ __('Enter Country') }}">
                    <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
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
            <button type="submit" form="equipmentForm" class="btn btn-success">
              {{ __('Save') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>

<script>
  $(document).ready(function(){
      
        var searchInput = 'location_eq';
      
        let options = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
      
        $(document).ready(function () {
            var autocomplete;
            autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
                types: ['geocode'],
            });
            
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                  var place = autocomplete.getPlace();

                  // Get country
                  var country = place.address_components.filter(component => {
                    return component.types[0] === 'country'; 
                  })[0].long_name;
                
                  // Get state/region
                  var state = place.address_components.filter(component => {
                    return component.types[0] === 'administrative_area_level_1';
                  })[0].long_name;
                
                  // Get city
                  var city = place.address_components.filter(component => {
                    return component.types[0] === 'locality';
                  })[0].long_name;
                
                    $("input[name='country']").val(country)
                    $("input[name='state']").val(state)
                    $("input[name='city']").val(city)
                
                //   console.log(country, state, city);
                
            });
        });
        
        $('input[name="contact_number"]').attr('id','phone_helper')
        var input__ = document.querySelector("#phone_helper");
        $("#phone_helper").after('<input type="hidden" name="contact_number" id="phone">');
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
        
    });
</script>
@endsection