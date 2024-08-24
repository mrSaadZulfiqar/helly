@extends('frontend.layout')

@section('pageHeading')
{{ __('Order Details') }}
@endsection

@section('content')

<!--====== Start Dashboard Section ======-->
<section class="user-dashboard ">
  <div class="container-fluid">

    <div style="min-height: 100vh" class="row">
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
                            <h3>{{ __('Order') . ': #' . $details->order_number }}</h3>
                            <p>{{ __('Order Date') . ': ' . date_format($details->created_at, 'M d, Y') }}</p>
                            <p>
                              {{ __('Order Status') . ':' }}
                              @if ($details->order_status == 'pending')
                              <span class="badge badge-warning px-2 py-1">{{ __('Pending') }}</span>
                              @elseif ($details->order_status == 'processing')
                              <span class="badge badge-primary px-2 py-1">{{ __('Processing') }}</span>
                              @elseif ($details->order_status == 'completed')
                              <span class="badge badge-success px-2 py-1">{{ __('Completed') }}</span>
                              @else
                              <span class="badge badge-danger px-2 py-1">{{ __('Rejected') }}</span>
                              @endif
                            </p>
                          </div>
                        </div>

                        @if (!is_null($details->invoice))
                        <div class="col-lg-4">
                          <div class="download">
                            <a href="{{ asset('assets/file/invoices/product/' . $details->invoice) }}" download
                              class="btn">{{ __('Invoice') }}</a>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="billing-add-area">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Billing Details') }}</h5>
                          <ul class="list">
                            <li>
                              <p><span>{{ __('Name') . ':' }}</span>{{ $details->billing_first_name . ' ' .
                                $details->billing_last_name }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Email') . ':' }}</span>{{ $details->billing_email }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Phone') . ':' }}</span>{{ $details->billing_contact_number }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Address') . ':' }}</span>{{ $details->billing_address }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('City') . ':' }}</span>{{ $details->billing_city }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('State') . ':' }}</span>{{ is_null($details->billing_state) ? '-' :
                                $details->billing_state }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Country') . ':' }}</span>{{ $details->billing_country }}</p>
                            </li>
                          </ul>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Shipping Details') }}</h5>
                          <ul class="list">
                            <li>
                              <p><span>{{ __('Name') . ':' }}</span>{{ $details->shipping_first_name . ' ' .
                                $details->shipping_last_name }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Email') . ':' }}</span>{{ $details->shipping_email }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Phone') . ':' }}</span>{{ $details->shipping_contact_number }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Address') . ':' }}</span>{{ $details->shipping_address }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('City') . ':' }}</span>{{ $details->shipping_city }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('State') . ':' }}</span>{{ is_null($details->shipping_state) ? '-' :
                                $details->shipping_state }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Country') . ':' }}</span>{{ $details->shipping_country }}</p>
                            </li>
                          </ul>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Payment Information') }}</h5>

                          @php
                          $position = $details->currency_symbol_position;
                          $symbol = $details->currency_symbol;
                          $subtotal = floatval($details->total) - floatval($details->discount);
                          @endphp

                          <ul class="list">
                            <li>
                              <p><span>{{ __('Price') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($details->total, 2) }}{{ $position == 'right' ? $symbol : '' }}</p>
                            </li>
                            <li>
                              <p><span class="text-success">{{ __('Discount') }} (<i
                                    class="far fa-minus"></i>):</span>{{
                                $position == 'left' ? $symbol : '' }}{{ $details->discount }}{{ $position == 'right' ?
                                $symbol : '' }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Subtotal') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($subtotal, 2) }}{{ $position == 'right' ? $symbol : '' }}</p>
                            </li>

                            @if (!is_null($details->shipping_cost))
                            <li>
                              <p><span class="text-danger">{{ __('Shipping Cost') }} (<i
                                    class="far fa-plus"></i>):</span>{{ $position == 'left' ? $symbol : '' }}{{
                                $details->shipping_cost }}{{ $position == 'right' ? $symbol : '' }}</p>
                            </li>
                            @endif

                            <li>
                              <p><span class="text-danger">{{ __('Tax') . ' (' . $tax->product_tax_amount . '%)' }} (<i
                                    class="far fa-plus"></i>):</span>{{ $position == 'left' ? $symbol : '' }}{{
                                $details->tax }}{{ $position == 'right' ? $symbol : '' }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Grand Total') . ':' }}</span>{{ $position == 'left' ? $symbol : '' }}{{
                                number_format($details->grand_total, 2) }}{{ $position == 'right' ? $symbol : '' }}</p>
                            </li>
                            <li>
                              <p><span>{{ __('Paid via') . ':' }}</span>{{ $details->payment_method }}</p>
                            </li>
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
                    </div>
                  </div>

                  <div class="table-responsive product-list">
                    <h5>{{ __('Ordered Products') }}</h5>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>{{ __('Product') }}</th>
                          <th>{{ __('Quantity') }}</th>
                          <th>{{ __('Unit Price') }}</th>
                          <th>{{ __('Total Price') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($items as $item)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            <a href="{{ route('shop.product_details', ['slug' => $item->slug]) }}" target="_blank">
                              {{ $item->productTitle }}
                            </a>

                            @if (($item->productType == 'digital') && ($details->payment_status == 'completed'))
                            @if ($item->inputType == 'link')
                            <div class="mt-1">
                              <a href="{{ $item->link }}" target="_blank" class="btn btn-primary btn-sm">
                                {{ __('Download') }}
                              </a>
                            </div>
                            @else
                            <form
                              action="{{ route('user.product_order.product.download', ['id' => $item->product_id]) }}"
                              method="POST">
                              @csrf
                              <button type="submit" class="btn btn-primary btn-sm mt-1">
                                {{ __('Download') }}
                              </button>
                            </form>
                            @endif
                            @endif
                          </td>
                          <td>{{ $item->quantity }}</td>
                          <td>
                            {{ $position == 'left' ? $symbol : '' }}{{ $item->price }}{{ $position == 'right' ? $symbol
                            :
                            '' }}
                          </td>
                          <td>
                            @php
                            $eachItemTotal = floatval($item->price) * $item->quantity;
                            @endphp

                            {{ $position == 'left' ? $symbol : '' }}{{ number_format($eachItemTotal, 2) }}{{ $position
                            ==
                            'right' ? $symbol : '' }}
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
</section>
<!--====== End Dashboard Section ======-->
@endsection