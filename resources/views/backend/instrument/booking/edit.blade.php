@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Bookings') }}</h4>
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
        <a href="#">{{ __('Edit Bookings') }}</a>
      </li>
    </ul>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Bookings') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.equipment_booking.bookings', ['language' => $defaultLang->code]) }}">
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
              
              <form action="{{ route('admin.equipment_booking.update', [ 'id' => $details->id ]) }}" method="POST"  enctype="multipart/form-data" >
                @csrf
                <div class="row">
                
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Select Customer*') }}</label>
                      <select name="user_id" id="select2_cus" class="form-control select2" required>
                          <option selected disabled>Select Customer</option>
                          @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @if($details->user_id == $customer->id) selected @endif>{{ $customer->username }}</option>
                          @endforeach
                      </select>
                      <p id="editErr_first_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
 
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Select Equipment*') }}</label>
                      
                       <select name="equipment_id" id="equipment_id" class="form-control select2" required>
                          <option selected disabled>Select Equipment</option>
                         @foreach($equipments as $equipment)
                            <option value="{{ $equipment->id }}"  @if($details->equipment_id == $equipment->id) selected @endif>{{ $equipment->content[0]->title }}</option>
                          @endforeach
                      </select>
                      <p id="editErr_last_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Location*') }}</label>
                      <input type="text" class="form-control" name="delivery_location" id="location_eq" required
                        placeholder="{{ __('Location') }}" value="{{ $details->delivery_location }}">
                        <input type="hidden" name="lat" id="location_eq_lat" value="{{ $details->lat }}">
                        <input type="hidden" name="long" id="location_eq_long" value="{{ $details->lng }}">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Booking Date *') }}</label>
                      @php
                        $date_value = date('m/d/Y', strtotime($details->start_date)) . ' - ' . date('m/d/Y', strtotime($details->end_date));
                    @endphp
                        
                        <input type="text" id="date-range-eq" placeholder="{{ __('Select Booking Date') }}"
                               name="dates" value="{{ $date_value }}" readonly class="form-control" required>

                      <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Full Name *') }}</label>
                      <input type="text"  class="form-control" name="name" required
                        placeholder="{{ __('Full Name') }} " value="{{ $details->name }}">
                      <p id="editErr_password_confirmation" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Number*') }}</label>
                      <input type="tel"  class="form-control" name="contact_number"
                        placeholder="{{ __('Contact Number') }}" required value="{{ $details->contact_number }}">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email Address') }}</label>
                      <input type="email" placeholder="{{ __('Email Address') }}"  class="form-control" name="email" required  value="{{ $details->email }}">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  @php
                  $additional_booking_parameters = json_decode($details->additional_booking_parameters);
                  $additional_charges_line_items = json_decode($details->additional_charges_line_items);
                  @endphp
                  <div class="col-lg-6">
                      <div class="form-group">
                            <div class="input-wrap mb-3">
                                 <label>Live Load</label>
                                  <select id="live_load" name="live_load" class="form-control" required>
                                      <option selected disabled>Select Live load</option>
                                       <option value="Yes" {{ isset($additional_booking_parameters[0]) && $additional_booking_parameters[0]->value == "Yes" ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ isset($additional_booking_parameters[0]) && $additional_booking_parameters[0]->value == "No" ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <br/>
                    <div class="form-group">
                       <input type="checkbox" value="Yes" name="is_emergency" class="form-checkbox" id="is_emergency" {{ isset($additional_booking_parameters[3]) && $additional_booking_parameters[3]->value == "Yes" ? 'checked' : '' }}>
                        <label for="is_emergency">Emergency</label>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Placement Instructions</label>
                      <textarea id="placement_instructions" name="placement_instructions" class="form-control">{{ isset($additional_booking_parameters[1]) && $additional_booking_parameters[1]->value  ? $additional_booking_parameters[1]->value : '' }}</textarea>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                     <label>Type of waste</label>
                      <select id="customer_punchoutlist" name="customer_punchoutlist" class="form-control">
                          <option value="">Select</option>
                          <option value="House Hold Debris" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "House Hold Debris" ? 'selected' : '' }}>House Hold Debris</option>
                          <option value="Construction Debris" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "Construction Debris" ? 'selected' : '' }}>Construction Debris</option>
                          <option value="Mattress" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "Mattress" ? 'selected' : '' }}>Mattress</option>
                          <option value="Furniture" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "Furniture" ? 'selected' : '' }}>Furniture</option>
                          <option value="Concrete" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "Concrete" ? 'selected' : '' }}>Concrete</option>
                          <option value="Appliances" {{ isset($additional_booking_parameters[2]) && $additional_booking_parameters[2]->value == "Appliances" ? 'selected' : '' }}>Appliances</option>
                      </select>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                      <div class="price-option-table mt-4">
                      <ul>
                          <div id="additional_charges_item_html">
                              @isset($additional_charges_line_items)
                                @foreach($additional_charges_line_items as $additional_charges_line_item)
                                    <li class="single-price-option ag-additional-next-item">
                                      <span class="title">{{ $additional_charges_line_item->name }} <span class="text-success">(<i
                                            class="fas fa-minus"></i>)</span> <span class="amount"
                                          dir="ltr"><span id="discount-amount"
                                            dir="ltr">{{ $additional_charges_line_item->amount }}</span></span></span>
                                    </li>
                                @endforeach
                              @endisset
                          </div>
                        <li class="single-price-option ag-additional-next-item">
                          <span class="title">{{ __('Discount') }} <span class="text-success">(<i
                                class="fas fa-minus"></i>)</span> <span class="amount"
                              dir="ltr"><span id="discount-amount"
                                dir="ltr">{{ $details->discount ?? '0.00' }}</span></span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Subtotal') }} <span class="amount"
                              dir="ltr"><span id="subtotal-amount"
                                dir="ltr">{{ $details->total ?? '0.00' }}</span></span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Tax') }}
                            <span dir="ltr"></span>
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount"
                              dir="ltr"><span id="tax-amount"
                                dir="ltr">{{ $details->tax ?? '0.00' }}</span></span></span>
                        </li>

                          <li class="single-price-option">
                            <span class="title">{{ __('Security Deposit Amount') }} <span class="text-danger">(<i
                                  class="fas fa-plus"></i>)</span>
                              <span class="amount" dir="ltr" id="security_deposit_amount">{{ $details->security_deposit_amount ?? '0.00' }}<span
                                  dir="ltr"></span></span></span><br>
                            <span class="text-warning lh-normal">
                              <small>{{ __('This amount will be refunded, once the equipment is returned to Vendor safely') }}</small>
                            </span>
                          </li>


                        <li class="single-price-option">
                          <span class="title">{{ __('Grand Total') }} <span class="amount"
                              dir="ltr"><span id="grand-total"
                                dir="ltr">{{ $details->grand_total ?? '0.00' }}</span></span></span>
                        </li>
                      </ul>
                    </div>
                  </div>
                
                </div>
                <button type="submit" class="btn btn-success">
                {{ __('Save') }}
              </button>
                </form>
             

            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
  
  @section('script')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="
