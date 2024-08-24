<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Location') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create"
          action="{{ route('vendor.equipment_booking.settings.store_location') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="location_name" id="" placeholder="{{ __('Enter Location Name') }}" required>
            <p id="err_location_name" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <div class="form-group">
            <label for="">{{ __('Language') . '*' }}</label>
            <select name="language_id" class="form-control">
              <option disabled>{{ __('Select a Language') }}</option>
              @foreach ($langs as $lang)
                <option selected value="{{ $lang->id }}">{{ $lang->name }}</option>
              @endforeach
            </select>
            <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Address') . '*' }}</label>
            <input type="text" class="form-control" name="name" id="in_name_c" placeholder="{{ __('Enter Address') }}">
            <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
           <div class="form-group">
               
            <input type="hidden" name="latitude" class="location_lat" id="in_latitude_c">
            <input type="hidden" name="longitude" class="location_long" id="in_longitude_c">
          </div>
          
          <!--<div class="form-group">-->
          <!--  <label for="country">Address *</label>-->
          <!--  <select class="form-control" name="name" id="country"  onchange="-->
          <!--      if(this.value){ -->
          <!--          $('#radiusdiv').css('display','block');-->
          <!--          $('#zipcodesdiv').css('display','block');-->
          <!--      } else{ -->
          <!--          $('#radiusdiv').css('display','none');-->
          <!--          $('#zipcodesdiv').css('display','none');-->
          <!--      }">-->
          <!--      <option value="">select</option>-->
          <!--      @php $additional = App\Models\AdditionalAddress::where('vendor_id',auth()->user()->id)->get(); @endphp-->
          <!--      @foreach($additional as $a)-->
          <!--      <option value="{{$a->address}}">{{$a->address}}</option>-->
          <!--      @endforeach-->
          <!--  </select>-->
          <!--  <p id="err_name" class="mt-2 mb-0 text-danger em"></p>-->
          <!--</div>-->
          
        <div class="form-group" id="radiusdiv">
            <label for="">Radius(Miles) *</label>
            <input type="number" min="0" step="1" class="form-control" name="radius" id="radius" placeholder="30">
            <p id="err_radius" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <div class="form-group">
            <label for="">{{ __('Equipment Category') . '*' }}</label>
            <select name="equipment_category_id" class="form-control">
                <option value="">Select Category</option>
                @if(!empty($equipment_categories))
                
                    @foreach($equipment_categories as $equipment_category)
                    <option value="{{ $equipment_category->id }}">{{ $equipment_category->name }}</option>
                    @endforeach
                @endif
              
            </select>
            <p id="err_equipment_category_id" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <div class="form-group">
            <label for="">{{ __('Rate Type') . '*' }}</label>
            <select name="rate_type" class="form-control">
              <option selected value="flat_rate">Flat Rate</option>
              <option value="rate_by_distance">Rate By Distance</option>
            </select>
            <p id="err_rate_type" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <!--<div class="form-group" id="zipcodesdiv" style="display:none">-->
          <!--  <label for="">Zipcodes *</label>-->
          <!--  <textarea class="form-control" name="zipcodes" id="zipcodes" readonly></textarea>-->
          <!--  <p id="err_name" class="mt-2 mb-0 text-danger em"></p>-->
          <!--</div>-->

          @if ($twoWayDeliveryStatus == 1)
            <div class="form-group for_flat_rate">
              <label for="">{{ __('Pickup & Dropoff Charge') . ' (' . $currency . ')' }}</label>
              <input type="number" value="0" step="0.01" class="form-control ltr" name="charge"
                placeholder="{{ __('Enter Location Charge') }}">
              <p id="err_charge" class="mt-2 mb-0 text-danger em"></p>
            </div>
            
            <div class="form-group for_rate_by_distance d-none">
              <label for="">{{ __('Pickup & Dropoff Charge Per Mile') . ' (' . $currency . ')' }}</label>
              <input type="number" value="0" step="0.01" class="form-control ltr" name="distance_rate"
                placeholder="{{ __('Enter Location Charge Per Mile') }}">
              <p id="err_distance_rate" class="mt-2 mb-0 text-danger em"></p>
            </div>
          @endif

          <!--<div class="form-group">-->
          <!--  <label for="">{{ __('Serial Number') . '*' }}</label>-->
          <!--  <input type="number" class="form-control ltr" name="serial_number"-->
          <!--    placeholder="{{ __('Enter Location Serial Number') }}">-->
          <!--  <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>-->
          <!--  <p class="text-warning mt-2 mb-0">-->
          <!--    <small>{{ __('The higher the serial number is, the later the location will be shown.') }}</small>-->
          <!--  </p>-->
          <!--</div>-->
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
