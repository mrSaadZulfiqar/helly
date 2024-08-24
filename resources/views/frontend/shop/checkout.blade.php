@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->checkout_page_title }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->checkout_page_title : '',
  ])

  <!--====== Start Checkout Section ======-->
  <section class="checkout-area-section pt-130 pb-120">
    <form action="{{ route('shop.purchase_product') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="container">
        {{-- show error message for attachment (Offline) --}}
        @error('attachment')
          <div class="row mb-3">
            <div class="col">
              <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
              </div>
            </div>
          </div>
        @enderror

        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="form billing-info">
              <div class="shop-title-box">
                <h3>{{ __('Billing Details') }}</h3>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('First Name') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_first_name"
                      value="{{ !empty($authUser) ? $authUser->first_name : old('billing_first_name') }}">
                    @error('billing_first_name')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Last Name') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_last_name"
                      value="{{ !empty($authUser) ? $authUser->last_name : old('billing_last_name') }}">
                    @error('billing_last_name')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Email Address') . '*' }}</label>
                    <input type="email" class="form_control" name="billing_email"
                      value="{{ !empty($authUser) ? $authUser->email : old('billing_email') }}">
                    @error('billing_email')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Phone Number') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_contact_number"
                      value="{{ !empty($authUser) ? $authUser->contact_number : old('billing_contact_number') }}">
                    @error('billing_contact_number')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Address') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_address"
                      value="{{ !empty($authUser) ? $authUser->address : old('billing_address') }}">
                    @error('billing_address')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('City') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_city"
                      value="{{ !empty($authUser) ? $authUser->city : old('billing_city') }}">
                    @error('billing_city')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('State') }}</label>
                    <input type="text" class="form_control" name="billing_state"
                      value="{{ !empty($authUser) ? $authUser->state : old('billing_state') }}">
                    @error('billing_state')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form_group">
                    <label>{{ __('Country') . '*' }}</label>
                    <input type="text" class="form_control" name="billing_country"
                      value="{{ !empty($authUser) ? $authUser->country : old('billing_country') }}">
                    @error('billing_country')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="form shipping-info">
              <div class="shop-title-box d-flex flex-row">
                <h3 class="{{ $currentLanguageInfo->direction == 1 ? 'ml-4' : 'mr-4' }}">{{ __('Shipping Details') }}
                </h3>

                <div class="mt-2">
                  <input type="checkbox"
                    class="{{ $currentLanguageInfo->direction == 0 ? 'mr-1' : 'ml-1' }} d-inline-block"
                    id="shipping-check">
                  <label for="shipping-check">{{ __('Same as billing details') }}</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('First Name') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_first_name"
                      value="{{ old('shipping_first_name') }}">
                    @error('shipping_first_name')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Last Name') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_last_name"
                      value="{{ old('shipping_last_name') }}">
                    @error('shipping_last_name')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Email Address') . '*' }}</label>
                    <input type="email" class="form_control" name="shipping_email"
                      value="{{ old('shipping_email') }}">
                    @error('shipping_email')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Phone Number') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_contact_number"
                      value="{{ old('shipping_contact_number') }}">
                    @error('shipping_contact_number')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Address') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_address"
                      value="{{ old('shipping_address') }}">
                    @error('shipping_address')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('City') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_city"
                      value="{{ old('shipping_city') }}">
                    @error('shipping_city')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('State') }}</label>
                    <input type="text" class="form_control" name="shipping_state"
                      value="{{ old('shipping_state') }}">
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form_group">
                    <label>{{ __('Country') . '*' }}</label>
                    <input type="text" class="form_control" name="shipping_country"
                      value="{{ old('shipping_country') }}">
                    @error('shipping_country')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @php
        $position = $currencyInfo->base_currency_symbol_position;
        $symbol = $currencyInfo->base_currency_symbol;
      @endphp

      <div class="bottom">
        <div class="container">
          <div class="row">
            @if ($status == false && count($charges) > 0)
              <div class="col-12 mb-5">
                <div class="table table-one table-responsive">
                  <div class="shop-title-box">
                    <h3>{{ __('Shipping Methods') }}</h3>
                  </div>

                  <table class="cart-table shipping-method">
                    <thead class="cart-header">
                      <tr>
                        <th>#</th>
                        <th>{{ __('Method') }}</th>
                        <th class="price">{{ __('Cost') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($charges as $charge)
                        <tr>
                          <td>
                            <input type="radio" {{ $loop->first ? 'checked' : '' }} name="shipping_charge"
                              id="{{ 'shipping-charge-' . $charge->id }}" value="{{ $charge->id }}"
                              data-shipping_charge="{{ $charge->shipping_charge }}">
                          </td>
                          <td>
                            <p class="mb-2"><strong>{{ $charge->title }}</strong></p>
                            <p><small>{{ $charge->short_text }}</small></p>
                          </td>
                          <td>
                            {{ $position == 'left' ? $symbol : '' }}<span>{{ $charge->shipping_charge }}</span>{{ $position == 'right' ? $symbol : '' }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
              <div class="table table-responsive">
                <div class="shop-title-box">
                  <h3>{{ __('Order Summary') }}</h3>
                </div>

                <table class="cart-table">
                  <thead class="cart-header">
                    <tr>
                      <th class="product-column">{{ __('Product') }}</th>
                      <th>&nbsp;</th>
                      <th>{{ __('Quantity') }}</th>
                      <th class="price">{{ __('Total') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($productItems as $key => $item)
                      <tr>
                        <td colspan="2" class="product-column">
                          <div class="column-box">
                            <div class="product-title">
                              @php $slug = $item['slug']; @endphp

                              <h3 class="prod-title">
                                <a href="{{ route('shop.product_details', ['slug' => $slug]) }}" target="_blank">
                                  {{ strlen($item['title']) > 25 ? mb_substr($item['title'], 0, 25, 'UTF-8') . '...' : $item['title'] }}
                                </a>
                              </h3>
                            </div>
                          </div>
                        </td>
                        <td class="qty">
                          <input class="quantity-spinner" type="text" value="{{ $item['quantity'] }}" readonly>
                        </td>
                        <td class="price">
                          {{ $position == 'left' ? $symbol : '' }}{{ number_format($item['price'], 2, '.', ',') }}{{ $position == 'right' ? $symbol : '' }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
              <div class="cart-total">
                <div class="shop-title-box">
                  <h3>{{ __('Charge Summary') }}</h3>
                </div>

                @php
                  // calculate total price
                  $total = 0;
                  
                  foreach ($productItems as $key => $item) {
                      $total += $item['price'];
                  }
                  
                  // get the first shipping charge
                  $shippingCost = $charges->first();
                  
                  // calculate tax
                  $taxAmount = $tax->product_tax_amount;
                  $calculatedTax = $total * ($taxAmount / 100);
                  
                  // calculate grand total
                  $grandTotal = $total + $shippingCost->shipping_charge + $calculatedTax;
                @endphp

                <div>
                  <ul class="cart-total-table">
                    <li class="clearfix">
                      <span class="col col-title">{{ __('Cart Total') }}</span>
                      <span class="col" dir="ltr">
                        {{ $position == 'left' ? $symbol : '' }}<span
                          id="total-amount">{{ number_format($total, 2, '.', ',') }}</span>{{ $position == 'right' ? $symbol : '' }}
                      </span>
                    </li>

                    <li class="clearfix">
                      <span class="col col-title">{{ __('Discount') }}
                        <span class="text-success">(<i class="fas fa-minus"></i>)</span>
                      </span>
                      <span class="col" dir="ltr">
                        {{ $position == 'left' ? $symbol : '' }}<span
                          id="discount-amount">0.00</span>{{ $position == 'right' ? $symbol : '' }}
                      </span>
                    </li>

                    <li class="clearfix">
                      <span class="col col-title">{{ __('Subtotal') }}</span>
                      <span class="col" dir="ltr">
                        {{ $position == 'left' ? $symbol : '' }}<span
                          id="subtotal-amount">{{ number_format($total, 2, '.', ',') }}</span>{{ $position == 'right' ? $symbol : '' }}
                      </span>
                    </li>

                    @if ($status == false)
                      <li class="clearfix">
                        <span class="col col-title">{{ __('Shipping Charge') }}
                          <span class="text-danger">(<i class="fas fa-plus"></i>)</span>
                        </span>
                        <span class="col" dir="ltr">
                          {{ $position == 'left' ? $symbol : '' }}<span
                            id="shipping-charge-amount">{{ $shippingCost->shipping_charge }}</span>{{ $position == 'right' ? $symbol : '' }}
                        </span>
                      </li>

                      <input type="hidden" id="shipping-charge-id" name="charge_id" value="{{ $shippingCost->id }}">
                    @endif

                    <li class="clearfix">
                      <span class="col col-title">{{ __('Tax') }} <span
                          dir="ltr">{{ '(' . $tax->product_tax_amount . '%)' }}</span>
                        <span class="text-danger">(<i class="fas fa-plus"></i>)</span>
                      </span>
                      <span class="col" dir="ltr">
                        {{ $position == 'left' ? $symbol : '' }}<span
                          id="tax-amount">{{ number_format($calculatedTax, 2, '.', ',') }}</span>{{ $position == 'right' ? $symbol : '' }}
                      </span>
                    </li>

                    <li class="clearfix">
                      <span class="col col-title">{{ __('Grand Total') }}</span>
                      <span class="col" dir="ltr">
                        {{ $position == 'left' ? $symbol : '' }}<span
                          id="grandtotal-amount">{{ number_format($grandTotal, 2, '.', ',') }}</span>{{ $position == 'right' ? $symbol : '' }}
                      </span>
                    </li>
                  </ul>
                </div>

                <div class="coupon mt-4">
                  <h4 class="mb-3">{{ __('Coupon') }}</h4>
                  <div class="form-group d-flex">
                    <input type="text" class="form-control" id="coupon-code"
                      placeholder="{{ __('Enter Your Coupon') }}">
                    <button class="btn" onclick="applyCoupon(event)">{{ __('Apply') }}</button>
                  </div>
                </div>

                <div class="payment-options">
                  <h4>{{ __('Pay via') }}</h4>

                  <div class="order-payment-box">
                    @if (count($onlineGateways) > 0)
                      @foreach ($onlineGateways as $onlineGateway)
                        <div class="single-radio">
                          <input type="radio" class="single-input" id="{{ $onlineGateway->keyword }}"
                            name="gateway" value="{{ $onlineGateway->keyword }}">
                          <label class="single-input-label sigle-input-check"
                            for="{{ $onlineGateway->keyword }}"><span>{{ __($onlineGateway->name) }}</span></label>
                        </div>

                        @if ($onlineGateway->keyword == 'stripe')
                          <div id="stripe-form"
                            class="mb-30 @if ($errors->has('card_number') ||
                                $errors->has('cvc_number') ||
                                $errors->has('expiry_month') ||
                                $errors->has('expiry_year')) d-block @else d-none @endif">
                            <div class="row mb-3">
                              <div class="col-lg-6">
                                <div class="form_group">
                                  <label>{{ __('Card Number') . '*' }}</label>
                                  <input type="text" class="form_control" name="card_number" autocomplete="off"
                                    oninput="checkCard(this.value)">
                                  <p class="mt-2 text-danger" id="card-error"></p>

                                  @error('card_number')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                  @enderror
                                </div>
                              </div>

                              <div class="col-lg-6">
                                <div class="form_group">
                                  <label>{{ __('CVC Number') . '*' }}</label>
                                  <input type="text" class="form_control" name="cvc_number" autocomplete="off"
                                    oninput="checkCVC(this.value)">
                                  <p class="mt-2 text-danger" id="cvc-error"></p>

                                  @error('cvc_number')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                  @enderror
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-6">
                                <div class="form_group">
                                  <label>{{ __('Expiry Month') . '*' }}</label>
                                  <input type="text" class="form_control" name="expiry_month">

                                  @error('expiry_month')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                  @enderror
                                </div>
                              </div>

                              <div class="col-lg-6">
                                <div class="form_group">
                                  <label>{{ __('Expiry Year') . '*' }}</label>
                                  <input type="text" class="form_control" name="expiry_year">

                                  @error('expiry_year')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                  @enderror
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    @endif

                    @if (count($offlineGateways) > 0)
                      @foreach ($offlineGateways as $offlineGateway)
                        <div class="single-radio">
                          <input type="radio" class="single-input" id="{{ $offlineGateway->id }}" name="gateway"
                            value="{{ $offlineGateway->id }}">
                          <label class="single-input-label sigle-input-check"
                            for="{{ $offlineGateway->id }}"><span>{{ __($offlineGateway->name) }}</span></label>
                        </div>

                        @if ($offlineGateway->has_attachment == 1)
                          <div id="{{ 'gateway-attachment-' . $offlineGateway->id }}" class="mb-4 d-none">
                            <div class="row">
                              <div class="col">
                                <div class="form_group">
                                  <label>{{ __('Attachment') . '*' }}</label>
                                  <input type="file" name="attachment">
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif

                        @if (!is_null($offlineGateway->short_description))
                          <div id="{{ 'gateway-description-' . $offlineGateway->id }}" class="mb-3 d-none">
                            <div class="row">
                              <div class="col">
                                <div class="form_group">
                                  <label>{{ __('Description') }}</label>
                                  <p class="mt-2">{{ $offlineGateway->short_description }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif

                        @if (!is_null($offlineGateway->instructions))
                          <div id="{{ 'gateway-instructions-' . $offlineGateway->id }}" class="mb-4 d-none">
                            <div class="row">
                              <div class="col">
                                <div class="form_group">
                                  <label>{{ __('Instructions') }}</label>
                                  <p class="mt-2">{!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    @endif
                  </div>

                  <div class="placeorder-button">
                    <button type="submit" class="main-btn">{{ __('Place Order') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
  <!--====== End Checkout Section ======-->
@endsection

@section('script')
  <script>
    'use strict';
    const tax = {{ $tax->product_tax_amount }};
  </script>

  <script src="{{ asset('assets/js/stripe.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/product.js') }}"></script>
@endsection