https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css
" rel="stylesheet">
<script src="
https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js
"></script>
  <script>
  $('#gateway').change(function(){
        let gateway = $(this).val();
        if(gateway == "stripe")
        {
            $('#stripe-card-input').removeClass('d-none');
        }
        else{
            $('#stripe-card-input').addClass('d-none');
        }
    });
    var searchInput = 'location_eq';
    let baseURL = "{{ url('/') }}";
    let tax = $('#tax-amount').text();
    let security_deposit_amount = $('#security_deposit_amount').text();
    let equipmentId = "";
    $('#equipment_id').change(function(){
        equipmentId = $(this).val();
        
      $.ajax({
            type: "GET",
            url: "{{ route('vendor.equipment_booking.get_equipment') }}",
            contentType: "application/json",
            data:{id:equipmentId},
            success: function(response) {
                $('#tax-amount').text(response.symbol+ " "+response.tax);
                $('#security_deposit_amount').text(response.symbol+ " "+response.details.security_deposit_amount);
            }
        });


    });
    let options = { minimumFractionDigits: 2, maximumFractionDigits: 2 };


    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('location_eq_lat').value = near_place.geometry.location.lat();
            document.getElementById('location_eq_long').value = near_place.geometry.location.lng();
            
            $('#location_field').change();
        });
    });
    $(function() {
  $('#date-range-eq').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});


$(document).on('change', '#extra_services, #type_of_rental, #live_load, #is_emergency, #location_field', function(){
     // get the difference of two dates, date should be in 'YYYY-MM-DD' format
    let dates = $('#date-range-eq').val();
    $('.ag-eq-booking-addtional-lineitem').remove();
    // get the minimum price
      let url = `${baseURL}/equipment/${equipmentId}/min-price`;
      
      // code by AG start
        // location data
        var location__ = '';
        var lat__ = '';
        var long__ = '';
        
            location__ = $('#location_eq').val();
            lat__ = $('#location_eq_lat').val();
            long__ = $('#location_eq_long').val();
        
        
        // for temporary toilet
        var extra_services = 0;
        var type_of_rental = '';
        if($('#extra_services').length > 0){
            extra_services = $('#extra_services').val();
        }
        
        if($('#type_of_rental').length > 0){
            type_of_rental = $('#type_of_rental').val();
        }
        
        // for dumpster / multiple charges category
        var live_load = '';
        var is_emergency = '';
        if($('#live_load').length > 0){
            live_load = $('#live_load').val();
        }
        
        if($('#is_emergency').length > 0){
            
            if( $('#is_emergency').prop('checked') == true){
                is_emergency = $('#is_emergency').val();
            }
            
        }
        
    $('.ag-eq-booking-addtional-lineitem').remove();
      // code by AG end

      $.get(url, { dates: dates, extra_services:extra_services, type_of_rental:type_of_rental,live_load:live_load,is_emergency:is_emergency,location__:location__,lat__:lat__,long__:long__ }, function (response) {
        if ('minimumPrice' in response) {
          let minPrice = response.minimumPrice;

          // recalculate the tax
          let calculatedTax = minPrice * (tax / 100);

          $('#booking-price').text(minPrice.toLocaleString(undefined, options));
          $('#subtotal-amount').text(minPrice.toLocaleString(undefined, options));
          $('#tax-amount').text(calculatedTax.toLocaleString(undefined, options));

          let shippingCharge;

         
          
          shippingCharge = parseFloat(response.shipping_cost);
              $('#shipping-charge').text(shippingCharge);

          let grandTotal = minPrice + calculatedTax + shippingCharge + security_deposit_amount;

          $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
          
          $('.ag-eq-booking-addtional-lineitem').remove();
          if(response.additional_charges_item_html != ''){
              $('#additional_charges_item_html').html(response.additional_charges_item_html);
          }
        } else if ('errorMessage' in response) {
          toastr['error'](response.errorMessage);
        }
      });
      
      
    
  });
   $('#select2_cus').select2();
    $('#equipment_id').select2();
    </script>
  @endsection

@endsection