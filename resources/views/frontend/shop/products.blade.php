@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->products_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_products }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_products }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->products_page_title : '',
  ])

  <!--====== Start Product Section ======-->
  <section class="products-area pt-130 pb-120">
    <div class="container">
      <div class="product-filter mb-20">
        <div class="row justify-content-between align-items-center">
          <div class="col-lg-3 col-md-4">
            <div class="form_group">
              <input type="text" placeholder="{{ __('Search Product') }}" class="form_control" id="input-search"
                value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">
              <i class="fas fa-search"></i>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="form_group">
              <select class="wide form_control" id="sort-search">
                <option selected disabled>{{ __('Sort By') }}</option>
                <option {{ request()->input('sort') == 'new' ? 'selected' : '' }} value="new">
                  {{ __('New Product') }}
                </option>
                <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
                  {{ __('Old Product') }}
                </option>
                <option {{ request()->input('sort') == 'ascending' ? 'selected' : '' }} value="ascending">
                  {{ __('Price') . ': ' . __('Ascending') }}
                </option>
                <option {{ request()->input('sort') == 'descending' ? 'selected' : '' }} value="descending">
                  {{ __('Price') . ': ' . __('Descending') }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3">
          <div class="sidebar-widget-area">
            <div class="widget product-categories mb-50">
              <h4 class="widget-title">{{ __('Categories') }}</h4>

              @if (count($categories) > 0)
                <ul class="widget-nav">
                  <li>
                    <a href="#" class="category-search {{ empty(request()->input('category')) ? 'active' : '' }}">
                      {{ __('All') }}
                    </a>
                  </li>

                  @foreach ($categories as $category)
                    <li>
                      <a href="#"
                        class="category-search {{ $category->slug == request()->input('category') ? 'active' : '' }}"
                        data-category_slug="{{ $category->slug }}">
                        {{ $category->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>

            <div class="widget filter-products mb-50">
              <h4 class="widget-title">{{ __('Filter Rating') }}</h4>
              <ul class="filter-products-list">
                <li>
                  <div class="single-method d-flex {{ empty(request()->input('rating')) ? 'active' : '' }}">
                    <input type="radio" id="all" class="rating-search" name="filter_rating" value=""
                      {{ empty(request()->input('rating')) ? 'checked' : '' }}>
                    <label for="all"><span>{{ __('Show All') }}</span></label>
                  </div>

                  <div class="single-method d-flex {{ request()->input('rating') == 5 ? 'active' : '' }}">
                    <input type="radio" id="five-star" class="rating-search" name="filter_rating" value="5"
                      {{ request()->input('rating') == 5 ? 'checked' : '' }}>
                    <label for="five-star"><span>{{ 5 . ' ' . __('Star') }}</span></label>
                  </div>

                  <div class="single-method d-flex {{ request()->input('rating') == 4 ? 'active' : '' }}">
                    <input type="radio" id="four-star" class="rating-search" name="filter_rating" value="4"
                      {{ request()->input('rating') == 4 ? 'checked' : '' }}>
                    <label for="four-star"><span>{{ 4 . ' ' . __('Star and higher') }}</span></label>
                  </div>

                  <div class="single-method d-flex {{ request()->input('rating') == 3 ? 'active' : '' }}">
                    <input type="radio" id="three-star" class="rating-search" name="filter_rating" value="3"
                      {{ request()->input('rating') == 3 ? 'checked' : '' }}>
                    <label for="three-star"><span>{{ 3 . ' ' . __('Star and higher') }}</span></label>
                  </div>

                  <div class="single-method d-flex {{ request()->input('rating') == 2 ? 'active' : '' }}">
                    <input type="radio" id="two-star" class="rating-search" name="filter_rating" value="2"
                      {{ request()->input('rating') == 2 ? 'checked' : '' }}>
                    <label for="two-star"><span>{{ 2 . ' ' . __('Star and higher') }}</span></label>
                  </div>

                  <div class="single-method d-flex {{ request()->input('rating') == 1 ? 'active' : '' }}">
                    <input type="radio" id="one-star" class="rating-search" name="filter_rating" value="1"
                      {{ request()->input('rating') == 1 ? 'checked' : '' }}>
                    <label for="one-star"><span>{{ 1 . ' ' . __('Star and higher') }}</span></label>
                  </div>
                </li>
              </ul>
            </div>

            <div class="widget price-range-widget mb-50">
              <h4 class="widget-title">{{ __('Filter Price') }}</h4>
              <div id="range-slider" class="mb-20"></div>
              <div class="price-number d-flex">
                <span class="text">{{ __('Price') . ' :' }}</span>
                <span class="amount"><input type="text" id="amount" readonly></span>
              </div>
            </div>

            <div class="product-advertise mb-50">
              {!! showAd(1) !!}
            </div>
          </div>
        </div>

        <div class="col-lg-9">
          <div class="product-item-area">
            @php
              $position = $currencyInfo->base_currency_symbol_position;
              $symbol = $currencyInfo->base_currency_symbol;
            @endphp

            @if (count($products) == 0)
              <div class="row text-center mt-5">
                <div class="col">
                  <h4>{{ __('No Product Found') . '!' }}</h4>
                </div>
              </div>
            @else
              <div class="row">
                @foreach ($products as $product)
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-item product-item-two mb-40">
                      <div class="product-img">
                        <img data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                          alt="product image" class="lazy">
                        <div class="product-overlay">
                          <div class="product-meta">
                            <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}" class="icon">
                              <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('shop.product.add_to_cart', ['id' => $product->id, 'quantity' => 1]) }}"
                              class="icon cart-btn add-to-cart-icon">
                              <i class="fas fa-shopping-cart"></i>
                            </a>
                          </div>
                        </div>
                      </div>

                      <div class="product-info text-center">
                        <div class="rate">
                          <div class="rating" style="width: {{ $product->average_rating * 20 . '%;' }}"></div>
                        </div>

                        <h3 class="title">
                          <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}">
                            {{ strlen($product->title) > 15 ? mb_substr($product->title, 0, 15, 'UTF-8') . '...' : $product->title }}
                          </a>
                        </h3>
                        <span
                          class="price">{{ $position == 'left' ? $symbol : '' }}{{ $product->current_price }}{{ $position == 'right' ? $symbol : '' }}
                          @if (!empty($product->previous_price))
                            <span
                              class="pre-price">{{ $position == 'left' ? $symbol : '' }}{{ $product->previous_price }}{{ $position == 'right' ? $symbol : '' }}</span>
                          @endif
                        </span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              {{ $products->appends([
                      'keyword' => request()->input('keyword'),
                      'category' => request()->input('category'),
                      'rating' => request()->input('rating'),
                      'min' => request()->input('min'),
                      'max' => request()->input('max'),
                      'sort' => request()->input('sort'),
                  ])->links() }}
            @endif

            <div class="mt-80 text-center">
              {!! showAd(3) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Product Section ======-->

  <form class="d-none" action="{{ route('shop.products') }}" method="GET">
    <input type="hidden" id="keyword-id" name="keyword"
      value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">

    <input type="hidden" id="category-id" name="category"
      value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

    <input type="hidden" id="rating-id" name="rating"
      value="{{ !empty(request()->input('rating')) ? request()->input('rating') : '' }}">

    <input type="hidden" id="min-id" name="min"
      value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

    <input type="hidden" id="max-id" name="max"
      value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

    <input type="hidden" id="sort-id" name="sort"
      value="{{ !empty(request()->input('sort')) ? request()->input('sort') : '' }}">

    <button type="submit" id="submitBtn"></button>
  </form>
@endsection

@section('script')
  <script>
    'use strict';
    let currency_info = {!! json_encode($currencyInfo) !!};
    let position = currency_info.base_currency_symbol_position;
    let symbol = currency_info.base_currency_symbol;
    let min_price = {{ $minPrice }};
    let max_price = {{ $maxPrice }};
    let curr_min = {{ !empty(request()->input('min')) ? request()->input('min') : $minPrice }};
    let curr_max = {{ !empty(request()->input('max')) ? request()->input('max') : $maxPrice }};
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/product.js') }}"></script>
@endsection
