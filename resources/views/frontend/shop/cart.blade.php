@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->cart_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_cart }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_cart }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->cart_page_title : '',
  ])

  <!--====== Start Cart Section ======-->
  <section class="cart-area-section pt-125 pb-130">
    <div class="container clearfix">
      @if (count($productCart) == 0)
        <div class="row text-center">
          <div class="col">
            <h3>{{ __('Cart is Empty') . '!' }}</h3>
          </div>
        </div>
      @else
        <div class="row">
          <div class="col-12">
            {{-- this below div will use to show message when cart is empty --}}
            <div id="cart-message"></div>

            @php
              $totalItems = count($productCart);
              
              $position = $currencyInfo->base_currency_symbol_position;
              $symbol = $currencyInfo->base_currency_symbol;
              
              $totalPrice = 0;
              
              foreach ($productCart as $key => $product) {
                  $totalPrice += $product['price'];
              }
              
              $totalPrice = number_format($totalPrice, 2, '.', '');
            @endphp

            <ul class="total-item-info">
              <li><strong>{{ __('Total Items') . ':' }}</strong> <strong class="cart-item-view"><span
                    id="total-item">{{ $totalItems }}</span></strong></li>
              <li><strong>{{ __('Cart Total') . ':' }}</strong> <strong class="cart-total-view"
                  dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                    id="cart-total">{{ $totalPrice }}</span>{{ $position == 'right' ? $symbol : '' }}</strong></li>
            </ul>

            <div class="table-outer table-responsive" id="cart-table">
              <table class="cart-table">
                <thead class="cart-header">
                  <tr>
                    <th class="prod-column">{{ __('Product') }}</th>
                    <th class="hide-column"></th>
                    <th>{{ __('Quantity') }}</th>
                    <th class="price">{{ __('Unit Price') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th class="remove">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($productCart as $key => $product)
                    <input type="hidden" class="product-id" id="{{ 'in-product-id' . $key }}"
                      value="{{ $key }}">

                    <tr id="{{ 'cart-product-item' . $key }}">
                      <td colspan="2" class="prod-column">
                        <div class="column-box">
                          <div class="title {{ $currentLanguageInfo->direction == 1 ? 'pr-3' : 'pl-3' }}">
                            @php $slug = $product['slug']; @endphp

                            <h3 class="prod-title">
                              <a href="{{ route('shop.product_details', ['slug' => $slug]) }}" target="_blank">
                                {{ $product['title'] }}
                              </a>
                            </h3>
                          </div>
                        </div>
                      </td>
                      <td class="qty">
                        <div class="quantity-input">
                          <div class="quantity-down sub-btn">
                            <i class="fal fa-minus"></i>
                          </div>

                          <input type="text" class="product-qty" id="product-quantity"
                            value="{{ $product['quantity'] }}" readonly>

                          <div class="quantity-up add-btn">
                            <i class="fal fa-plus"></i>
                          </div>
                        </div>
                      </td>

                      @php
                        $price = floatval($product['price']);
                        $quantity = floatval($product['quantity']);
                        $unitPrice = number_format($price / $quantity, 2, '.', '');
                        $perItemTotal = number_format($product['price'], 2, '.', '');
                      @endphp

                      <td class="price cart_price {{ $currentLanguageInfo->direction == 1 ? 'pr-4' : 'pl-4' }}">
                        <span dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                            class="product-unit-price">{{ $unitPrice }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                      </td>

                      <td class="sub-total {{ $currentLanguageInfo->direction == 1 ? 'pr-3' : 'pl-3' }}">
                        <span dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                            class="per-product-total">{{ $perItemTotal }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                      </td>
                      <td>
                        <div class="remove">
                          <div class="checkbox">
                            <a href="{{ route('shop.cart.remove_product', ['id' => $key]) }}" class="remove-product-icon"
                              data-product_id="{{ $key }}"><i class="fas fa-times"></i></a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="row cart-middle" id="cart-buttons">
          <div class="col-lg-12">
            <div class="update-cart">
              <a href="{{ route('shop.update_cart') }}" class="cart-btn"
                id="update-cart-btn"><span>{{ __('Update Cart') }}</span></a>
              <a href="{{ route('shop.checkout') }}" class="cart-btn"><span>{{ __('Checkout') }}</span></a>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>
  <!--====== End Cart Section ======-->
@endsection

@section('script')
  <script>
    'use strict';
    let cartEmptyTxt = "{{ __('Cart is Empty') . '!' }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/product.js') }}"></script>
@endsection
