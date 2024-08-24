@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Invoice') }}</h4>
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
        <a href="#">{{ __('Invoice') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Invoices') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Invoice') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Invoice') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.invoice') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              
              <form id="invoiceForm" action="{{ route('vendor.invoice.store') }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                  
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Client*') }}</label>
					  <div class="row">
                        <div class="col-lg-8">
                          <select id="invoiceCustomer" class="form-control select2" placeholder="{{ __('Select Client') }}" name="customer">
								<option selected disabled value="">Select Customer</option>
                              @foreach($customers as $customer)
                                <option @if(!empty(request('customer_id'))) @selected(request('customer_id') == $customer->id) @endif value="{{ $customer->id }}">{{ $customer->username }}</option>
                              @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4">
                          <button type="button" data-toggle="modal" data-target="#addCustomer" class="btn btn-sm btn-primary">Add Customer</button>
                        </div>
                      </div>
					  
                      <p id="editErr_customer" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Invoice Number') }}</label>
                      <input type="text" value="1" class="form-control" name="invoice_number">
                      <p id="editErr_invoice_number" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
				  
				  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Status*') }}</label>
					  <select id="invoiceStatus" class="form-control select2" placeholder="{{ __('Select Status') }}" name="invoice_status">
						<option value="">Select</option>
						<option value="Unpaid">Unpaid</option>
						<option value="Partially Paid">Partially Paid</option>
						<option value="Paid">Paid</option>
						<option value="Overdue">Overdue</option>
					  </select>
                      <p id="editErr_invoice_status" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Issue date*') }}</label>
                      <input type="text" value="" class="form-control datepicker" id="issue_date" name="issue_date">
                      <p id="editErr_issue_date" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
				  
				  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Due date*') }}</label>
                      <input type="text" value="" class="form-control datepicker" id="due_date" name="due_date">
                      <p id="editErr_due_date" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
				  
				  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Recurring*') }}</label>
					  <select id="invoiceRecurring" class="form-control" name="recurring">
						<option value="0">No</option>
						<option value="1">Yes</option>
					  </select>
                      <p id="editErr_recurring" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
				  
                  

                </div>
                <div id="accordion" class="mt-5">
                  
                </div>
              </form>

            </div>
          </div>
		  
			<div class="row">
				<div class="col-lg-8">
					<h5 class="card-title">Other information</h5>
					<div class="bg-warning px-4 py-2 mb-4">
						Discount will be applicable on subtotal amount
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
							  <label>{{ __('Choose discount type') }}</label>
							  <select id="invoiceDiscountType" class="form-control" name="discount_type">
								<option value="">
									Choose discount type
								</option>
								<option value="fixed">
									Fixed
								</option>
								<option value="percentage">
									Percentage
								</option>
							  </select>
							  <p id="editErr_discount_type" class="mt-1 mb-0 text-danger em"></p>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="form-group">
							  <label>{{ __('Discount') }}</label>
							  <input type="number" min="0" step=".01" value="" class="form-control" id="discount_amount" name="discount_amount">
							  <p id="editErr_discount_amount" class="mt-1 mb-0 text-danger em"></p>
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="form-group">
							  <label>{{ __('Notes') }}</label>
							  <textarea class="form-control" id="invoice_notes" name="invoice_notes"></textarea>
							  
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="form-group">
							  <label>{{ __('Terms') }}</label>
							  <textarea class="form-control" id="invoice_terms" name="invoice_terms"></textarea>
							  
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="col-lg-4">
					<h5 class="card-title">Payment summary</h5>
					
					<table data-v-3323e4d7="" class="w-100" style="color: var(--default-font-color);">
						<tbody data-v-3323e4d7="">
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="">Sub total:</span></td>
								<td data-v-3323e4d7="" class="w-50 text-right"><span data-v-3323e4d7="">$ 0.00 </span></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="" class="label">(+) Tax:</span></td>
								<td data-v-3323e4d7="" class="text-right"><span data-v-3323e4d7="">$ 0.00</span></td>
							</tr>
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="" class="label">(-) Discount:</span></td>
								<td data-v-3323e4d7="" class="text-right"><span data-v-3323e4d7=""> $ 0.00</span></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7="" colspan="2" class="p-1"></td>
							</tr>
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7="" colspan="2" class="p-1"></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><strong data-v-3323e4d7="" class="label" style="text-transform: uppercase;">Total:</strong></td>
								<td data-v-3323e4d7="" class="text-right"><strong data-v-3323e4d7="">$ 0.00</strong></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="" class="label">Received amount:</span></td> 
								<td data-v-3323e4d7=""><div data-v-3323e4d7=""><input type="number" name="formData_received_amount" id="formData_received_amount" placeholder="" autocomplete="off" class="form-control text-right"> <!----></div></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="" class="label">Return amount:</span></td> 
								<td data-v-3323e4d7="" class="text-right"><span data-v-3323e4d7="">$ 0.00</span></td>
							</tr> 
							<tr data-v-3323e4d7="">
								<td data-v-3323e4d7=""><span data-v-3323e4d7="" class="label">Due amount:</span></td> 
								<td data-v-3323e4d7="" class="text-right"><span data-v-3323e4d7="">$ 0.00</span></td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
			
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="invoiceForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCustomerLabel">Add Customer</h5>
        </div>
        <form action="{{ route('add-customer-from-invoice') }}" method="post">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" required id="first_name" name="first_name">
                @error('first_name')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="col-12 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" required id="last_name" name="last_name">
                @error('last_name')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="col-12 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" required id="username" name="username">
                @error('username')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" required id="email" name="email">
                @error('email')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
@endsection
