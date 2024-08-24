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
          action="{{ route('admin.equipment_booking.settings.update_location') }}" method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name"
              placeholder="{{ __('Enter Location Name') }}">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          @if ($twoWayDeliveryStatus == 1)
            <div class="form-group">
              <label for="">{{ __('Two Way Delivery Charge') . ' (' . $currency . ')' }}</label>
              <input type="number" value="0" step="0.01" id="in_charge" class="form-control ltr" name="charge"
                placeholder="{{ __('Enter Location Charge') }}">
              <p id="editErr_charge" class="mt-2 mb-0 text-danger em"></p>
            </div>
          @endif

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"
              placeholder="{{ __('Enter Location Serial Number') }}">
            <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the location will be shown.') }}</small>
            </p>
          </div>
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
