<div class="modal fade" id="leadRemarkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Lead Remarks') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.vendor_management.add-lead-remark') }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="row no-gutters">
            <div class="col-lg-12">
                <h3>Remarks</h3>
            </div>
            <div class="col-lg-12 remarks-list">
                <div class="list-group agcd-lead-remarks-list">
                    <!-- remarks will be appeded here by jQuery -->
                    
                </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label for="">{{ __('Remark') }}</label>
                <textarea id="in_remark" class="form-control" name="remark"
                  placeholder="{{ __('Enter Remark') }}"></textarea>
                
              </div>
            </div>
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
