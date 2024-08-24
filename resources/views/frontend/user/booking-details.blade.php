@extends('frontend.layout')

@section('pageHeading')
{{ __('Booking Details') }}
@endsection

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
<!--====== Start Dashboard Section ======-->
<style>
  .bar-progress .status-at {
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
<section class="user-dashboard">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div style="padding-block: 20px">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="order-details">
                  <div class="title">
                    <h4>{{ __('Details') }}</h4>
                  </div>

                  <div class="view-order-page">
                    <div class="order-info-area">
                      <div class="row align-items-center">
                        <div class="col-lg-8">
                          <div class="order-info">
                            <h3>{{ __('Booking') . ': #' . $details->booking_number }}</h3>
                            <p>{{ __('Booking Date') . ': ' . date_format($details->created_at, 'M d, Y') }}</p>
                          </div>
                        </div>

                        @if (!is_null($details->invoice))
                        <div class="col-lg-4">
                          <div class="download">
                            <a href="{{ asset('assets/file/invoices/equipment/' . $details->invoice) }}" download
                              class="btn">{{ __('Invoice') }}</a>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="billing-add-area">
                    <div class="row">
                      @php
                      $equipment = $details->equipmentInfo()->first();
                      $content = $equipment
                      ->content()
                      ->where('language_id', $language->id)
                      ->first();
                      $equipmentTitle = $content->title;
                      $equipmentSlug = $content->slug;

                      $startDate = Carbon\Carbon::parse($details->start_date)->format('M d, Y');
                      $endDate = Carbon\Carbon::parse($details->end_date)->format('M d, Y');
                      @endphp

                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Booking Information') }}</h5>
                          <ul class="list">
                            @if ($equipment->vendor)
                            <li>
                              <p><span>{{ __('Vendor') . ':' }}</span><a class="text-primary" target="_blank"
                                  href="{{ route('frontend.vendor.details', $equipment->vendor->username) }}">{{ $vendor
                                  =
                                  optional($equipment->vendor)->username }}</a>
                              </p>
                            </li>
                            @else
                            <li>
                              <p><span>{{ __('Vendor') . ':' }} {{ __('Admin') }}</span>
                              </p>
                            </li>
                            @endif
                            <li>
                              <p><span>{{ __('Equipment') . ':' }}</span><a class="text-primary" target="_blank"
                                  href="{{ route('equipment_details', ['slug' => $equipmentSlug]) }}">{{ $equipmentTitle
                                  }}</a>
                              </p>
                            </li>



                            @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')
                            @php
                            if(auth()->user()->owner_id){
                            $company = \App\Models\Company::where('customer_id',auth()->user()->owner_id)->first();
                            $branch = \App\Models\CompanyBranch::find($details->branch_id);
                            }else if(auth()->user()->account_type == 'corperate_account'){
                            $company = \App\Models\Company::where('customer_id',auth()->user()->id)->first();
                            $branch = \App\Models\CompanyBranch::find($details->branch_id);
                            }
                            @endphp
                            <li>
                              <p><span>{{ __('Company Name') . ':' }}</span>{{ $company->name }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Branch Name') . ':' }}</span>{{ $branch->name }}</p>
                            </li>
                            @endif
                            <li>
                              <p><span>{{ __('Start Date') . ':' }}</span>{{ $startDate }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('End Date') . ':' }}</span>{{ $endDate }}</p>
                            </li>

                            @if (!is_null($details->shipping_method))
                            <li>
                              <p><span>{{ __('Shipping Type') . ':' }}</span>Pickup & Drop off
                                <!--{{ ucwords($details->shipping_method) }}-->
                              </p>
                            </li>
                            @endif

                            <li>
                              <p><span>{{ __('Location') . ':' }}</span>{{ $details->delivery_location }}</p>
                            </li>


                            <!--code by AG start-->
                            @php $additional_booking_parameters = json_decode($details->additional_booking_parameters,
                            true); @endphp

                            @if(!empty($additional_booking_parameters))

                            @foreach($additional_booking_parameters as $item)
                            <li>
                              <p><span>{{ $item['name'] . ':' }}</span>{{ $item['value'] }}</p>
                            </li>

                            @endforeach

                            @endif
                            <!--code by AG end-->

                            <!--<li>-->
                            <!--  <p>-->
                            <!--    <span>{{ __('Shipping Status') . ':' }}</span>-->
                            <!--    @if ($details->shipping_status == 'pending')-->
                            <!--      <span class="badge badge-warning px-2 py-1">{{ __('Pending') }}</span>-->
                            <!--    @elseif ($details->shipping_status == 'delivered' || $details->shipping_status == 'taken')-->
                            <!--      <span-->
                            <!--        class="badge badge-primary px-2 py-1">{{ ucwords($details->shipping_status) }}</span>-->
                            <!--    @else-->
                            <!--      <span class="badge badge-success px-2 py-1">{{ __('Returned') }}</span>-->
                            <!--    @endif-->
                            <!--  </p>-->



                            <!--</li>-->
                          </ul>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Billing Details') }}</h5>
                          <ul class="list">
                            <li>
                              <p><span>{{ __('Name') . ':' }}</span>{{ $details->name }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Email') . ':' }}</span>{{ $details->email }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Phone') . ':' }}</span>{{ $details->contact_number }}</p>
                            </li>
                          </ul>
                        </div>
                      </div>

                      @php
                      $position = $details->currency_symbol_position;
                      $symbol = $details->currency_symbol;
                      $subtotal = floatval($details->total) - floatval($details->discount);
                      @endphp

                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Payment Information') }}</h5>
                          <ul class="list">
                            @if (!is_null($details->total))
                            <li style=" background: #d7d7d7; padding: 18px 18px; border-radius: 10px; ">
                              <p>
                                <span>{{ __('Price') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($details->total, 2) }}{{ $position == 'right' ? $symbol : '' }}
                              </p>

                              <div class="row">
                                <div class="col-lg-12">
                                  @php $additional_charges_line_items =
                                  json_decode($details->additional_charges_line_items, true); @endphp

                                  @if(!empty($additional_charges_line_items))
                                  <ul class="price_summary_ag">
                                    <li><b>INCLUDING</b></li>
                                    @foreach($additional_charges_line_items as $item)
                                    <li><b>{{ $item['name'] }} : </b>
                                      {{ $position == 'left' ? $symbol . ' ' : '' }}{{ number_format($item['amount'], 2)
                                      }}{{ $position == 'right' ? ' ' . $symbol : '' }}</li>
                                    @endforeach

                                  </ul>
                                  @endif
                                </div>
                              </div>
                            </li>
                            @endif

                            @if (!is_null($details->discount))
                            <li>
                              <p><span class="text-success">{{ __('Discount') }} (<i
                                    class="far fa-minus"></i>):</span>{{
                                $position == 'left' ? $symbol : '' }}{{ $details->discount }}{{ $position == 'right' ?
                                $symbol : '' }}
                              </p>
                            </li>
                            @endif

                            @if (!is_null($details->total) && !is_null($details->discount))
                            <li>
                              <p>
                                <span>{{ __('Subtotal') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($subtotal, 2) }}{{ $position == 'right' ? $symbol : '' }}
                              </p>
                            </li>
                            @endif

                            @if (!is_null($details->shipping_cost))
                            <li>
                              <p><span class="text-danger">{{ __('Shipping Cost') }} (<i
                                    class="far fa-plus"></i>):</span>{{ $position == 'left' ? $symbol : '' }}{{
                                $details->shipping_cost }}{{ $position == 'right' ? $symbol : '' }}
                              </p>
                            </li>
                            @endif

                            @if (!is_null($details->tax))
                            <li>
                              <p><span class="text-danger">{{ __('Tax') . ' (' . $tax->equipment_tax_amount . '%)' }}
                                  (<i class="far fa-plus"></i>):</span>{{ $position == 'left' ? $symbol : '' }}{{
                                $details->tax }}{{ $position == 'right' ? $symbol : '' }}
                              </p>
                            </li>
                            @endif

                            @if ($details->security_deposit_amount > 0)
                            <li>
                              <p><span class="text-danger">{{ __('Security Deposit Amount') }}
                                  (<i class="far fa-plus"></i>):</span>{{ $position == 'left' ? $symbol : '' }}{{
                                $details->security_deposit_amount }}{{ $position == 'right' ? $symbol : '' }}
                              </p>
                              <span class="text-warning lh-normal">
                                <small>{{ __('Once the equipment is returned safely , this amount will be refunded')
                                  }}</small>
                              </span>
                            </li>
                            @endif

                            @if (!is_null($details->grand_total))
                            <li>
                              <p>
                                <span>{{ __('Grand Total') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($details->grand_total, 2) }}{{ $position == 'right' ? $symbol : '' }}
                              </p>
                            </li>
                            @endif

                            @if (is_null($details->payment_method))
                            <li>
                              <p><span>{{ __('Paid via') . ':' }}</span>{{ __('Negotiated') }}</p>
                            </li>
                            @else
                            <li>
                              <p><span>{{ __('Paid via') . ':' }}</span>{{ __($details->payment_method) }}</p>
                            </li>
                            @endif

                            <li>
                              <p>
                                <span>{{ __('Payment Status') . ':' }}</span>
                                @if ($details->payment_status == 'completed')
                                <span class="badge badge-success px-2 py-1">{{ __('Completed') }}</span>
                                @elseif ($details->payment_status == 'pending')
                                <span class="badge badge-warning px-2 py-1">{{ __('Pending') }}</span>
                                @else
                                <span class="badge badge-danger px-2 py-1">{{ __('Rejected') }}</span>
                                @endif
                              </p>
                            </li>
                          </ul>
                        </div>
                      </div>


                      <!--code by AG start-->
                      <div class="col-md-12">
                        <div class="mt-5"></div>
                        <div class="title">
                          <h4>Booking Status</h4>
                        </div>

                        <?php echo $status_timeline_html; ?>

                      </div>
                      <!--code by AG end-->

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(!empty($additional_invoices))
          <div class="row">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <div class="user-profile-details">
                    <div class="account-info">
                      <div class="title">
                        <h4>{{ __('Additional Invoices') }}</h4>
                      </div>

                      <div class="main-info">
                        <div class="main-table">
                          <div class="table-responsive">
                            <table id="user-datatable"
                              class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                              <thead>
                                <tr>
                                  <th>{{ __('Date') }}</th>
                                  <th>{{ __('Amount') }}</th>
                                  <th>{{ __('Status') }}</th>
                                  <th>{{ __('Action') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($additional_invoices as $additional_invoice)
                                <tr>
                                  <td>{{ $additional_invoice->additional_day }}</td>
                                  <td>{{ $position == 'left' ? $symbol : '' }}{{
                                    number_format($additional_invoice->amount, 2) }}{{ $position == 'right' ? $symbol :
                                    ''
                                    }}</td>
                                  <td>{{ $additional_invoice->status }}</td>
                                  <td>
                                    @if($additional_invoice->status == 'pending')
                                    <a class="badge badge-primary px-2 py-1" href="#">
                                      <span class="btn-label">
                                        Pay
                                      </span>
                                    </a>
                                    @else

                                    @endif

                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>

      </div>
    </div>

  </div>
</section>
<!--====== End Dashboard Section ======-->
@endsection