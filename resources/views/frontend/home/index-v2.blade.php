@extends('frontend.layout')

@section('pageHeading')
  {{ __('My Home') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection

@section('content')
  @php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;
  @endphp

  <!--====== Start Hero Section ======-->
  <section class="hero-area">
    <div class="hero-wrapper-two bg_cover lazy"
      @if (!empty($heroSectionImage)) data-bg="{{ asset('assets/img/hero/' . $heroSectionImage) }}" @endif>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <h1>{{ empty($staticInfo->title) ? '' : $staticInfo->title }}</h1>
              <p>{{ empty($staticInfo->text) ? '' : $staticInfo->text }}</p>

              @if (!empty($staticInfo->button_name) && !empty($staticInfo->button_url))
                <a href="{{ $staticInfo->button_url }}" class="main-btn">{{ $staticInfo->button_name }}</a>
              @endif
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-search-wrapper">
              <h2>{{ __('Find Your Equipment') }}</h2>
              <form action="{{ route('all_equipment') }}" method="GET">
                <div class="row">
                  <div class="col-lg-12">
                    <select class="form_control" name="category">
                      <option selected disabled>{{ __('Search By Category') }}</option>

                      @foreach ($equipCategories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-lg-12">
                    <div class="form_group">
                      <input type="text" class="form_control" placeholder="{{ __('What are you looking for?') }}"
                        name="keyword">
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form_group">
                      <div class="input-wrap">
                        <input type="text" class="form_control" id="date-range"
                          placeholder="{{ __('Search By Date') }}" name="dates" readonly>
                        <i class="far fa-calendar-alt"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form_group">
                      <div class="input-wrap">
                        <input type="text" class="form_control"
                          placeholder="{{ __('Location') }}" name="location" >
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <button type="submit" class="search-btn">{{ __('Search') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Hero Section ======-->

  <!--====== Start About Section ======-->
  @if ($secInfo->about_section_status == 1)
    <section class="about-area pt-130 pb-110">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="about-content-box-two about-content-box pb-50">
              <div class="section-title mb-20">
                <span class="sub-title">{{ !empty($aboutSecInfo->subtitle) ? $aboutSecInfo->subtitle : '' }}</span>
                <h2>{{ !empty($aboutSecInfo->title) ? $aboutSecInfo->title : '' }}</h2>
              </div>
              <p>{!! !empty($aboutSecInfo->text) ? nl2br($aboutSecInfo->text) : '' !!}</p>

              @if (!empty($aboutSecInfo->button_name) && !empty($aboutSecInfo->button_url))
                <a href="{{ $aboutSecInfo->button_url }}" class="main-btn">{{ $aboutSecInfo->button_name }}</a>
              @endif
            </div>
          </div>

          <div class="col-lg-7">
            @if (!empty($aboutSectionImage))
              <div class="about-img-box about-img-box-two pb-50">
                <img data-src="{{ asset('assets/img/' . $aboutSectionImage) }}" alt="about image" class="lazy">
              </div>
            @endif
          </div>
        </div>

        <div class="mt-40 text-center">
          {!! showAd(3) !!}
        </div>
      </div>
    </section>
  @endif
  <!--====== End About Section ======-->

  <!--====== Start Working Process Section ======-->
  @if ($secInfo->work_process_section_status == 1)
    <section class="working-process light-gray pt-130 pb-110">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-title text-center mb-65">
              <span
                class="sub-title">{{ !empty($workProcessSecInfo->subtitle) ? $workProcessSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($workProcessSecInfo->title) ? $workProcessSecInfo->title : '' }}</h2>
              <p>{{ !empty($workProcessSecInfo->text) ? $workProcessSecInfo->text : '' }}</p>
            </div>
          </div>
        </div>

        @if (count($processes) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Work Process Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row no-gutters">
            @foreach ($processes as $process)
              <div class="col-lg-3 col-md-6 process-column">
                <div class="process-item process-item-two text-center mb-35">
                  <div class="count-box">
                    <div class="process-count">{{ str_pad($process->serial_number, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="icon">
                      <i class="{{ $process->icon }}"></i>
                    </div>
                  </div>

                  <div class="content text-center">
                    <h5>{{ $process->title }}</h5>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        <div class="mt-60 text-center">
          {!! showAd(3) !!}
        </div>
      </div>
    </section>
  @endif
  <!--====== End Working Process Section ======-->

  <!--====== Start Features Section ======-->
  @if ($secInfo->feature_section_status == 1)
    <section class="features-area pt-120 pb-100 dark-blue">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-title section-title-white text-center mb-70">
              <span class="sub-title">{{ !empty($featureSecInfo->subtitle) ? $featureSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($featureSecInfo->title) ? $featureSecInfo->title : '' }}</h2>
              <p>{{ !empty($featureSecInfo->text) ? $featureSecInfo->text : '' }}</p>
            </div>
          </div>
        </div>

        @if (count($features) == 0)
          <div class="row text-center">
            <div class="col">
              <h3 class="text-light">{{ __('No Feature Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row">
            @foreach ($features as $feature)
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div
                  class="features-item features-item-two text-center mb-40 {{ $loop->iteration % 2 == 0 ? 'active-item' : '' }}">
                  <div class="icon">
                    <i class="{{ $feature->icon }}"></i>
                  </div>
                  <div class="content">
                    <h4>{{ $feature->title }}</h4>
                    <p>{{ $feature->text }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endif
  <!--====== End Features Section ======-->

  <!--====== Start Counter Section ======-->
  @if ($secInfo->counter_section_status == 1)
    <section class="counter-area bg-with-overlay bg-with-overlay-white bg_cover pt-130 pb-90 lazy"
      @if (!empty($counterSectionImage)) data-bg="{{ asset('assets/img/' . $counterSectionImage) }}" @endif>
      <div class="container">
        @if (count($counters) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Information Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row">
            @foreach ($counters as $counter)
              <div class="col-lg-6 col-md-6 col-sm-12 counter-column">
                <div class="counter-item counter-item-two mb-40 text-center">
                  <div class="icon">
                    <i class="{{ $counter->icon }}"></i>
                  </div>
                  <div class="content">
                    <h2><span class="count">{{ $counter->amount }}</span>+</h2>
                    <h5>{{ $counter->title }}</h5>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endif
  <!--====== End Counter Section ======-->

  <!--====== Start Featured Equipment Section ======-->
  @if ($secInfo->equipment_section_status == 1)
    <section class="pricing-area pt-120 pb-100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="section-title text-center mb-55">
              <span class="sub-title">{{ !empty($equipmentSecInfo->subtitle) ? $equipmentSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($equipmentSecInfo->title) ? $equipmentSecInfo->title : '' }}</h2>
              <p>{{ !empty($equipmentSecInfo->text) ? $equipmentSecInfo->text : '' }}</p>
            </div>
          </div>
        </div>

        @if (count($allEquipment) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Equipment Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="equipment-slider">
            @foreach ($allEquipment as $equipment)
              <div class="slider-item">
                <div class="pricing-item pricing-item-two mb-40">
                  <div class="pricing-img">
                    <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}" class="d-block">
                      <img
                        data-src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}"
                        alt="image" class="lazy">
                    </a>
                  </div>

                  <div class="pricing-info">
                    <div class="price-info">
                      <h5 class="title">
                        {{ strlen($equipment->title) > 15 ? mb_substr($equipment->title, 0, 15, 'UTF-8') . '...' : $equipment->title }}
                      </h5>

                      @if (!empty($equipment->lowest_price))
                        <span>{{ __('Starts From') }}</span>
                      @endif

                      <div class="price-tag">
                        @if (!empty($equipment->lowest_price))
                          <h4>
                            {{ $position == 'left' ? $symbol : '' }}{{ $equipment->lowest_price }}{{ $position == 'right' ? $symbol : '' }}
                          </h4>
                        @endif
                      </div>
                    </div>

                    <div class="pricing-body">
                      <div class="price-option">
                        @if (!empty($equipment->per_day_price))
                          <span
                            class="span-btn day">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_day_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}</span>
                        @endif

                        @if (!empty($equipment->per_week_price))
                          <span
                            class="span-btn week active-btn">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_week_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Week') }}</span>
                        @endif

                        @if (!empty($equipment->per_month_price))
                          <span
                            class="span-btn month">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_month_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Month') }}</span>
                        @endif
                      </div>

                      <ul class="info-list">
                        @php $features = explode(PHP_EOL, $equipment->features); @endphp

                        @foreach ($features as $feature)
                          @if ($loop->iteration <= 3)
                            <li>{{ $feature }}</li>
                          @else
                            <li class="more-feature d-none">{{ $feature }}</li>
                          @endif
                        @endforeach
                      </ul>

                      @if (count($features) > 3)
                        <a href="#" class="more-feature-link">{{ __('More Features') . '...' }}</a>
                      @endif

                      <span class="delivary">
                        <a href="#" class="category-search" data-category_slug="{{ $equipment->categorySlug }}">
                          {{ $equipment->categoryName }}
                        </a>
                        <div class="vendor-name">
                          @if ($equipment->vendor_id != null)
                            {{ __('By') }}
                            <a href="{{ route('frontend.vendor.details', $equipment->vendor->username) }}">
                              {{ $vendor = optional($equipment->vendor)->username }}
                            </a>
                          @else
                            {{ __('By') }} {{ __('Admin') }}
                          @endif
                        </div>
                      </span>
                    </div>

                    <div class="pricing-bottom">
                      <div class="d-flex flex-row justify-content-center">
                        <div class="rate">
                          <div class="rating" style="width: {{ $equipment->avgRating * 20 . '%;' }}"></div>
                        </div>

                        <div class="{{ $currentLanguageInfo->direction == 0 ? 'ml-3' : 'mr-3' }}">
                          {{ number_format($equipment->avgRating, 2) }}
                          ({{ $equipment->ratingCount . ' ' . __('rating') }})
                        </div>
                      </div>

                      <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}"
                        class="main-btn main-btn-primary mt-3">
                        {{ __('View') }}
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        <div class="mt-90 text-center">
          {!! showAd(3) !!}
        </div>
      </div>
    </section>
  @endif
  <!--====== End Featured Equipment Section ======-->

  <!--====== Start Testimonial Section ======-->
  @if ($secInfo->testimonial_section_status == 1)
    <section class="testimonial-area dark-blue pt-120 pb-110">
      <div class="container">
        <div class="row align-items-end">
          <div class="col-lg-6">
            <div class="section-title section-title-white mb-50">
              <span
                class="sub-title">{{ !empty($testimonialSecInfo->subtitle) ? $testimonialSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($testimonialSecInfo->title) ? $testimonialSecInfo->title : '' }}</h2>
            </div>
          </div>
        </div>

        @if (count($testimonials) == 0)
          <div class="row text-center">
            <div class="col">
              <h3 class="text-light mt-4">{{ __('No Testimonial Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row testimonial-slider-one">
            @foreach ($testimonials as $testimonial)
              <div class="col-lg-4">
                <div class="testimonial-item testimonial-item-two mb-35">
                  <div class="testimonial-content">
                    <div class="quote">
                      <i class="fal fa-quote-left"></i>
                    </div>
                    <p>{{ $testimonial->comment }}</p>
                  </div>

                  <div class="testimonial-thumb-title d-flex align-items-center">
                    <div class="thumb">
                      @if (is_null($testimonial->image))
                        <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image" class="lazy">
                      @else
                        <img data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}" alt="client image"
                          class="lazy">
                      @endif
                    </div>

                    <div class="title">
                      <h4>{{ $testimonial->name }}</h4>
                      <span class="position">{{ $testimonial->occupation }}</span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endif
  <!--====== End Testimonial Section ======-->

  <!--====== Start Products Section ======-->
  @if ($secInfo->product_section_status == 1)
    <section class="products-area light-gray pt-120 pb-110">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-title text-center mb-55">
              <span class="sub-title">{{ !empty($productSecInfo->subtitle) ? $productSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($productSecInfo->title) ? $productSecInfo->title : '' }}</h2>
              <p>{{ !empty($productSecInfo->text) ? $productSecInfo->text : '' }}</p>
            </div>
          </div>
        </div>

        @if (count($products) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Product Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row product-two-slider">
            @foreach ($products as $product)
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="product-item product-item-one mb-40">
                  <div class="product-img">
                    <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}" class="d-block">
                      <img data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                        alt="product" class="lazy">
                    </a>
                  </div>

                  <div class="product-info">
                    <h3 class="title">
                      <a
                        href="{{ route('shop.product_details', ['slug' => $product->slug]) }}">{{ $product->title }}</a>
                    </h3>
                    <span
                      class="price">{{ $position == 'left' ? $symbol : '' }}{{ $product->current_price }}{{ $position == 'right' ? $symbol : '' }}
                      @if (!empty($product->previous_price))
                        <span
                          class="pre-price">{{ $position == 'left' ? $symbol : '' }}{{ $product->previous_price }}{{ $position == 'right' ? $symbol : '' }}</span>
                      @endif
                    </span>

                    <a href="{{ route('shop.product.add_to_cart', ['id' => $product->id, 'quantity' => 1]) }}"
                      class="main-btn add-to-cart-icon mt-3">
                      {{ __('Add To Cart') }}
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        <div class="mt-90 text-center">
          {!! showAd(3) !!}
        </div>
      </div>
    </section>
  @endif
  <!--====== End Products Section ======-->

  <!--====== Start Blog Section ======-->
  @if ($secInfo->blog_section_status == 1)
    <section class="blog-area pt-120 pb-130">
      <div class="container">
        <div class="row align-items-end">
          <div class="col-lg-6">
            <div class="section-title mb-60">
              <span class="sub-title">{{ !empty($blogSecInfo->subtitle) ? $blogSecInfo->subtitle : '' }}</span>
              <h2>{{ !empty($blogSecInfo->title) ? $blogSecInfo->title : '' }}</h2>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="blog-arrows-one mb-60"></div>
          </div>
        </div>

        @if (count($blogs) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Blog Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="row blog-slider-one">
            @foreach ($blogs as $blog)
              <div class="col-lg-4">
                <div class="blog-post-item blog-post-item-two">
                  <div class="post-thumbnail">
                    <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}" class="d-block">
                      <img data-src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="image" class="lazy">
                    </a>
                    <a href="#" class="category post-category" data-category_slug="{{ $blog->categorySlug }}">
                      {{ $blog->categoryName }}
                    </a>
                  </div>

                  <div class="entry-content">
                    <h3 class="title">
                      <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                        {{ strlen($blog->title) > 30 ? mb_substr($blog->title, 0, 30, 'UTF-8') . '...' : $blog->title }}
                      </a>
                    </h3>
                    <div class="post-meta">
                      <ul>
                        <li><span><i class="fas fa-user"></i>{{ $blog->author }}</span></li>
                        <li><span><i
                              class="fas fa-calendar-alt"></i>{{ date_format($blog->created_at, 'M d, Y') }}</span>
                        </li>
                      </ul>
                    </div>
                    <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                      class="main-btn">{{ __('Read More') }}</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endif
  <!--====== End Blog Section ======-->

  <!--====== Start Partner/Sponsor Section ======-->
  @if ($secInfo->partner_section_status == 1)
    <section class="sponsor-area pb-130">
      <div class="container">
        @if (count($partners) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Partner Found') . '!' }}</h3>
            </div>
          </div>
        @else
          <div class="sponsor-slider-two">
            @foreach ($partners as $partner)
              <div class="sponsor-item sponsor-item-two mb-40">
                <a href="{{ $partner->url }}" target="_blank">
                  <img data-src="{{ asset('assets/img/partners/' . $partner->image) }}" alt="sponsor image"
                    class="lazy">
                </a>
              </div>
            @endforeach
          </div>
        @endif

        <div class="mt-100 text-center">
          {!! showAd(3) !!}
        </div>
      </div>
    </section>
  @endif
  <!--====== End Partner/Sponsor Section ======-->

  <!--====== Start Newsletter Section ======-->
  @if ($secInfo->subscribe_section_status == 1)
    <section class="newsletter-area">
      <div class="newsletter-wrapper-two pt-120 pb-130 bg_cover">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="newsletter-content-box">
                <div class="section-title text-center mb-30">
                  <h2>{{ __('Subscribe to Our Newsletter') }}</h2>
                </div>

                <form class="newsletter-form subscription-form" action="{{ route('store_subscriber') }}"
                  method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-9 col-md-8">
                      <div class="form_group">
                        <input type="email" class="form_control" placeholder="{{ __('Enter Your Email Address') }}"
                          name="email_id">
                      </div>
                    </div>

                    <div class="col-lg-3 col-md-4">
                      <div class="form_group">
                        <button type="submit" class="newsletter-btn">{{ __('Subscribe') }}</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!--====== End Newsletter Section ======-->

  {{-- equipment search form start --}}
  <form class="d-none" action="{{ route('all_equipment') }}" method="GET">
    <input type="hidden" id="category-id" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- equipment search form end --}}

  {{-- post search form start --}}
  <form class="d-none" action="{{ route('blog') }}" method="GET">
    <input type="hidden" id="categoryKey" name="category">

    <button type="submit" id="form-submit-btn"></button>
  </form>
  {{-- post search form end --}}
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/equipment.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/product.js') }}"></script>
@endsection
