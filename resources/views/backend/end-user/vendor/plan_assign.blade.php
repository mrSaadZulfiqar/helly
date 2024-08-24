@extends('backend.layout')

@section('content')
<style>
    ul.price_summary_ag {
    list-style: none;
    margin: 0;
    background: #fff;
    padding: 8px 8px;
    border-radius: 10px;
    margin-top: 5px;
}
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Plan Assign') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Plan') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Plan Assign') }}</a>
      </li>
    </ul>
  </div>
    <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.vendor_management.plan.assign.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Name*</label>
                          <input type="text" class="form-control" name="name">
                          @error('name')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Email*</label>
                          <input type="email" class="form-control" name="email">
                          @error('email')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Contact*</label>
                          <input type="text" class="form-control" name="contact">
                          @error('contact')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Vendor*</label>
                          <select class="form-control" id="vendorSelect" name="vendor_id">
                              <option disabled selected>Select Vendor</option>
                          </select>
                          @error('vendor_id')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Plan*</label>
                          <select class="form-control select2" name="plan_id">
                              <option disabled selected>Select plan</option>
                              @foreach($plans as $plan)
                              <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                              @endforeach
                              
                          </select>
                          @error('plan_id')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <label>Payment Status*</label>
                          <select class="form-control select2" name="payment_status">
                              <option disabled selected>Select Payment Status</option>
                              <option value="pending">Pending</option>
                              <option value="complete">Complete</option>
                          </select>
                          @error('plan_id')
                          <p id="editErr_username" class="mt-1 mb-0 text-danger em">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Assign</button>
                    
                </div>
            </form>
        </div>
      </div>
    </div>
@section('script')
<script>
    $(document).ready(function() {
    $("#vendorSelect").select2({
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('admin.vendor_management.plan.get_vendors_ajax') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page_limit: 25,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.username 
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select Vendor', 
        escapeMarkup: function (markup) { return markup; }, 
        minimumInputLength: 1
    });
});

</script>

@endsection
@endsection