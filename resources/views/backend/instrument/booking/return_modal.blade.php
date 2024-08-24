{{-- receipt modal --}}
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">{{ __('Return Security Deposit Amount') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" action="{{ route('admin.equipment_booking.update_return_status') }}" method="POST">
          @csrf
          <input type="hidden" name="booking_id" value="" id="booking_id">
          <p id="err_booking_id" class="mt-2 mb-0 text-danger em"></p>
          <div class="form-group">
            <label for="">{{ __('Refund Type') }}</label>
            <select name="refund_type" class="form-control" id="refund_type">
              <option value="full">{{ __('Full') }}</option>
              <option value="partial">{{ __('Partial') }}</option>
              <option value="no_refund">{{ __('No refund') }}</option>
            </select>
            <p id="err_refund_type" class="mt-2 mb-0 text-danger em"></p>
          </div>
          <div class="form-group d-none" id="partial_amount">
            <label for="">{{ __('Partial Amount') }}
              ({{ $settings->base_currency_text }}) </label>
            <input type="text" name="partial_amount" class="form-control">
            <p id="err_partial_amount" class="mt-2 mb-0 text-danger em"></p>
          </div>
          <div class="form-group pt-0">
            <label for="">{{ __('Security Deposit Amount : ') }}
              {{ $settings->base_currency_text_position == 'left' ? $settings->base_currency_text : '' }} <span
                id="security_deposit_amount" class="mt-2 mb-0"></span>
              {{ $settings->base_currency_text_position == 'right' ? $settings->base_currency_text : '' }}</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" id="submitBtn">{{ __('Submit') }}</button>
      </div>
    </div>
  </div>
</div>
