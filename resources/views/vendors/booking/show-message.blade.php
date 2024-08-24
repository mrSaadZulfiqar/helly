{{-- message modal --}}
<div class="modal fade" id="priceMsgModal-{{ $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Request Price Message') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <textarea class="form-control" rows="10">{{ $booking->price_message }}</textarea>
        </div>
      </div>

      <div class="modal-footer"></div>
    </div>
  </div>
</div>
