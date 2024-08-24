<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Coupon') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('admin.equipment_booking.settings.store_coupon') }}"
          method="post">
          @csrf
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Name') . '*' }}</label>
                <input type="text" class="form-control" name="name" placeholder="{{ __('Enter Coupon Name') }}">
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Code') . '*' }}</label>
                <input type="text" class="form-control" name="code" placeholder="{{ __('Enter Coupon Code') }}">
                <p id="err_code" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Coupon Type') . '*' }}</label>
                <select name="type" class="form-control">
                  <option selected disabled>{{ __('Select a Type') }}</option>
                  <option value="fixed">{{ __('Fixed') }}</option>
                  <option value="percentage">{{ __('Percentage') }}</option>
                </select>
                <p id="err_type" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Value') . '*' }}</label>
                <input type="number" step="0.01" class="form-control" name="value"
                  placeholder="{{ __('Enter Coupon Value') }}">
                <p id="err_value" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Start Date') . '*' }}</label>
                <input type="text" class="form-control datepicker" autocomplete="off" name="start_date"
                  placeholder="{{ __('Enter Start Date') }}">
                <p id="err_start_date" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('End Date') . '*' }}</label>
                <input type="text" class="form-control datepicker" autocomplete="off" name="end_date"
                  placeholder="{{ __('Enter End Date') }}">
                <p id="err_end_date" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group select-auto">
                <label for="">{{ __('Equipments') }}</label>
                <select name="equipments[]" class="form-control select2" multiple="multiple">
                  @foreach ($allEquipment as $equipment)
                    <option value="{{ $equipment->id }}">
                      {{ $equipment->title }}
                      {{ $equipment->vendor ? '(' . $equipment->vendor->username . ')' : '(Admin)' }}
                    </option>
                  @endforeach
                </select>
                <p class="text-warning mt-2 mb-0">
                  <small>
                    {{ __('This coupon can be applied to these equipment.') }}<br>
                    {{ __('Leave this field empty for all equipment.') }}
                  </small>
                </p>
              </div>
            </div>
          </div>
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
