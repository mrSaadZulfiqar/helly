@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->equipment_details_page_title }}
  @endif
@endsection

@section('metaKeywords')
  {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $details->meta_description }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => Str::limit($details->title, 20, '...'),
  ])

<style>
    span.current-location-nav {
    position: absolute;
    top: 16px;
    right: 18px;
    color: var(--primary-color);
    display: inline-flex;
    cursor: pointer;
    background: #fff;
}
.current-location-nav i.far.fa-location {
    position: unset !IMPORTANT;
}
</style>
  <!--====== Start Equipment Details Section ======-->
  <section class="equipment-details-section pt-130 pb-110">
    <div class="container">
      <div class="row">
           <div class="col-lg-4">
          <div class="equipement-sidebar-info">
            <form action="{{ route('equipment.make_booking') }}" method="POST" enctype="multipart/form-data"
              id="equipment-booking-form">
              @csrf
              <input type="hidden" name="equipment_id" value="{{ $details->id }}">

              <div class="booking-form">
                @php
                  $position = $currencyInfo->base_currency_symbol_position;
                  $symbol = $currencyInfo->base_currency_symbol;

                  // calculate tax
                  $currTotal = $details->lowest_price;
                  
                  if(is_equipment_multiple_charges($details->equipment_category_id) || is_equipment_temporary_toilet_type($details->equipment_category_id) || is_equipment_storage_container_type($details->equipment_category_id)){
                    if (!empty($multiple_charges_settings['base_price'])){
                        $currTotal = $multiple_charges_settings['base_price']??0;
                    }
                  }
                  
                  $currTotal = amount_with_commission($currTotal);
                  
                  $taxAmount = $basicData['equipment_tax_amount'];
                  $calculatedTax = $currTotal * ($taxAmount / 100);

                  // calculate grand total
                  $grandTotal = $currTotal + $calculatedTax + $details->security_deposit_amount;
                @endphp

                <div class="price-info">
                  <h5>{{ __('Price') }}</h5>
                    
                    @if(is_equipment_multiple_charges($details->equipment_category_id) || is_equipment_temporary_toilet_type($details->equipment_category_id) || is_equipment_storage_container_type($details->equipment_category_id))
                        <div class="price-tag">
                            
                            @if (!empty($multiple_charges_settings['base_price']))
                            <h4>
                                {{ $position == 'left' ? $symbol : '' }}<span
                                  id="booking-price">{{ amount_with_commission($multiple_charges_settings['base_price']) }}</span>{{ $position == 'right' ? $symbol : '' }}
                            </h4>
                            @endif
                        </div>
                      @else
                          <div class="price-tag">
                            @if (!empty($currTotal))
                              <h4 dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                                  id="booking-price">{{ number_format($currTotal, 2) }}</span>{{ $position == 'right' ? $symbol : '' }}
                              </h4>
                            @endif
                          </div>
                          
                        @endif
                </div>

                <div class="pricing-body">
                  {{-- show error message for request-price-message --}}
                  @error('price_message')
                    <div class="row">
                      <div class="col">
                        <div class="alert alert-danger alert-block">
                          <strong>{{ $message }}</strong>
                          <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                      </div>
                    </div>
                  @enderror

                  <div class="price-option">
                      
                    @if(is_equipment_multiple_charges($details->equipment_category_id))
                            
                                @if (!empty($multiple_charges_settings['additional_daily_cost']))
                                  <span
                                    class="span-btn day"><b>Additional Daily Cost: </b>{{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($multiple_charges_settings['additional_daily_cost']) }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}</span>
                                @endif
                                
                    @elseif(is_equipment_temporary_toilet_type($details->equipment_category_id))
                            @if (!empty($multiple_charges_settings['additional_service_cost']))
                                  <span
                                    class="span-btn day"><b>Additional Service Cost: </b>{{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($multiple_charges_settings['additional_service_cost']) }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Service') }}</span>
                                @endif
                    
                    @elseif(is_equipment_storage_container_type($details->equipment_category_id))
                            
                                
                    @else
                      
                        @if (!empty($details->per_day_price))
                          <span
                            class="span-btn day">{{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($details->per_day_price) }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}</span>
                        @endif
    
                        @if (!empty($details->per_week_price))
                          <span
                            class="span-btn week">{{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($details->per_week_price) }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Week') }}</span>
                        @endif
    
                        @if (!empty($details->per_month_price))
                          <span
                            class="span-btn month">{{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($details->per_month_price) }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Month') }}</span>
                        @endif
                        
                    @endif
                  </div>

                  @if (Auth::guard('web')->check() == false && $basicData['guest_checkout_status'] == 1)
                    <div class="alert alert-warning mb-0 mt-4">
                      {{ __('You are now booking as a guest') . '. ' . __('if you want to log in before booking') . ', ' . __('then please') }}
                      <a href="{{ route('user.login', ['redirect_path' => 'equipment-details']) }}"
                        id="login-link">{{ __('Click Here') }}</a>
                    </div>
                  @endif

                  <div class="form_group">
                      
                      <!--code by AG start-->
                      <div class="input-wrap mb-3">
                          <input type="text" placeholder="{{ __('Enter Your Location') }}" id="location_field" value="<?php echo $eqp_search_data['address']??''; ?>" name="delivery_location"
                          value="">
                          <span class="current-location-nav" onclick="getLocation()">
                            <i class="far fa-location" style="font-family:'Font Awesome 5 Pro'"></i>
                        </span>
                          <input type="hidden" name="lat" id="location_lat" value="<?php echo $eqp_search_data['lat']??''; ?>">
                        <input type="hidden" name="long" id="location_long" value="<?php echo $eqp_search_data['long']??''; ?>">
                      </div>
                      <!--code by AG end-->
                      
                      
                    <div class="input-wrap">
                      <input type="text" id="date-range" placeholder="{{ __('Select Booking Date') }}"
                        name="dates" value="{{ $eqp_search_data['dates']??'' }}" readonly>
                      <i class="far fa-calendar-alt"></i>

                      <p id="booking-day" class="mt-2 {{ $currentLanguageInfo->direction == 1 ? 'mr-3' : 'ml-3' }}">
                      </p>

                      @error('dates')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="input-wrap mt-3">
                      @guest('web')
                        <input type="text" placeholder="{{ __('Enter Full Name') }}" name="name"
                          value="{{ old('name') }}">
                      @endguest

                      @auth('web')
                        @php
                          $name = Auth::guard('web')->user()->first_name;
                          if (!empty(Auth::guard('web')->user()->last_name)) {
                              $name = $name . ' ' . Auth::guard('web')->user()->last_name;
                          }
                        @endphp
                        <input type="text" placeholder="{{ __('Enter Full Name') }}" name="name"
                          value="{{ $name }}">
                      @endauth
                      <i class="far fa-user"></i>

                      @error('name')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="input-wrap mt-3">
                      @guest('web')
                        <input type="text" placeholder="{{ __('Enter Contact Number') }}" name="contact_number"
                          value="{{ old('contact_number') }}">
                      @endguest

                      @auth('web')
                        <input type="text" placeholder="{{ __('Enter Contact Number') }}" name="contact_number"
                          value="{{ Auth::guard('web')->user()->contact_number }}">
                      @endauth
                      <i class="far fa-phone"></i>

                      @error('contact_number')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="input-wrap mt-3">
                      @guest('web')
                        <input type="email" placeholder="{{ __('Enter Email') }}" name="email"
                          value="{{ old('email') }}">
                      @endguest

                      @auth('web')
                        <input type="email" placeholder="{{ __('Enter Email') }}" name="email"
                          value="{{ Auth::guard('web')->user()->email }}">
                      @endauth
                      <i class="far fa-envelope"></i>

                      @error('email')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>
      @php
$user = auth()->user();

if ($user !== null) {
    if ($user->account_type == 'corperate_account') {
        $branches = \App\Models\CompanyBranch::where('owner_id', $user->id)->get();
        $company = \App\Models\Company::where('customer_id', $user->id)->first();
    } elseif (isset($user->owner_id)) {
        $branch_ids = \App\Models\BranchUser::where('user_id', $user->id)->get()->pluck('branch_id');
        $branches =  \App\Models\CompanyBranch::whereIn('id', $branch_ids)->get();
        $company = \App\Models\Company::where('customer_id', $user->owner_id)->first();
    }
}
@endphp

@if(isset($user) && ($user->account_type == 'corperate_account' || isset($user->owner_id)))
    <div class="input-wrap mt-3">
        @if(isset($company))
            <input value="{{ $company->id }}" hidden name="company_id">
        @endif
        <select class="form-control" name="branch_id">
            <option selected disabled>Select branch</option>
            @if(isset($branches))
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            @endif
        </select>

        @error('branch_id')
            <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>
@endif

                  </div>

                  @php $shippingMethod = session()->get('shippingMethod'); @endphp

                  <div class="form_group">
                    @if ($basicData['self_pickup_status'] == 1 || $basicData['two_way_delivery_status'] == 1)
                      <div class="reserved-filter d-flex justify-content-between">
                        @if ($basicData['self_pickup_status'] == 1)
                          <div class="single-method d-flex">
                            <input type="radio" id="self-pickup" name="shipping_method" value="self pickup"
                              {{ $shippingMethod == 'self pickup' ? 'checked' : '' }}>
                            <label for="self-pickup"><span>{{ __('Self Pickup') }}</span></label>
                          </div>
                        @endif

                        @if ($basicData['two_way_delivery_status'] == 1)
                          <div class="single-method d-flex">
                            <input type="radio" id="two-way-delivery" name="shipping_method"
                              value="two way delivery" {{ $shippingMethod == 'two way delivery' ? 'checked' : '' }}>
                            <label for="two-way-delivery"><span>{{ __('Pickup and Dropoff') }}</span></label>
                          </div>
                        @endif
                      </div>

                      @error('shipping_method')
                        <p class="text-danger mt-2">{{ $message }}</p>
                      @enderror
                    @endif

                    <div id="reload-div">
                      <div id="location-wrapper">
                        @if ($shippingMethod == 'self pickup')
                          <div id="self-pickup-select" class="mt-4">
                            @if (count($locations) > 0)
                              <select name="location" class="wide form_control">
                                <option selected disabled>{{ __('Select a Location') }}</option>

                                @foreach ($locations as $location)
                                  <option value="{{ $location->id }}"
                                    {{ $location->id == old('location') ? 'selected' : '' }}>
                                    {{ $location->name }}
                                  </option>
                                @endforeach
                              </select>
                            @endif
                            @error('location')
                              <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                          </div>
                        @endif

                        @if ($shippingMethod == 'two way delivery')
                          <div id="two-way-delivery-select" class="mt-4">
                            @if (count($locations) > 0)
                              <select name="location" class="wide form_control">
                                <option selected disabled>{{ __('Select a Location') }}</option>

                                @foreach ($locations as $location)
                                  <option value="{{ $location->id }}" data-charge="{{ $location->charge }}"
                                    {{ $location->id == old('location') ? 'selected' : '' }}>
                                    {{ $location->name }} @if ($basicData['two_way_delivery_status'] == 1)
                                      (+
                                      {{ $position == 'left' ? $symbol : '' }}{{ $location->charge }}{{ $position == 'right' ? $symbol : '' }})
                                    @endif
                                  </option>
                                @endforeach
                              </select>

                              @if ($basicData['two_way_delivery_status'] == 1)
                                <p class="mt-2 text-info">
                                  {{ __('Shipping charge is only applicable for') . ' "' . __('Pickup and Dropoff') . '".' }}
                                </p>
                              @endif
                            @endif
                            @error('location')
                              <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  
                  
                  <!--code by AG start-->
                      @if(is_equipment_temporary_toilet_type($details->equipment_category_id))
                      
                        <div class="form-group">
                            <div class="input-wrap mb-3">
                                 <label>Extra Services</label>
                                 <input type="number" value="0" min="0" step="1" id="extra_services" name="extra_services" class="form-control">
                            
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-wrap mb-3">
                                 <label>Type of Rental</label>
                                  <select id="type_of_rental" name="type_of_rental" class="form-control">
                                      <option selected="" value="">Select</option>
                                      <option value="Construction Event">Construction Event</option>
                                      <option value="Special Event">Special Event</option>
                                      
                                      <option value="Long Term">Long Term</option>
                                      <option value="Short Term">Short Term</option>
                                </select>
                            </div>
                        </div>
                      @endif
                    @if(isset($details->equipment_category_id))
                        @if($details->equipment_category_id == 90)
                            <div class="form-group">
                                <div class="input-wrap mb-3">
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
                                </div>
                            </div> 
                        @endif
                    @endif
                   
                    <div class="form-group">
                        <div class="input-wrap mb-3">
                             <label>Placement Instructions</label>
                              <textarea id="placement_instructions" name="placement_instructions" class="form-control"></textarea>
                        </div>
                    </div>  
                      
                      
                       @if(is_equipment_multiple_charges($details->equipment_category_id))
                            
                                @if (!empty($multiple_charges_settings['live_load']) && $multiple_charges_settings['live_load'] == 'Yes')
                                    
                                    <div class="form-group d-none">
                                        <div class="input-wrap mb-3">
                                             <label>Live Load</label>
                                              <select id="live_load" name="live_load" class="form-control">
                                                  <option value="Yes" selected>Yes</option>
                                                  <option value="No">No</option>
                                                  
                                            </select>
                                        </div>
                                    </div>
                                    
                                @endif
                                
                                <div class="form-group">
                                    <div class="input-wrap mb-3 d-flex">
                                         
                                         <input type="checkbox" value="Yes" name="is_emergency" class="form-checkbox" id="is_emergency">
                                          <label for="is_emergency" class="m-0 ml-2">Emergency</label>
                                    </div>
                                </div>
                                
                        @endif
                     
                    
                  <!--code by AG end-->

                  <div class="extra-option pt-35 pb-35">
                    <div class="form-group d-flex">
                      <input type="text" class="form-control" id="coupon-code"
                        placeholder="{{ __('Enter Your Coupon') }}">
                      <button class="btn" onclick="applyCoupon(event)">{{ __('Apply') }}</button>
                    </div>

                    <div class="price-option-table mt-4">
                      <ul>
                        <li class="single-price-option ag-additional-next-item">
                          <span class="title">{{ __('Discount') }} <span class="text-success">(<i
                                class="fas fa-minus"></i>)</span> <span class="amount"
                              dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span id="discount-amount"
                                dir="ltr">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Subtotal') }} <span class="amount"
                              dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span id="subtotal-amount"
                                dir="ltr">{{ number_format($currTotal, 2) }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                        </li>

                        <li class="single-price-option">
                          <span class="title">{{ __('Tax') }}
                            <span dir="ltr">{{ '(' . $basicData['equipment_tax_amount'] . '%)' }}</span>
                            <span class="text-danger">(<i class="fas fa-plus"></i>)</span> <span class="amount"
                              dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span id="tax-amount"
                                dir="ltr">{{ number_format($calculatedTax, 2) }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                        </li>

                        @if ($basicData['two_way_delivery_status'] == 1)
                          <li class="single-price-option">
                            <span class="title">{{ __('Shipping Charge') }} <span class="text-danger">(<i
                                  class="fas fa-plus"></i>)</span> <span class="amount"
                                dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span id="shipping-charge"
                                  dir="ltr">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                          </li>
                        @endif

                        @if ($details->security_deposit_amount > 1)
                          <li class="single-price-option">
                            <span class="title">{{ __('Security Deposit Amount') }} <span class="text-danger">(<i
                                  class="fas fa-plus"></i>)</span>
                              <span class="amount" dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                                  dir="ltr">{{ $details->security_deposit_amount }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                            <span class="text-warning lh-normal">
                              <small>{{ __('This amount will be refunded, once the equipment is returned to Vendor safely') }}</small>
                            </span>
                          </li>
                        @endif


                        <li class="single-price-option">
                          <span class="title">{{ __('Grand Total') }} <span class="amount"
                              dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span id="grand-total"
                                dir="ltr">{{ number_format($grandTotal, 2) }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                        </li>
                      </ul>
                    </div>
                  </div>
                   @if(auth()->check())
                    @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')
                    <div class="form_group">
                        <div class="input-wrap mt-3">
                            <input type="number" placeholder="{{ __('Enter Job Number') }}" name="job_number"
                              value="">
                          <i class="far fa-life-ring"></i>
                          @error('job_number')
                            <p class="text-danger mt-1">{{ $message }}</p>
                          @enderror
                        </div>
                        <div class="input-wrap mt-3">
                            <input type="number" placeholder="{{ __('Enter Po Number') }}" name="po_number"
                              value="">
                          <i class="far fa-chart-bar"></i>
                          @error('po_number')
                            <p class="text-danger mt-1">{{ $message }}</p>
                          @enderror
                        </div>
                    </div>
                    @endif
                    @endif


                  @if ($details->price_btn_status == 0 && (count($onlineGateways) > 0 || count($offlineGateways) > 0))
                    <div class="form_group">
                      <select name="gateway" class="form_control">
                        <!--<option disabled>{{ __('Select Payment Gateway') }}</option>-->

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

                      @if ($stripeExist == true)
                        <div id="stripe-card-input"
                          class="mt-4 @if (
                              $errors->has('card_number') ||
                                  $errors->has('cvc_number') ||
                                  $errors->has('expiry_month') ||
                                  $errors->has('expiry_year')) d-block @else d-none @endif">
                          <div class="input-wrap">
                            <input type="text" name="card_number" placeholder="{{ __('Enter Your Card Number') }}"
                              autocomplete="off" oninput="checkCard(this.value)">
                            <p class="mt-1 text-danger" id="card-error"></p>

                            @error('card_number')
                              <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="input-wrap mt-3">
                            <input type="text" name="cvc_number" placeholder="{{ __('Enter CVC Number') }}"
                              autocomplete="off" oninput="checkCVC(this.value)">
                            <p class="mt-1 text-danger" id="cvc-error"></p>

                            @error('cvc_number')
                              <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="input-wrap mt-3">
                            <input type="text" name="expiry_month" placeholder="{{ __('Enter Expiry Month') }}">

                            @error('expiry_month')
                              <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="input-wrap mt-3">
                            <input type="text" name="expiry_year" placeholder="{{ __('Enter Expiry Year') }}">

                            @error('expiry_year')
                              <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                      @endif

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
                  @endif
                  @if ($basicData['guest_checkout_status'] == 1)
                  
                  <!--code by AG start-->
                    
                    <div class="input-wrap" style="display: inline-flex;">
                      <input type="checkbox" id="accept-terms-and-condition" name="accept_terms_conditions" value="1">
                         <!--data-toggle="modal" data-target="#helly-terms-popup"-->
                        <p class="ml-2">I accept <a target="_blank" href="{{ url('/terms-and-conditions') }}" class="text-primary" id="terms-and-condition-popup">Terms & Conditions</a></p>
                     
                      @error('accept_terms_conditions')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>
                   
                    <!--code by AG end-->
                    @if(count($user_cards) == 1)
                        <input value="{{ $user_cards[0]->id }}" name="card_id" hidden>
                        <div class="button text-center mt-30">
                          <button type="submit" class="main-btn" >{{ __('Book Now') }}</button>
                        </div>
                    @elseif(count($user_cards) == 0)
                        <div class="button text-center mt-30">
                          <button type="submit" class="main-btn" >{{ __('Book Now') }}</button>
                        </div>
                    @else
                        <input value="{{ $default_user_card->id ?? "" }}" name="card_id" id="card_id" hidden>
                        <div class="button text-center mt-30">
                          <button type="button" class="main-btn" data-toggle="modal" data-target="#card_list"  >{{ __('Book Now') }}</button>
                        </div>
                    @endif
                    
                  @elseif(Auth::guard('web')->check() == false && $basicData['guest_checkout_status'] == 0)
                    <div class="button text-center mt-30">
                      <a href="{{ route('user.login', ['redirect_path' => 'equipment-details']) }}" class="main-btn">
                        {{ __('Login') }}
                      </a>
                    </div>
                  @else
                  
                    <!--code by AG start-->
                    
                    <div class="input-wrap" style="display: inline-flex;">
                      <input type="checkbox" id="accept-terms-and-condition" name="accept_terms_conditions" value="1">
                        <!--data-toggle="modal" data-target="#helly-terms-popup" -->
                        <p class="ml-2">I accept <a target="_blank" href="{{ url('/terms-and-conditions') }}" class="text-primary" id="terms-and-condition-popup">Terms & Conditions</a></p>
                     
                      @error('accept_terms_conditions')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>
                    
                    <!--code by AG end-->
                    
                    <div class="button text-center mt-30">
                      <button type="submit"class="main-btn">{{ __('Book Now') }}</button>
                    </div>
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-8">
          @php $sliderImages = json_decode($details->slider_images); 
            $sliderImages = (empty($sliderImages))?array():$sliderImages;
          @endphp
            
            @if(!empty($sliderImages))
          <div class="equipment-gallery-box d-flex mb-40">
            <div class="equipment-slider-wrap">
              <div class="equipment-gallery-slider">
                @foreach ($sliderImages as $sliderImage)
                  <div class="single-gallery-item"
                    data-thumb="{{ asset('assets/img/equipments/slider-images/' . $sliderImage) }}">
                    <a href="{{ asset('assets/img/equipments/slider-images/' . $sliderImage) }}" class="img-popup">
                      <img data-src="{{ asset('assets/img/equipments/slider-images/' . $sliderImage) }}" alt="image"
                        class="lazy">
                    </a>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="equipment-gallery-arrow"></div>
          </div>
          @endif

          <div class="description-wrapper">
            <h3 class="title mb-2">{{ $details->title }}</h3>
            <h6>{{ optional($details->vendor)->shop_name }}</h6>
            <div class="vendor-name">
              @if ($details->vendor)
                <!--{{ __('By') }}-->
                <!--<a href="{{ route('frontend.vendor.details', $details->vendor->username) }}">-->
                <!--  {{ $vendor = optional($details->vendor)->username }}-->
                <!--</a>-->
              @else
                <!--{{ __('By') }} {{ __('Admin') }}</a>-->
              @endif
            </div>

            <br>
            <a href="#" class="voucher-btn category-search" data-category_slug="{{ $details->categorySlug }}">
              {{ $details->categoryName }}
            </a>

            <div class="description-tabs">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#description">{{ __('Description') }}</a>
                </li>

                <!--<li class="nav-item">-->
                <!--  <a class="nav-link" data-toggle="tab" href="#features">{{ __('Features') }}</a>-->
                <!--</li>-->

                <!--<li class="nav-item">-->
                <!--  <a class="nav-link" data-toggle="tab" href="#reviews">{{ __('Reviews') }}</a>-->
                <!--</li>-->
              </ul>
            </div>

            <div class="tab-content mt-30">
              <div id="description" class="tab-pane fade show active">
                <div class="description-content-box">
				<?php echo $fields_html; // code by AG ?>
                  <p>{!! replaceBaseUrl($details->description, 'summernote') !!}</p>
                </div>
              </div>

              <div id="features" class="tab-pane fade">
                <div class="features-content-box">
                  @php $features = explode(PHP_EOL, $details->features); @endphp

                  <div class="content-table table-responsive">
                    <table class="table">
                      <tbody>
                        @foreach ($features as $feature)
                          <tr>
                            <td>{{ $feature }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div id="reviews" class="tab-pane fade">
                <div class="equipment-review-content-box">
                  @if (count($reviews) == 0)
                    <h5 class="mb-30">{{ __('This equipment has no review yet') . '!' }}</h5>
                  @else
                    @foreach ($reviews as $review)
                      <div class="equipment-review-user d-flex">
                        <div class="thumb">
                          @if (empty($review->user->image))
                            <img data-src="{{ asset('assets/img/user.png') }}" alt="image" class="lazy">
                          @else
                            <img data-src="{{ asset('assets/img/users/' . $review->user->image) }}" alt="image"
                              class="lazy">
                          @endif
                        </div>

                        <div class="content">
                          <ul class="rating lh-1">
                            @for ($i = 0; $i < $review->rating; $i++)
                              <li><i class="fas fa-star"></i></li>
                            @endfor
                          </ul>

                          @php
                            $name = $review->user->username;
                            $date = date_format($review->created_at, 'F d, Y');
                          @endphp

                          <span
                            class="date"><span>{{ $name == ' ' ? 'User' : $name }}</span>{{ ' – ' . $date }}</span>
                          <p>{{ $review->comment }}</p>
                        </div>
                      </div>
                    @endforeach
                  @endif

                  @guest('web')
                    <a href="{{ route('user.login', ['redirect_path' => 'equipment-details']) }}" class="main-btn">
                      {{ __('Login') }}
                    </a>
                  @endguest

                  @auth('web')
                    <div class="equipment-review-form">
                      <form action="{{ route('equipment_details.store_review', ['id' => $details->id]) }}" method="POST">
                        @csrf
                        <div class="form_group">
                          <label>{{ __('Comment') }}</label>
                          <textarea class="form_control" name="comment">{{ old('comment') }}</textarea>
                        </div>

                        <div class="form_group">
                          <label>{{ __('Rating') . '*' }}</label>
                          <ul class="rating mb-20">
                            <li class="review-value review-1">
                              <span class="fas fa-star" data-ratingVal="1"></span>
                            </li>

                            <li class="review-value review-2">
                              <span class="fas fa-star" data-ratingVal="2"></span>
                              <span class="fas fa-star" data-ratingVal="2"></span>
                            </li>

                            <li class="review-value review-3">
                              <span class="fas fa-star" data-ratingVal="3"></span>
                              <span class="fas fa-star" data-ratingVal="3"></span>
                              <span class="fas fa-star" data-ratingVal="3"></span>
                            </li>

                            <li class="review-value review-4">
                              <span class="fas fa-star" data-ratingVal="4"></span>
                              <span class="fas fa-star" data-ratingVal="4"></span>
                              <span class="fas fa-star" data-ratingVal="4"></span>
                              <span class="fas fa-star" data-ratingVal="4"></span>
                            </li>

                            <li class="review-value review-5">
                              <span class="fas fa-star" data-ratingVal="5"></span>
                              <span class="fas fa-star" data-ratingVal="5"></span>
                              <span class="fas fa-star" data-ratingVal="5"></span>
                              <span class="fas fa-star" data-ratingVal="5"></span>
                              <span class="fas fa-star" data-ratingVal="5"></span>
                            </li>
                          </ul>
                        </div>

                        <input type="hidden" id="rating-id" name="rating">

                        <div class="form_group">
                          <button type="submit" class="main-btn">
                            {{ __('Submit') }}
                          </button>
                        </div>
                      </form>
                    </div>
                  @endauth
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-70">
            {!! showAd(3) !!}
          </div>
        </div>

       
      </div>
    </div>
  </section>
  <!--====== End Equipment Details Section ======-->

  <!-- Request Price Modal -->
  <div class="modal fade" id="requestPriceModal" tabindex="-1" role="dialog"
    aria-labelledby="requestPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="requestPriceModalLabel">{{ __('Request Equipment Price') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <textarea class="form-control mt-3" id="message-text" rows="7"
              placeholder="{{ __('Write Your Message Here') }}"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="modal-submit-btn">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>
  
  <!--code by AG start-->
<!-- The Modal -->
<div class="modal" id="helly-terms-popup">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Terms & Conditions</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><span style="display: inline; font-size: 18px;"><strong style="font-weight: bolder;">Helly.co - Terms and Conditions</strong></span></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">Please carefully read the following terms and conditions before using the Helly.co website ("Website"). By accessing or using this Website, you agree to comply with and be bound by these terms and conditions. If you do not agree with any part of these terms, please refrain from using the Website.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">1. Definitions</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;"Website" refers to Helly.co, operated by Helly.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">"Helly" refers to the company operating the Website.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">"Vendors" refers to individuals or companies who register and list their equipment for rent on Helly.co.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">"Customers" refers to individuals or companies who sign up and book equipment from vendors on Helly.co.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">"Equipment" refers to any tools, machinery, or items listed for rent on Helly.co.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">2. Registration and User Accounts</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;2.1. Vendors and customers must provide accurate and complete information when registering on Helly.co. This includes personal or business information, contact details, and any other requested information.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;2.2. Users are responsible for maintaining the confidentiality of their account credentials and ensuring the security of their accounts. Any activity occurring under a user's account is their responsibility.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">3. Equipment Listings</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;3.1. Vendors are responsible for creating accurate and detailed equipment listings on Helly.co, including equipment specifications, availability, pricing, and contact information.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;3.2. Vendors must regularly update their equipment listings to reflect the most current availability and condition of their equipment.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">4. Booking and Rental</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;4.1. Customers can browse equipment listings on Helly.co and submit booking requests. Booking requests are subject to approval by the vendor.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;4.2. Once a booking request is approved by the vendor, a rental agreement is established between the customer and the vendor. Helly is not a party to this agreement but facilitates the transaction.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">5. Payment and Fees</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">5.1. Customers agree to pay the rental fees and any additional charges as specified by the vendor during the booking process.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;5.2. Helly may charge service fees for using the Website, which will be clearly communicated to customers during the booking process.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">6. Delivery and Return of Equipment</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">6.1. Vendors are responsible for delivering equipment to the location specified by the customer in the booking.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;6.2. Customers are responsible for the safe and timely return of the equipment to the vendor in the condition it was received, subject to normal wear and tear.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">7. Cancellations and Refunds</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">7.1. Cancellation policies and refund procedures are determined by each vendor and communicated to customers during the booking process.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">8. Privacy and Data Security</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">8.1. Helly collects and processes personal data in accordance with its Privacy Policy, which can be found on the Website.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">9. Liability and Disputes</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">9.1. Helly is not responsible for the quality, condition, or safety of the equipment listed on Helly.co. Disputes related to equipment quality or rental agreements should be resolved directly between the customer and the vendor.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">10. Termination</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">10.1. Helly reserves the right to terminate or suspend the accounts of users who violate these terms and conditions or engage in fraudulent activities.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);"><strong style="font-weight: bolder;"><span style="display: inline; font-size: 18px;">11. Changes to Terms and Conditions</span></strong></p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">11.1. Helly may update these terms and conditions at any time. Users will be notified of any changes, and continued use of Helly.co constitutes acceptance of the updated terms.</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p>
<p style="color: rgb(0, 0, 0); font-family: Montserrat, sans-serif; font-size: 16px; text-align: left; white-space: normal; background-color: rgb(255, 255, 255);">By using Helly.co, you acknowledge that you have read, understood, and agreed to these terms and conditions.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="card_list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Cards List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
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
                
                <tbody>
                    @foreach($user_cards as $user_card)
                        <tr>
                            <td>{{ $user_card->first_name }}</td>
                            <td>{{ $user_card->last_name }}</td>
                            <td>{{ $user_card->card_number }}</td>
                            <td>{{ $user_card->cvv }}</td>
                            <td>{{ $user_card->exp_month }}</td>
                            <td>{{ $user_card->exp_year }}</td>
                            <td>
                                <input hidden type="radio" {{ $user_card->is_default == '1' ? 'checked' : '' }} name="card_data" data-name="{{ $user_card->first_name.' '.$user_card->last_name }}" class="select_card" value="{{ $user_card->id }}" id="card_{{ $user_card->id }}">
                                <label for="card_{{ $user_card->id }}" class="text-white btn {{ $user_card->is_default == '1' ? 'btn-success' : 'btn-primary' }}">
                                    {{ $user_card->is_default == '1' ? 'Selected' : 'Select' }}
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submitForm()">Continue Process</button>
      </div>
    </div>
  </div>
</div>
    <!--code by AG end-->
  {{-- equipment search form start --}}
  <form class="d-none" action="{{ route('all_equipment') }}" method="GET">
    <input type="hidden" id="category-id" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- equipment search form end --}}
@endsection

@section('script')
  <script>
    'use strict';
    
function submitForm() {
        var form = document.getElementById("equipment-booking-form");
        form.submit();
    }

$(document).ready(function() {

    $(document).on('change', '.select_card', function() {
        const id = $(this).val();
        if ($(this).prop('checked')) {
            $('#card_id').val(id);
            $('#card_name').val($(this).data('name'));
            $('.select_card').not(this).prop('checked', false).parent().find('label').removeClass('btn-success').addClass('btn-primary').text('Select');
            $(this).parent().find('label').removeClass('btn-primary').addClass('btn-success').text('Selected');
        } else {
            $(this).parent().find('label').removeClass('btn-success').addClass('btn-primary').text('Select');
        }
    });
});



    let minBookingDays = {{ $details->min_booking_days }};
    let maxBookingDays = {{ $details->max_booking_days }};
    let equipmentId = {{ $details->id }};
    const security_deposit_amount =
      {{ $details->security_deposit_amount > 1 ? $details->security_deposit_amount : 0 }};
    const tax = {{ $basicData['equipment_tax_amount'] }};
    const twoWayDeliveryStatus = {{ $basicData['two_way_delivery_status'] }};
    let dateArray = {!! json_encode($bookedDates) !!};
    const numDayStr = "{{ __('Number of Days') }}";
    const maxDayStr = "{{ __('Maximum booking day is') }}";
    const minDayStr = "{{ __('Minimum booking day is') }}";
    
    
    var searchInput = 'location_field';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('location_lat').value = near_place.geometry.location.lat();
            document.getElementById('location_long').value = near_place.geometry.location.lng();
            
            $('#location_field').change();
        });
    });

    // $(document).on('change', '#'+searchInput, function () {
    //     document.getElementById('location_lat').value = '';
    //     document.getElementById('location_long').value = '';
    // });
    
    function getLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
          } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
          }
        }
        
    function showPosition(position) {
      $('#location_lat').val(position.coords.latitude);
      $('#location_long').val(position.coords.longitude);
      location.latitude=position.coords.latitude;
        location.longitude=position.coords.longitude;
        
        var geocoder = new google.maps.Geocoder();
        var latLng = new google.maps.LatLng(location.latitude, location.longitude);
    
     if (geocoder) {
        geocoder.geocode({ 'latLng': latLng}, function (results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
             console.log(results[0].formatted_address); 
             $('#location_field').val(results[0].formatted_address);
           }
           else {
           // $('#address').html('Geocoding failed: '+status);
            console.log("Geocoding failed: " + status);
           }
        }); //geocoder.geocode()
      } 
    }
  </script>

  <script src="{{ asset('assets/js/stripe.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/equipment.js?v=7') }}"></script>
@endsection
