@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->product_details_page_title }}
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

  <!--====== Start Products details Section ======-->
  <section class="products-details-section pt-130 pb-120">
    <div class="container">
      <div class="products-details-wrapper mb-60">
        <div class="row">
          <div class="col-lg-6">
            <div class="products-gallery-wrap">
              @php $sliderImages = json_decode($details->slider_images); @endphp

              <div class="products-big-slider mb-20">
                @foreach ($sliderImages as $sliderImage)
                  <div class="product-img">
                    <a href="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}" class="img-popup">
                      <img data-src="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}" alt="product image"
                        class="lazy">
                    </a>
                  </div>
                @endforeach
              </div>

              <div class="products-thumb-slider">
                @foreach ($sliderImages as $sliderImage)
                  <div class="product-img">
                    <img data-src="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}" alt="product image"
                      class="lazy">
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="product-info">
              <h2>{{ $details->title }}</h2>

              @if (!empty($details->average_rating))
                <div class="rate">
                  <div class="rating" style="width: {{ $details->average_rating * 20 . '%;' }}"></div>
                </div>
              @endif

              @php
                $position = $currencyInfo->base_currency_symbol_position;
                $symbol = $currencyInfo->base_currency_symbol;
              @endphp

              <span
                class="price">{{ $position == 'left' ? $symbol : '' }}{{ $details->current_price }}{{ $position == 'right' ? $symbol : '' }}
                @if (!empty($details->previous_price))
                  <span
                    class="pre-price {{ $currentLanguageInfo->direction == 0 ? 'ml-3' : 'mr-3' }}">{{ $position == 'left' ? $symbol : '' }}{{ $details->previous_price }}{{ $position == 'right' ? $symbol : '' }}</span>
                @endif
              </span>
              <p>{{ $details->summary }}</p>
              <div class="button mb-20">
                <div class="quantity-input">
                  <div class="quantity-down sub-btn">
                    <i class="fal fa-minus"></i>
                  </div>

                  <input type="text" id="product-quantity" value="1" readonly>

                  <div class="quantity-up add-btn">
                    <i class="fal fa-plus"></i>
                  </div>
                </div>

                <a href="{{ route('shop.product.add_to_cart', ['id' => $details->id, 'quantity' => 'qty']) }}"
                  class="main-btn" id="add-to-cart-btn">
                  {{ __('Add To Cart') }}
                </a>
              </div>
              <ul class="social-link mb-20">
                <li><a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                      class="fab fa-facebook-f"></i></a></li>
                <li><a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                      class="fab fa-twitter"></i></a></li>
                <li><a
                    href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"><i
                      class="fab fa-linkedin-in"></i></a></li>
              </ul>
              <ul class="product-tags">
                <li>
                  <span>{{ __('Category') . ':' }}</span>
                  <a href="#" class="category-search" data-category_slug="{{ $details->categorySlug }}">
                    {{ $details->categoryName }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="description-wrapper">
            <div class="description-tabs">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#description">{{ __('Description') }}</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#reviews">{{ __('Reviews') }}</a>
                </li>
              </ul>
            </div>

            <div class="tab-content mt-30">
              <div id="description" class="tab-pane fade show active">
                <div class="description-content-box">
                  <p>{!! replaceBaseUrl($details->content, 'summernote') !!}</p>
                </div>
              </div>

              <div id="reviews" class="tab-pane fade">
                <div class="shop-review-area">
                  @if (count($reviews) == 0)
                    <h5 class="mb-30">{{ __('This product has no review yet') . '!' }}</h5>
                  @else
                    @foreach ($reviews as $review)
                      <div class="shop-review-user d-flex">
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
                            $name = $review->user->first_name . ' ' . $review->user->last_name;
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
                    <a href="{{ route('user.login', ['redirect_path' => 'product-details']) }}" class="main-btn">
                      {{ __('Login') }}
                    </a>
                  @endguest

                  @auth('web')
                    <div class="shop-review-form">
                      <form action="{{ route('shop.product_details.store_review', ['id' => $details->id]) }}"
                        method="POST">
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
        </div>
      </div>

      <div class="text-center mt-70">
        {!! showAd(3) !!}
      </div>
    </div>
  </section>
  <!--====== End Products details Section ======-->

  {{-- product search form start --}}
  <form class="d-none" action="{{ route('shop.products') }}" method="GET">
    <input type="hidden" id="category-id" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- product search form end --}}
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/product.js') }}"></script>
@endsection
