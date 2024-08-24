<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Location') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form"
          action="{{ route('vendor.equipment_booking.settings.update_location') }}" method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">
          
          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="location_name" id="in_location_name" placeholder="{{ __('Enter Location Name') }}" required>
            <p id="err_location_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          
          <div class="form-group">
            <label for="">{{ __('Address') . '*' }}</label>
            <input type="text" class="form-control" name="name" id="in_name" placeholder="{{ __('Enter Address') }}">
            <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <div class="form-group">
               
            <input type="hidden" name="latitude" class="location_lat" id="in_latitude">
            <input type="hidden" name="longitude" class="location_long" id="in_longitude">
          </div>
          
           <div class="form-group" id="radiusdiv_2">
            <label for="">Radius(Miles) *</label>
            <input type="number" min="0" step="1" class="form-control" name="radius" id="in_radius" placeholder="25">
            <p id="err_radius" class="mt-2 mb-0 text-danger em"></p>
          </div>
          
          <div class="form-group">
            <label for="">{{ __('Equipment Category') . '*' }}</label>
            <select name="equipment_category_id" id="in_equipment_category_id" class="form-control">
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
            <select name="rate_type" id="in_rate_type" class="form-control">
              <option value="flat_rate">Flat Rate</option>
              <option value="rate_by_distance">Rate By Distance</option>
            </select>
            <p id="err_rate_type" class="mt-2 mb-0 text-danger em"></p>
          </div>
          

          @if ($twoWayDeliveryStatus == 1)
            <div class="form-group for_flat_rate">
              <label for="">{{ __('Two Way Delivery Charge') . ' (' . $currency . ')' }}</label>
              <input type="number" step="0.01" value="0" id="in_charge" class="form-control ltr" name="charge"
                placeholder="{{ __('Enter Location Charge') }}">
              <p id="editErr_charge" class="mt-2 mb-0 text-danger em"></p>
            </div>
            
            <div class="form-group for_rate_by_distance d-none">
              <label for="">{{ __('Pickup & Dropoff Charge Per Mile') . ' (' . $currency . ')' }}</label>
              <input type="number" value="0" step="0.01" class="form-control ltr" id="in_distance_rate" name="distance_rate"
                placeholder="{{ __('Enter Location Charge Per Mile') }}">
              <p id="err_distance_rate" class="mt-2 mb-0 text-danger em"></p>
            </div>
          @endif

          <!--<div class="form-group">-->
          <!--  <label for="">{{ __('Serial Number') . '*' }}</label>-->
          <!--  <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"-->
          <!--    placeholder="{{ __('Enter Location Serial Number') }}">-->
          <!--  <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>-->
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
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
