@extends('vendors.layout')

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
    <h4 class="page-title">{{ __('Booking Details') }}</h4>
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
        <a href="#">{{ __('Equipment Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Bookings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Booking Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @php
      $position = $details->currency_symbol_position;
      $currency = $details->currency_symbol;
    @endphp

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Booking No.') . ' ' . '#' . $details->booking_number }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Booking Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">{{ date_format($details->created_at, 'M d, Y') }}</div>
            </div>

            @if (is_null($details->total))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Price') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">{{ __('Negotiable') }}</div>
              </div>
            @else
              <div class="row mb-2" style=" background: #d7d7d7; padding: 18px 0px; border-radius: 10px; ">
                <div class="col-lg-6">
                  <strong>{{ __('Price') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->total, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  
                </div>
                
                <div class="col-lg-12">
                  @php $additional_charges_line_items = json_decode($details->additional_charges_line_items, true); @endphp
                  
                  @if(!empty($additional_charges_line_items))
                    <ul class="price_summary_ag">
                        <li><b>INCLUDING</b></li>
                    @foreach($additional_charges_line_items as $item)
                        <li><b>{{ $item['name'] }} : </b>
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($item['amount'], 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}</li>
                    @endforeach
                    
                    </ul>
                  @endif
                </div>
              </div>
              
              
            @endif

            @if (!is_null($details->discount))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Discount') }} <span class="text-success">(<i class="far fa-minus"></i>)</span>
                    :</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->discount, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </div>
              </div>
            @endif

            @if (!is_null($details->total) && !is_null($details->discount))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Subtotal') . ' :' }}</strong>
                </div>

                @php
                  $total = floatval($details->total);
                  $discount = floatval($details->discount);
                  $subtotal = $total - $discount;
                @endphp

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($subtotal, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </div>
              </div>
            @endif

            @if (!is_null($details->shipping_cost))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Shipping Cost') }} <span class="text-danger">(<i class="far fa-plus"></i>)</span>
                    :</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->shipping_cost, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </div>
              </div>
            @endif

            @if (!is_null($details->tax))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Tax') }} {{ '(' . $tax->equipment_tax_amount . '%)' }} <span
                      class="text-danger">(<i class="far fa-plus"></i>)</span> :</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->tax, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  ({{ __('Received by Admin') }})
                </div>
              </div>
            @endif

            @if ($details->security_deposit_amount > 1)
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Security Deposit Amount') }} <span class="text-danger">(<i
                        class="far fa-plus"></i>)</span> : </strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->security_deposit_amount, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  ({{ __('Received by Admin but Refundable') }})
                </div>
              </div>
            @endif

            @if (!is_null($details->grand_total))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Customer Paid') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->grand_total, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </div>
              </div>
            @endif

            @if (!is_null($details->received_amount))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Commision') }} ({{ $details->commission_percentage }}%) : </strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->comission, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  ({{ __('Received by Admin') }})
                </div>
              </div>
            @endif

            @if (!is_null($details->received_amount))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Received Amount') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->received_amount, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </div>
              </div>
            @endif

            @if (!is_null($details->payment_method))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Paid via') . ' :' }}</strong>
                </div>

                <div class="col-lg-6">{{ $details->payment_method }}</div>
              </div>
            @endif

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Payment Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                @if ($details->payment_status == 'completed')
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                @elseif ($details->payment_status == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @else
                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @endif
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-6">
                <strong>{{ __('Shipping Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                  
                  {{ $details->shipping_status }}
                  
                  <!--commented by AG start-->
                <!--<form id="shippingStatusForm-{{ $details->id }}" class="d-inline-block"-->
                <!--  action="{{ route('vendor.equipment_booking.update_shipping_status', ['id' => $details->id]) }}"-->
                <!--  method="post">-->
                <!--  @csrf-->
                <!--  <select-->
                <!--    class="form-control form-control-sm @if ($details->shipping_status == 'pending') bg-warning text-dark @elseif ($details->shipping_status == 'delivered' || $details->shipping_status == 'taken') bg-primary @else bg-success @endif"-->
                <!--    name="shipping_status"-->
                <!--    onchange="document.getElementById('shippingStatusForm-{{ $details->id }}').submit()">-->
                <!--    <option value="pending" {{ $details->shipping_status == 'pending' ? 'selected' : '' }}>-->
                <!--      {{ __('Pending') }}-->
                <!--    </option>-->

                <!--    @if ($details->shipping_method == 'self pickup')-->
                <!--      <option value="taken" {{ $details->shipping_status == 'taken' ? 'selected' : '' }}>-->
                <!--        {{ __('Taken') }}-->
                <!--      </option>-->
                <!--    @else-->
                <!--      <option value="delivered" {{ $details->shipping_status == 'delivered' ? 'selected' : '' }}>-->
                <!--        {{ __('Delivered') }}-->
                <!--      </option>-->
                <!--    @endif-->
                <!--  </select>-->
                <!--</form>-->
                
                <!--commented by AG end-->
                
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-lg-6">
                <strong>{{ __('Return Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                @if ($details->return_status == 1)
                  <span class="badge badge-success">
                    {{ __('Yes') }}
                  </span>
                @else
                  @if ($details->security_deposit_amount > 0)
                    <form id="returnStatusForm-{{ $details->id }}" class="d-inline-block">
                      @csrf
                      <select
                        class="form-control form-control-sm @if ($details->return_status == 0) bg-danger  @else bg-success @endif returnStatus"
                        name="return_status" data-id="{{ $details->id }}"
                        data-security_deposit_amount="{{ $details->security_deposit_amount }}">
                        <option value="1" @selected($details->return_status == 1)>
                          {{ __('Yes') }}
                        </option>

                        <option value="0" @selected($details->return_status == 0)>
                          {{ __('No') }}
                        </option>
                      </select>
                    </form>
                  @else
                    <form id="returnStatusForm-main{{ $details->id }}" class="d-inline-block"
                      action="{{ route('vendor.equipment_booking.update_return_status', ['booking_id' => $details->id]) }}"
                      method="POST">
                      @csrf
                      <select
                        class="form-control form-control-sm  @if ($details->return_status == 0) bg-danger  @else bg-success @endif"
                        name="status"
                        onchange="document.getElementById('returnStatusForm-main{{ $details->id }}').submit()">
                        <option value="1" @selected($details->return_status == 1)>
                          {{ __('Yes') }}
                        </option>

                        <option value="0" @selected($details->return_status == 0)>
                          {{ __('No') }}
                        </option>
                      </select>
                    </form>
                  @endif
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Booking Information') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Equipment') . ' :' }}</strong>
              </div>

              @php
                $equipment = $details->equipmentInfo()->first();
                $equipmentTitle = $equipment
                    ->content()
                    ->where('language_id', $language->id)
                    ->select('title', 'slug')
                    ->first();
              @endphp

              <div class="col-lg-8"><a target="_blank"
                  href="{{ route('equipment_details', $equipmentTitle->slug) }}">{{ strlen($equipmentTitle->title) > 20 ? mb_substr($equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $equipmentTitle->title }}</a>
              </div>
            </div>

            @php
              $startDate = Carbon\Carbon::parse($details->start_date)->format('M d, Y');
              $endDate = Carbon\Carbon::parse($details->end_date)->format('M d, Y');
            @endphp

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Start Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $startDate }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('End Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $endDate }}</div>
            </div>

            @if (!is_null($details->shipping_method))
              <div class="row mb-2">
                <div class="col-lg-4">
                  <strong>{{ __('Shipping Type') . ' :' }}</strong>
                </div>

                <!--<div class="col-lg-8">{{ ucwords($details->shipping_method) }}</div>-->
                <div class="col-lg-8">Pickup & Drop off</div>
              </div>
            @endif

            <div class="row mb-1">
              <div class="col-lg-4">
                <strong>{{ __('Shipping Location') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->delivery_location }}</div>
            </div>
            
            <!--code by AG start-->
             @php $additional_booking_parameters = json_decode($details->additional_booking_parameters, true); @endphp
                      
              @if(!empty($additional_booking_parameters))
                
                @foreach($additional_booking_parameters as $item)
                    <div class="row mb-1">
                      <div class="col-lg-4">
                        <strong>{{ $item['name'] . ':' }}</strong>
                      </div>
        
                      <div class="col-lg-8">{{ $item['value'] }}</div>
                    </div>
                @endforeach
               
              @endif
            <!--code by AG end-->
            
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Billing Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->name }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Username') . ' :' }}</strong>
              </div>
              @php
                $user = $details->user()->first();
              @endphp
              @if ($user)
                <div class="col-lg-8">{{ $user->username }}</div>
              @else
                <div class="col-lg-8">{{ __('Guest') }}</div>
              @endif
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->email }}</div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-4">
                <strong>{{ __('Contact Number') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $details->contact_number }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!--code by AG start-->
    <style>
        .bar-progress .status-at{
            font-size: 10px;
            background: #0E2B5C;
            color: #fff;
            padding: 0px 10px;
            display: inline-flex;
            justify-content: center;
            line-height: 2;
            width: 100%;
        }
      .bar-progress {
            width: 100%;
            display: inline-flex;
            justify-content: center;
        }
        
        .bar-progress .step {
            display: inline-block;
                border: 1px solid #0E2B5C;
            padding: 5px 7px;
            border-radius: 10px;
                width: 100%;
        }
        
        .bar-progress .step .number-container {
            display: inline-block;
            border: solid 1px #0E2B5C;
            border-radius: 50%;
            width: 24px;
            height: 24px;
        }
        
        .bar-progress .step.step-active .number-container {
            background-color: #0E2B5C;
        }
        
        .bar-progress .step .number-container .number {
            font-weight: 700;
            font-size: .8em;
            line-height: 1.75em;
            display: block;
            text-align: center;
        }
        
        .bar-progress .step.step-active .number-container .number {
            color: white;
        }
        
        .bar-progress .step h5 {
            display: inline;
            font-weight: 100;
            font-size: .8em;
            margin-left: 10px;
            text-transform: uppercase;
        }
        
        .bar-progress .seperator {
            display: block;
            width: 20px;
            height: 1px;
            background-color: #0E2B5C;
            margin: auto 20px;
        }
  </style>
    <div class="col-md-12">
    <div class="mt-5"></div>
    <div class="title">
    <h4>Booking Status</h4>
    </div>
    
      <?php echo $status_timeline_html; ?>
    
    </div>
    <!--code by AG end-->

  </div>
  @includeIf('vendors.booking.return_modal')
@endsection
