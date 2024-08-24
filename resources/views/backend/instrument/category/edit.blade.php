<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Equipment Category') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.equipment_management.update_category') }}"
          method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name"
              placeholder="{{ __('Enter Category Name') }}">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Status') . '*' }}</label>
            <select name="status" id="in_status" class="form-control">
              <option disabled>{{ __('Select Category Status') }}</option>
              <option value="1">{{ __('Active') }}</option>
              <option value="0">{{ __('Deactive') }}</option>
            </select>
            <p id="editErr_status" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"
              placeholder="{{ __('Enter Category Serial Number') }}">
            <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the category will be shown.') }}</small>
            </p>
          </div>

          <!-- code by AG start -->
          <div class="form-group">
            
                <label class="form-check-label">
                    <input class="form-check-input" name="request_for_price" type="checkbox" value="1" id="in_request_for_price">
                    {{ __('Request For Price') }}
                </label>
                
                <p id="err_request_for_price" class="mt-2 mb-0 text-danger em"></p>
                <p class="text-warning mt-2 mb-0">
                <small>{{ __('Select this option if you want to show request for price button on equipment page of this category.') }}</small>
                </p>

            
          </div>
          
          <div class="form-group">
            
                <label class="form-check-label">
                    <input class="form-check-input" name="multiple_charges" type="checkbox" value="1" id="in_multiple_charges">
                    {{ __('Multiple Charges') }}
                </label>
                
                <p id="err_multiple_charges" class="mt-2 mb-0 text-danger em"></p>
                <p class="text-warning mt-2 mb-0">
                <small>{{ __('Select this option if you want to add multiple charges options.') }}</small>
                </p>

            
          </div>

          <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">{{ __('Placeholder Image') }}</label>
                    <br>
                    <div class="thumb-preview">
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img in_image">
                    </div>

                    <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Photo') }}
                        <input type="file" class="img-input" name="placeholder_img">
                    </div>
                    <p id="editErr_placeholder_img" class="mt-1 mb-0 text-danger em"></p>
                    
                    </div>
                </div>
            </div>
          </div>
          <!-- code by AG end -->

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
