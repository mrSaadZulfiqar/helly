@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Create Bookings') }}</h4>
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
        <a href="{{route('vendor.equipment_booking.bookings', ['language' => 'en'])}}">{{ __('Equipment Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{route('vendor.equipment_booking.create')}}">{{ __('Create Bookings') }}</a>
      </li>
    </ul>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Create Bookings') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{route('vendor.equipment_booking.bookings', ['language' => 'en'])}}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              
              <form action="{{ route('vendor.equipment_booking.store') }}" method="POST"  enctype="multipart/form-data" >
                @csrf
                <div class="row">
                
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Select Customer*') }}</label>
                      <div class="row">
                        <div class="col-lg-8">
                          <select name="user_id" id="select2_cus" class="form-control select2" required>
                              <option selected disabled>Select Customer</option>
                              @foreach($customers as $customer)
                                <option @if(!empty(request('customer_id'))) @selected(request('customer_id') == $customer->id) @endif value="{{ $customer->id }}">{{ $customer->username }}</option>
                              @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4">
                          <button type="button" data-toggle="modal" data-target="#addCustomer" class="btn btn-sm btn-primary">Add Customer</button>
                        </div>
                      </div>
                      <p id="editErr_first_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                    
                  </div>
 
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Select Equipment*') }}</label>
                      
                       <div class="row">
                        <div class="col-7">
                          <select name="equipment_id" id="equipment_id" class="form-control select2" required>
                            <option selected disabled>Select Equipment</option>
                           @foreach($equipments as $equipment)
                              <option value="{{ $equipment->id }}" title="{{ $equipment->content[0]->equipment_category_id }}">{{ $equipment->content[0]->title }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-5">
                          <div style="gap: 5px" class="d-flex flex-wrap">
                            <a target="_blank" class="btn btn-primary btn-sm" href="{{ route('vendor.equipment_management.create_equipment') }}">Add Equipment</a>
                            <button type="button" id="reloadEquipmentBtn" class="btn btn-primary btn-sm">Reload Equipment</button>
                          </div>
                        </div>
                       </div>
                      <p id="editErr_last_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  
                  <div id="company_details" class="d-none col-lg-12">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label>{{ __('Company Name*') }}</label>
                              <input type="text" class="form-control" id="company_name" 
                                placeholder="{{ __('Compamy Name') }}" readonly>
                              <input type="text" class="form-control" id="company_id" readonly hidden name="company_id">
                              <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label>{{ __('Branches*') }}</label>
                              <div id="branches_select">
                                  
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Location*') }}</label>
                      <input type="text" class="form-control" name="delivery_location" id="location_eq" required
                        placeholder="{{ __('Location') }}">
                        <input type="hidden" name="lat" id="location_eq_lat">
                        <input type="hidden" name="long" id="location_eq_long">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Booking Date *') }}</label>
                    <input type="text" id="date-range-eq" placeholder="{{ __('Select Booking Date') }}"
                    name="dates" value="" class="form-control" required>
                      <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Full Name *') }}</label>
                      <input type="text" value="" class="form-control" name="name" required
                        placeholder="{{ __('Full Name') }} ">
                      <p id="editErr_password_confirmation" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Number') }}</label>
                      <input type="tel" value="" class="form-control" name="contact_number"
                        placeholder="{{ __('Contact Number') }}">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email Address') }}</label>
                      <input type="email" placeholder="{{ __('Email Address') }}" value="" class="form-control" name="email" required>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                            <div class="input-wrap mb-3">
                                 <label>Live Load</label>
                                  <select id="live_load" name="live_load" class="form-control" required>
                                      <option selected disabled>Select Live load</option>
                                      <option value="Yes">Yes</option>
                                      <option value="No">No</option>
                                      
                                </select>
                            </div>
                        </div>
                        <br/>
                    <div class="form-group">
                       <input type="checkbox" value="Yes" name="is_emergency" class="form-checkbox" id="is_emergency">
                        <label for="is_emergency">Emergency</label>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Placement Instructions</label>
                      <textarea id="placement_instructions" name="placement_instructions" class="form-control"></textarea>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                     <label>Type of waste</label>
                      <select id="customer_punchoutlist" name="customer_punchoutlist" class="form-control">
                          <option value="">Select</option>
                          <option value="House Hold Debris">House Hold Debris</option>
                          <option value="Construction Debris">Construction Debris</option>
                          <option value="Mattress">Mattress</option>
                          <option value="Furniture">Furniture</option>
                          <option value="Concrete">Concrete</option>
                          <option value="Appliances">Appliances</option>
                      </select>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div  class="col-lg-6">
                       @if ((count($onlineGateways) > 0 || count($offlineGateways) > 0))
                        <div class="form_group">
                          <select name="gateway" class="form-control" id="gateway">
                            <option value="offline_payment">{{ __('Offline Payment') }}</option>
    
                            @if (count($onlineGateways) > 0)
                              @foreach ($onlineGateways as $onlineGateway)
                                <option value="{{ $onlineGateway->keyword }}"
                                  {{ $onlineGateway->keyword == old('gateway') || $onlineGateway->keyword == 'stax' ? 'selected' : '' }}>
                                  {{ __($onlineGateway->name) }}
                                </option>
                              @endforeach
                            @endif
    
                            @if (count($offlineGateways) > 0)
                              @foreach ($offlineGateways as $offlineGateway)
                                <option value="{{ $offlineGateway->id }}"
                                  {{ $offlineGateway->id == old('gateway') ? 'selected' : '' }}>
                                  {{ __($offlineGateway->name) }}
                                </option>
                              @endforeach
                            @endif
                          </select>
    
                          @php
                            $stripeExist = false;
    
                            if (count($onlineGateways) > 0) {
                                foreach ($onlineGateways as $onlineGateway) {
                                    if ($onlineGateway->keyword == 'stripe') {
                                        $stripeExist = true;
                                        break;
                                    }
                                }
                            }
                          @endphp
    
                            <div class="d-none mt-3" id="stripe-card-input"
                              class="mt-4 @if (
                                  $errors->has('card_number') ||
                                      $errors->has('cvc_number') ||
                                      $errors->has('expiry_month') ||
                                      $errors->has('expiry_year')) d-block @else d-none @endif">
                              <div class="input-wrap">
                                <input type="text" name="card_number" placeholder="{{ __('Enter Your Card Number') }}"
                                  autocomplete="off" oninput="checkCard(this.value)" class="form-control">
                                <p class="mt-1 text-danger" id="card-error"></p>
    
                                @error('card_number')
                                  <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                              </div>
    
                              <div class="input-wrap mt-3">
                                <input type="text" name="cvc_number" placeholder="{{ __('Enter CVC Number') }}"
                                  autocomplete="off" oninput="checkCVC(this.value)" class="form-control">
                                <p class="mt-1 text-danger" id="cvc-error"></p>
    
                                @error('cvc_number')
                                  <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                              </div>
    
                              <div class="input-wrap mt-3">
                                <input type="text" name="expiry_month" placeholder="{{ __('Enter Expiry Month') }}"  class="form-control">
    
                                @error('expiry_month')
                                  <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                              </div>
    
                              <div class="input-wrap mt-3">
                                <input type="text" name="expiry_year" placeholder="{{ __('Enter Expiry Year') }}"  class="form-control">
    
                                @error('expiry_year')
                                  <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                              </div>
                            </div>
    
                          @foreach ($offlineGateways as $offlineGateway)
                            <div id="{{ 'offline-gateway-' . $offlineGateway->id }}"
                              class="offline-gateway-info @if (
                                  $errors->has('attachment') &&
                                      request()->session()->get('gatewayId') == $offlineGateway->id) d-block @else d-none @endif">
                              @if ($offlineGateway->has_attachment == 1)
                                <div class="input-wrap mt-3">
                                  <label>{{ __('Attachment') . '*' }}</label>
                                  <br>
                                  <input type="file" name="attachment" id="offline-gateway-attachment">
    
                                  @error('attachment')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                  @enderror
                                </div>
                              @endif
    
                              @if (!is_null($offlineGateway->short_description))
                                <div class="input-wrap mt-3">
                                  <label>{{ __('Description') }}</label>
                                  <p>{{ $offlineGateway->short_description }}</p>
                                </div>
                              @endif
    
                              @if (!is_null($offlineGateway->instructions))
                                <div class="input-wrap mt-3">
                                  <label>{{ __('Instructions') }}</label>
                                  <p>{!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}</p>
                                </div>
                              @endif
                            </div>
                          @endforeach
                        </div>
                        <div class="input-wrap" style="display: inline-flex;">
                              <input type="checkbox" id="accept-terms-and-condition" name="accept_terms_conditions" value="1" required>
                                <!--data-toggle="modal" data-target="#helly-terms-popup" -->
                                <p class="ml-2 mb-0 mt-2">I accept <a target="_blank" href="{{ url('/terms-and-conditions') }}" class="text-primary" id="terms-and-condition-popup">Terms & Conditions</a></p>
                             
                              @error('accept_terms_conditions')
                                <p class="text-danger mt-1">{{ $message }}</p>
                              @enderror
                            </div>
                      @endif
                      <br><br>
                      <label><strong>Selected Card</strong></label>
                      <input class="form-control" id="card_name" readonly>
                  </div>
                  <div class="col-lg-6">
                      <div class="price-option-table mt-4">
                      <ul>
                        <li class="single-price-option ag-additional-next-item">
                          <span class="title">{{ __('Discount') }} <span class="text-success">(<i
                                class="fas fa-minus"></i>)</span> <span class="amount"
                              dir="ltr"><span id="discount-amount"
                                dir="ltr">0.00</span></span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Subtotal') }} <span class="amount"
                              dir="ltr"><span id="subtotal-amount"
                                dir="ltr"></span></span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Tax') }}
                            <span dir="ltr"></span>
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount"
                              dir="ltr"><span id="tax-amount"
                                dir="ltr"></span></span></span>
                        </li>

                          <li class="single-price-option">
                            <span class="title">{{ __('Security Deposit Amount') }} <span class="text-danger">(<i
                                  class="fas fa-plus"></i>)</span>
                              <span class="amount" dir="ltr" id="security_deposit_amount"><span
                                  dir="ltr"></span></span></span><br>
                            <span class="text-warning lh-normal">
                              <small>{{ __('This amount will be refunded, once the equipment is returned to Vendor safely') }}</small>
                            </span>
                          </li>


                        <li class="single-price-option">
                          <span class="title">{{ __('Grand Total') }} <span class="amount"
                              dir="ltr"><span id="grand-total"
                                dir="ltr"></span></span></span>
                        </li>
                      </ul>
                    </div>
                  </div>
                
                </div>
                <input id="card_id" hidden name="card_id">
                <button type="submit" class="btn btn-success">
                {{ __('Create Booking') }}
              </button>
                <button type="button" class="btn btn-success" id="get_cards" >
                {{ __('Make Payment by Customer Card') }}
              </button>
                </form>
             

            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              
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
        <form action="{{ route('add-customer-from-booking') }}" method="post">
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

  <div class="modal fade" id="addCard" tabindex="-1" aria-labelledby="addCardLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCardLabel">Add Card</h5>
        </div>
        <form action="#" method="post">
          {{-- @csrf --}}
          <div class="modal-body">
            <div>

              <div class="row mb-3">
                <div class="col-6">
                  <input type="text" class="form-control" placeholder="{{ __('First Name') }}" name="card_first_name">
                  @error('first_name')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-6">
                  <input type="text" class="form-control" placeholder="{{ __('Last Name') }}" name="card_last_name">
                  @error('last_name')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-8">
                  <input type="text" class="form-control" placeholder="{{ __('Card Number') }}" name="card_card_number">
                  @error('card_number')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-4">
                  <input type="text" class="form-control" placeholder="{{ __('CVV') }}" name="card_cvv">
                  @error('cvv')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-6">
                  <input type="number" step="1" min="1" max="12" class="form-control" placeholder="{{ __('Expiry Month') }}" name="card_exp_month">
                  @error('exp_month')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-6">
                  <input type="text" class="form-control" placeholder="{{ __('Expiry Year') }}" name="card_exp_year">
                  @error('exp_year')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-6">
                    <input class="form-control" value="Dubai" placeholder="{{ __('Location') }}" name="card_location"  id="pay_meth_location"/>
                    <input id="pay_meth_location_lat" name="card_lat"  value="20" hidden>
                    <input id="pay_meth_location_long" name="card_lng" value="20"  hidden>
                  
                  @error('location')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-6">
                  <input type="text" class="form-control" placeholder="{{ __('City') }}" name="card_city">
                  @error('city')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-lg-12">
                    <textarea class="form-control" placeholder="{{ __('Address1') }}" name="card_address1"></textarea>
                  
                  @error('address1')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              
              {{-- <div class="row mb-3">
                <div class="col-lg-12">
                  <textarea class="form-control" placeholder="{{ __('Address2') }}" name="address2"></textarea>
                  
                  @error('address2')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div> --}}
              


            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="save-card" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  
<!-- Modal -->
<div class="modal fade" id="card_list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Cards List</h5>
        <button type="button" data-target="#addCard" data-toggle="modal" class="btn btn-sm btn-primary">
          Add Card 
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Card Number</th>
                        <th>CVV</th>
                        <th>Expiry Month</th>
                        <th>Expiry Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody id="card_list_body">
                    
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
  



  @section('script')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  <script>
  $(document).ready(function(){

      $(document).on('click', '#reloadEquipmentBtn', function() {
        $.ajax({
              type:"GET",
              url:"{{ route('vendor.equipment.data') }}",
              success:function(response){
                
                var newOptions = response.data.allEquipment;
                if (newOptions != null) {
                  $("#equipment_id").empty();
                  newOptions.forEach(option => {
                  $("#equipment_id").append(
                    $('<option>', {
                      value: option.id,
                      text: option.title
                    })
                  );
                })                  
                }else{
                  alert("Unable to load new equipments")
                }
              },
              
          });
      })

      $(document).on('change','#select2_cus',function(){
          const user_id = $(this).val();
          $.ajax({
              type:"GET",
              url:"{{ route('vendor.equipment_booking.get_user_data') }}",
              data:{id:user_id},
              success:function(response){
                //   const data = JSON.parse(response);
                console.log(response)
                
                
                $("input[name='name']").val(response.user.first_name + " " + response.user.last_name)
                $("input[name='contact_number']").val(response.user.contact_number)
                $("input[name='email']").val(response.user.email)
                
                if(response.hasOwnProperty("company"))
                {
                    $('#company_details').removeClass('d-none');
                    $('#company_name').val(response.company.name);
                    $('#company_id').val(response.company.id);
                }else{
                    $('#company_details').addClass('d-none');
                    $('#company_name').val("");
                    $('#company_id').val("");
                }
                
                if(response.hasOwnProperty("branches"))
                {
                    $('#company_details').removeClass('d-none');
                    var html = "";
                    html += "<select name='branch_id' class='form-control'>";
                    response.branches.forEach((item)=>{
                        html += "<option value='"+ item.id +"'>"+ item.name +"</option>";
                    });
                    html += "</select>";
                    $('#branches_select').html(html);
                }else{
                    $('#company_details').addClass('d-none');
                }
                
              },
              
          });
      });
      

      function getCustomerCards() {
            const user_id = $('#select2_cus').val();
            if (user_id != null) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('vendor.equipment_booking.get_cards') }}",
                    data: { id: user_id },
                    success: function(response) {
                        var html = "";
                        response.forEach((item) => {
                            html +='<tr>' ;
                            html +='<td>'+ item.first_name +'</td>' ;
                            html +='<td>'+ item.last_name +'</td>' ;
                            html +='<td>'+ item.card_number +'</td>' ;
                            html +='<td>'+ item.cvv +'</td>' ;
                            html +='<td>'+ item.exp_month +'</td>' ;
                            html +='<td>'+ item.exp_year +'</td>' ;
                            html +='<td><input type="radio" name="card_data" data-name="'+item.first_name+' '+item.last_name +'" class="select_card" value="'+ item.id +'" id="card_'+ item.id +'" hidden> <label for="card_'+ item.id +'" class=" text-white btn btn-primary">Select</label></td>' ;
                            html +='</tr>' ;
                        });
                        
                        $('#card_list_body').html(html);
                        $('#card_list').modal('show');
                    }
                });
            } else {
                alert('Please select Customer');
            }
        }

        $(document).on('click', '#save-card', function(){

          const user_id = $('#select2_cus').val();

          if (user_id != null) {
            const data = {
              first_name : $("input[name='card_first_name']").val(),
              last_name : $("input[name='card_last_name']").val(),
              card_number : $("input[name='card_card_number']").val(),
              cvv : $("input[name='card_cvv']").val(),
              exp_month : $("input[name='card_exp_month']").val(),
              exp_year : $("input[name='card_exp_year']").val(),
              location : $("input[name='card_location']").val(),
              lat : $("input[name='card_lat']").val(),
              lng : $("input[name='card_lng']").val(),
              address1 : $("textarea[name='card_address1']").val(),
              city : $("input[name='card_city']").val(),
              user_id : user_id,
              _token : $('meta[name="csrf-token"]').attr('content')
            }  

            $.ajax({
                    type: "POST",
                    url: "{{ route('store-customer-card-ajax') }}",
                    data: data,
                    success: function(response) {
                      getCustomerCards()
                    }
                });

          } else {
            alert('Please select Customer');
          }

          


        });
        $(document).on('click', '#get_cards', getCustomerCards);
        $(document).on('change','.select_card',function(){
            const id = $(this).val();
            if($(this).prop('checked',true))
            {
                $('#card_id').val(id);
                $('#card_name').val($(this).data('name'));
                $(this).parent().find('label').removeClass('btn-primary');
                $(this).parent().find('label').addClass('btn-success');
                $(this).parent().find('label').text('Selected');
                $('#card_list').modal('hide');
                
            }else{
                $('#card_id').val('');
                $(this).parent().find('label').removeClass('btn-success');
                $(this).parent().find('label').addClass('btn-primary');
                $(this).parent().find('label').text('Select');
            }
        });
  });
  

  
  $('#gateway').change(function(){
        let gateway = $(this).val();
        if(gateway == "stripe")
        {
            $('#stripe-card-input').removeClass('d-none');
        }
        else{
            $('#stripe-card-input').addClass('d-none');
        }
    });
    var searchInput = 'location_eq';
    let baseURL = "{{ url('/') }}";
    let tax = $('#tax-amount').text();
    let security_deposit_amount = $('#security_deposit_amount').text();
    let equipmentId = "";
    $('#equipment_id').change(function(){
        equipmentId = $(this).val();
        
      $.ajax({
            type: "GET",
            url: "{{ route('vendor.equipment_booking.get_equipment') }}",
            contentType: "application/json",
            data:{id:equipmentId},
            success: function(response) {
                $('#tax-amount').text(response.symbol+ " "+response.tax);
                $('#security_deposit_amount').text(response.symbol+ " "+response.details.security_deposit_amount);
            }
        });


    });
    let options = { minimumFractionDigits: 2, maximumFractionDigits: 2 };


    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('location_eq_lat').value = near_place.geometry.location.lat();
            document.getElementById('location_eq_long').value = near_place.geometry.location.lng();
            
            $('#location_field').change();
        });
    });
    
    $(function() {
  $('#date-range-eq').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});


