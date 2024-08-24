@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($vendor))
    {{ $vendor->username }}
  @endif
@endsection

@section('content')
  <!--====== Start Hero Area ======-->
  <section class="hero-area">
    <div class="breadcrumbs-area bg_cover" style="background-image: url({{ asset('assets/img/' . $bgImg->breadcrumb) }})">
      <div class="container">
        <div class="row">
          <div class="col-lg-10">
            <div class="page-title">
              <div class="author mb-3">
                <figure class="author-img mb-0">
                  <a href="javaScript:void(0)">
                    @if ($vendor->photo != null)
                      <img class="rounded-lg" src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                        alt="Author">
                    @else
                      <img class="rounded-lg" src="{{ asset('assets/img/user.png') }}" alt="Author">
                    @endif
                  </a>
                </figure>
                <div class="author-info">
                  <h4 class="mb-0 text-white">{{ optional($vendor->vendor_info)->shop_name }}</h4>
                  <div class="author-name">
                    <h6 class="mb-0 text-white">{{ $vendor->username }}</h6>
                    <span>{{ __('Member since') }} {{ date('F Y', strtotime($vendor->created_at)) }}</span>
                  </div>
                  <div class="ratings mt-1">
                    <div class="rate">
                      @php
                        $avg_rating = $vendor
                            ->reviews()
                            ->get()
                            ->avg('rating');
                      @endphp
                      <div class="rating-icon" style="width: {{ $avg_rating * 20 }}%"></div>
                    </div>
                    <span class="ratings-total">( {{ $vendor->reviews()->get()->count() }}
                      @if ($vendor->reviews()->get()->count() <= 1)
                        {{ __('Rating') }}
                      @else
                        {{ __('Ratings') }}
                      @endif
                      )
                    </span>
                  </div>
                </div>
              </div>
              <ul class="breadcrumbs-link d-flex">
                <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                <li class="active">{{ $vendor->username }}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Hero Area ======-->


  <!-- Author-single-area start -->
  <div class="author-area pt-130">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h3 class="mb-20">{{ __('All Equipments') }}</h3>
          <div class="author-tabs mb-30">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <button class="nav-link active" type="button" data-toggle="tab" data-target="#all"
                  aria-selected="true">{{ __('All') }}</button>
              </li>
              @foreach ($categories as $item)
                <li class="nav-item">
                  <button class="nav-link" type="button" data-toggle="tab"
                    data-target="#{{ $item->slug }}"aria-selected="false" tabindex="-1">{{ $item->name }}</button>
                </li>
              @endforeach

            </ul>
          </div>
          <div class="tab-content mb-50">
            <div class="tab-pane fade show active" id="all">
              <div class="row">
                @if (count($allEquipments) > 0)
                  @foreach ($allEquipments as $equipment)
                    <div class="col-12">
                      <div class="pricing-item pricing-item-three mb-30">
                        <div class="pricing-img">
                          <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}" class="d-block">
                            <img
                              data-src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}"
                              alt="Pricing image" class="lazy bg-img">
                          </a>

                          @if (!empty($equipment->offer))
                            <span class="discount">{{ $equipment->offer . '% ' . __('off') }}</span>
                          @endif
                        </div>
                        @php
                          $position = $currencyInfo->base_currency_symbol_position;
                          $symbol = $currencyInfo->base_currency_symbol;
                        @endphp

                        <div class="pricing-info">
                          <div class="price-info">
                            <h5>{{ __('Price') }}</h5>
                            
                            <!-- code by AG start -->
                        @if(is_equipment_request_for_price($equipment->equipment_category_id))
                        <div class="price-tag">
                            <h4 class="m-0 p-0">Request A Quote</h4>
                        </div>
                        @else
                        
                            @if(is_equipment_multiple_charges($equipment->equipment_category_id) || is_equipment_temporary_toilet_type($equipment->equipment_category_id) || is_equipment_storage_container_type($equipment->equipment_category_id))
                                <div class="price-tag">
                                    
                                    @if (!empty($equipment['multiple_charges_settings']['base_price']))
                                    <h4 class="m-0 p-0">
                                        {{ $position == 'left' ? $symbol : '' }}{{ amount_with_commission($equipment['multiple_charges_settings']['base_price']) }}{{
                                        $position == 'right' ? $symbol : '' }}
                                    </h4>
                                   
                                    @endif
                                </div>
                              @else
                              
                            <!-- code by AG end -->
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
                            @endif

                                        @endif
                          </div>

                          <div class="pricing-body">
                            <h3 class="title">
                              <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}">
                                {{ strlen($equipment->title) > 25 ? mb_substr($equipment->title, 0, 25, 'UTF-8') . '...' : $equipment->title }}
                              </a>
                            </h3>
                            <div class="price-option">
                              @if (!empty($equipment->per_day_price) && 0)
                                <span
                                  class="span-btn day">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_day_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}</span>
                              @endif

                              @if (!empty($equipment->per_week_price) && 0)
                                <span
                                  class="span-btn week">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_week_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Week') }}</span>
                              @endif

                              @if (!empty($equipment->per_month_price) && 0)
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
                              <a href="#" class="mt-2 more-feature-link">{{ __('More Features') . '...' }}</a>
                            @endif
                          </div>

                          <div class="pricing-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                              <div class="rate">
                                <div class="rating" style="width: {{ $equipment->avgRating * 20 . '%;' }}"></div>
                              </div>
                              <span
                                class="{{ $currentLanguageInfo->direction == 0 ? 'ml-3' : 'mr-3' }}">{{ number_format($equipment->avgRating, 2) }}
                                ({{ $equipment->ratingCount . ' ' . __('rating') }})
                              </span>
                            </div>

                            <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}"
                              class="main-btn">{{ __('View') }}</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12">
                    <h4 class="text-center">{{ __('No Equipment Found') }}</h4>
                  </div>
                @endif
              </div>
              {{ $allEquipments->links() }}
            </div>
            @foreach ($categories as $item)
              @php
                $allEquipments = App\Models\Instrument\Equipment::query()
                    ->where('vendor_id', $vendor->id)
                    ->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
                    ->where('equipment_contents.language_id', '=', $item->language_id)
                    ->where('equipment_contents.equipment_category_id', '=', $item->id)
                
                    ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer')
                    ->orderBy('equipments.id', 'desc')
                    ->paginate(3);
              @endphp
              <div class="tab-pane fade" id="{{ $item->slug }}">
                <div class="row">
                  @if (count($allEquipments) > 0)
                    @foreach ($allEquipments as $equipment)
                      <div class="col-12">
                        <div class="pricing-item pricing-item-three mb-30">
                          <div class="pricing-img">
                            <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}" class="d-block">
                              <img
                                data-src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}"
                                alt="Pricing image" class="lazy bg-img">
                            </a>
                            @if (!empty($equipment->offer))
                              <span class="discount">{{ $equipment->offer . '% ' . __('off') }}</span>
                            @endif
                          </div>
                          @php
                            $position = $currencyInfo->base_currency_symbol_position;
                            $symbol = $currencyInfo->base_currency_symbol;
                          @endphp

                          <div class="pricing-info">
                            <div class="price-info">
                              <h5>{{ __('Price') }}</h5>

                            
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
                              <h3 class="title">
                                <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}">
                                  {{ strlen($equipment->title) > 25 ? mb_substr($equipment->title, 0, 25, 'UTF-8') . '...' : $equipment->title }}
                                </a>
                              </h3>
                              <div class="price-option">
                                @if (!empty($equipment->per_day_price))
                                  <span
                                    class="span-btn day">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_day_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}</span>
                                @endif

                                @if (!empty($equipment->per_week_price))
                                  <span
                                    class="span-btn week">{{ $position == 'left' ? $symbol : '' }}{{ $equipment->per_week_price }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Week') }}</span>
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
                                <a href="#" class="mt-2 more-feature-link">{{ __('More Features') . '...' }}</a>
                              @endif
                            </div>

                            <div class="pricing-bottom d-flex align-items-center justify-content-between">
                              <div class="d-flex flex-row align-items-center">
                                <div class="rate">
                                  <div class="rating" style="width: {{ $equipment->avgRating * 20 . '%;' }}"></div>
                                </div>
                                <span
                                  class="{{ $currentLanguageInfo->direction == 0 ? 'ml-3' : 'mr-3' }}">{{ number_format($equipment->avgRating, 2) }}
                                  ({{ $equipment->ratingCount . ' ' . __('rating') }})
                                </span>
                              </div>

                              <a href="{{ route('equipment_details', ['slug' => $equipment->slug]) }}"
                                class="main-btn">{{ __('View') }}</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @else
                    <div class="col-12">
                      <h4 class="text-center">{{ __('No Equipment Found') }}</h4>
                    </div>
                  @endif
                </div>
                {{ $allEquipments->links() }}
              </div>
            @endforeach

          </div>
          <div class="row">
            <div class="col-xl-10">

            </div>
            <div class="text-center mt-70 pb-30">
              {!! showAd(3) !!}
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <aside class="sidebar-widget-area">
            <div class="widget widget-author-details mb-30">
              <div class="author mb-20 text-center">
                <figure class="author-img mb-15">
                  @if ($vendor->photo != null)
                    <img class="rounded-lg" src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                      alt="Author">
                  @else
                    <img class="rounded-lg" src="{{ asset('assets/img/user.png') }}" alt="Author">
                  @endif
                </figure>
                <div class="author-info">
                  <h3>{{ optional($vendor->vendor_info)->shop_name }}</h3>
                  <!--<h6>{{ $vendor->username }}</h6>-->

                </div>
                <div class="ratings mt-2 justify-content-center">
                  <div class="rate">
                    @php
                      $avg_rating = $vendor
                          ->reviews()
                          ->get()
                          ->avg('rating');
                    @endphp
                    <div class="rating-icon" style="width: {{ $avg_rating * 20 }}%"></div>
                  </div>
                  <span class="ratings-total">( {{ $vendor->reviews()->get()->count() }}
                    @if ($vendor->reviews()->get()->count() <= 1)
                      {{ __('Rating') }}
                    @else
                      {{ __('Ratings') }}
                    @endif
                    )
                  </span>
                </div>
              </div>
              @if (!empty(optional($vendor->vendor_info)->details))
                <div class="font-sm">
                  <div class="click-show">
                    <p class="text">
                      <b>{{ __('About') }}:</b> {{ optional($vendor->vendor_info)->details }}
                    </p>
                  </div>
                  @if (strlen(optional($vendor->vendor_info)->details) > 200)
                    <div class="read-more-btn"><span>{{ __('Read More') }}</span></div>
                  @endif
                </div>
              @endif


              <ul class="toggle-list list-unstyled mt-15 font-sm" id="toggleList" data-toggle-show="5">
                <li>
                  <span class="first">{{ __('Total Items') }} : </span>
                  <span class="last font-sm">{{ $vendor->equipment()->get()->count() }}</span>
                </li>
                @if ($vendor->show_email_addresss == 1)
                  <!--<li>-->
                  <!--  <span class="first">{{ __('Email') }} : </span>-->
                  <!--  <span class="last font-sm"><a href="mailto:{{ $vendor->email }}"-->
                  <!--      title="{{ $vendor->email }}">{{ $vendor->email }}</a></span>-->
                  <!--</li>-->
                @endif

                @if ($vendor->show_phone_number == 1)
                  <!--<li>-->
                  <!--  <span class="first">{{ __('Phone') }} : </span>-->
                  <!--  <span class="last font-sm"><a href="tel:{{ $vendor->phone }}"-->
                  <!--      title="{{ $vendor->phone }}">{{ $vendor->phone }}</a></span>-->
                  <!--</li>-->
                @endif

                @if (!empty(optional($vendor->vendor_info)->city) || !empty(optional($vendor->vendor_info)->country))
                  <li>
                    <span class="first">{{ __('Location') }} : </span>
                    <span class="last font-sm">
                      @if (!empty(optional($vendor->vendor_info)->city))
                        {{ optional($vendor->vendor_info)->city }}
                      @endif
                      @if (!empty(optional($vendor->vendor_info)->city) && !empty(optional($vendor->vendor_info)->country))
                        {{ ',' }}
                      @endif
                      @if (!empty(optional($vendor->vendor_info)->country))
                        {{ optional($vendor->vendor_info)->country }}
                      @endif
                    </span>
                  </li>
                @endif

                <li>
                  <span class="first">{{ __('Member since') }} : </span>
                  <span class="last font-sm">{{ date('F Y', strtotime($vendor->created_at)) }}</span>
                </li>
              </ul>
              <span class="btn-text show-more-btn" data-toggle-btn="toggleList2">{{ __('Show More') }} <i
                  class="fal fa-plus"></i></span>
              @if ($vendor->show_contact_form == 1)
                <div class="btn-groups text-center mt-20">
                  <button type="button" class="main-btn w-100 mb-10" title="Title" data-toggle="modal"
                    data-target="#contactModal">{{ __('Contact Now') }}</button>
                </div>
              @endif

            </div>
            <div class="widget mb-30">
              <div class="text-center">
                {!! showAd(2) !!}
              </div>
            </div>
            <div class="pb-40"></div>
          </aside>
        </div>
      </div>
    </div>
  </div>
  <!-- Author-single-area start -->

  {{-- modal --}}
  <!-- Contact Modal -->
  <div class="contact-modal modal fade" id="contactModal" tabindex="-1" role="dialog"
    aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contactModalLabel">{{ __('Contact Now') . '!' }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="contact-wrapper">
            <div class="contact-form">
              <form action="{{ route('vendor.contact.message') }}" method="POST">
                @csrf
                <input type="hidden" name="vendor_email" value="{{ $vendor->email }}">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form_group mb-3">
                      <input type="text" class="form_control" placeholder="{{ __('Name') }}" name="name"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form_group mb-3">
                      <input type="email" class="form_control" placeholder="{{ __('Email') }}" name="email"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form_group mb-3">
                      <input type="text" class="form_control" placeholder="{{ __('Subject') }}" name="subject"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form_group mb-3">
                      <textarea name="message" class="form_control" placeholder="{{ __('Comment') }}"></textarea>
                    </div>
                  </div>
                  @if ($info->google_recaptcha_status == 1)
                    <div class="col-lg-12">
                      <div class="form_group mb-3">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                      </div>
                      @error('g-recaptcha-response')
                        <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  @endif
                  <div class="col-lg-12 text-center">
                    <button class="main-btn" type="submit" title="Submit">{{ __('Submit') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- modal end --}}
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/equipment.js') }}"></script>
@endsection