$(document).on('change', '#extra_services, #type_of_rental, #live_load, #is_emergency, #location_field', function(){
     // get the difference of two dates, date should be in 'YYYY-MM-DD' format
    let dates = $('#date-range-eq').val();
    $('.ag-eq-booking-addtional-lineitem').remove();
    // get the minimum price
      let url = `${baseURL}/equipment/${equipmentId}/min-price`;
      
      // code by AG start
        // location data
        var location__ = '';
        var lat__ = '';
        var long__ = '';
        
            location__ = $('#location_eq').val();
            lat__ = $('#location_eq_lat').val();
            long__ = $('#location_eq_long').val();
        
        
        // for temporary toilet
        var extra_services = 0;
        var type_of_rental = '';
        if($('#extra_services').length > 0){
            extra_services = $('#extra_services').val();
        }
        
        if($('#type_of_rental').length > 0){
            type_of_rental = $('#type_of_rental').val();
        }
        
        // for dumpster / multiple charges category
        var live_load = '';
        var is_emergency = '';
        if($('#live_load').length > 0){
            live_load = $('#live_load').val();
        }
        
        if($('#is_emergency').length > 0){
            
            if( $('#is_emergency').prop('checked') == true){
                is_emergency = $('#is_emergency').val();
            }
            
        }
        
    $('.ag-eq-booking-addtional-lineitem').remove();
      // code by AG end

      $.get(url, { dates: dates, extra_services:extra_services, type_of_rental:type_of_rental,live_load:live_load,is_emergency:is_emergency,location__:location__,lat__:lat__,long__:long__ }, function (response) {
        if ('minimumPrice' in response) {
          let minPrice = response.minimumPrice;

          // recalculate the tax
          let calculatedTax = minPrice * (tax / 100);

          $('#booking-price').text(minPrice.toLocaleString(undefined, options));
          $('#subtotal-amount').text(minPrice.toLocaleString(undefined, options));
          $('#tax-amount').text(calculatedTax.toLocaleString(undefined, options));

          let shippingCharge;

         
          
          shippingCharge = parseFloat(response.shipping_cost);
              $('#shipping-charge').text(shippingCharge);

          let grandTotal = minPrice + calculatedTax + shippingCharge + security_deposit_amount;

          $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
          
          $('.ag-eq-booking-addtional-lineitem').remove();
          if(response.additional_charges_item_html != ''){
              $('.ag-additional-next-item').before(response.additional_charges_item_html);
          }
        } else if ('errorMessage' in response) {
          toastr['error'](response.errorMessage);
        }
      });
      
      
    
  });
   $('#select2_cus').select2();
    $('#equipment_id').select2();
    
    $('#equipment_id').on("change", function(){
        let eqCatId = $(this).select2('data')[0].title;
        if(eqCatId != 90){
            $('#customer_punchoutlist').parent().hide();   
        }else{
            $('#customer_punchoutlist').parent().show();   
        }
    })
    </script>
  @endsection

@endsection
