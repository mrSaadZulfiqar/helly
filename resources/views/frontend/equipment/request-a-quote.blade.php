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

  <!--====== Start Equipment Details Section ======-->
  <section class="equipment-details-section pt-130 pb-110">
    <div class="container">
      <div class="row">
           <div class="col-lg-4">
          <div class="equipement-sidebar-info">
            <form action="{{ route('equipment.request_quote') }}" method="POST" enctype="multipart/form-data"
              id="equipment-quote-form">
              @csrf
              <input type="hidden" name="equipment_id" value="{{ $details->id }}">

              <div class="booking-form">
                @php
                  $position = $currencyInfo->base_currency_symbol_position;
                  $symbol = $currencyInfo->base_currency_symbol;

                  // calculate tax
                  $currTotal = $details->lowest_price;
                  $taxAmount = $basicData['equipment_tax_amount'];
                  $calculatedTax = $currTotal * ($taxAmount / 100);

                  // calculate grand total
                  $grandTotal = $currTotal + $calculatedTax + $details->security_deposit_amount;
                @endphp

                <div class="price-info">
                  <h5>{{ __('Price') }}</h5>
                
                  <div class="price-tag">
                        
                        <h4>Request A Quote</h4>
                    </div>
                </div>

                <div class="pricing-body">
				@if (session('success'))
					<div class="row">
					<div class="col-sm-12">
						<div class="alert  alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
					</div>
					</div>
				@endif
                  {{-- show error message for request-price-message --}}
				  
				  
					<?php if( $errors->any()){ ?>
                  <div class="row">
                      <div class="col">
                        <div class="alert alert-danger alert-block">
                        <?php 
						
							foreach ($errors->all() as $message) {
								echo '<strong>'.$message.'</strong><br>';
							} 
						
						?>
                         
                          <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                      </div>
                    </div>
					<?php } ?>
                  
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

                    <div class="form-group">
                        <h5>{{ __('Contact Information') }}</h5>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('First Name') . '*' }}</label>
                        <input type="text" class="form-control" name="first_name" placeholder="{{ __('Enter First Name') }}">
                        <p id="err_first_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Last Name') . '*' }}</label>
                        <input type="text" class="form-control" name="last_name" placeholder="{{ __('Enter Last Name') }}">
                        <p id="err_last_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Email') . '*' }}</label>
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Enter email') }}">
                        <p id="err_email" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Mobile Number') . '*' }}</label>
                        <input type="text" class="form-control" name="phone" placeholder="{{ __('Enter Mobile Number') }}">
                        <p id="err_phone" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Company Name') . '*' }}</label>
                        <input type="text" class="form-control" name="company_name" placeholder="{{ __('Enter Company Name') }}">
                        <p id="err_company_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <h5>{{ __('Project Information') }}</h5>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Project Country') . '*' }}</label>
                        <input type="text" class="form-control" name="project_country" placeholder="{{ __('Enter Project Country') }}">
                        <p id="err_project_country" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Project City') . '*' }}</label>
                        <input type="text" class="form-control" name="project_city" placeholder="{{ __('Enter Project City') }}">
                        <p id="err_project_city" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Project State/Province') . '*' }}</label>
                        <input type="text" class="form-control" name="project_state" placeholder="{{ __('Enter Project State/Province') }}">
                        <p id="err_project_state" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Project ZIP/Postal Code') . '*' }}</label>
                        <input type="text" class="form-control" name="project_zipcode" placeholder="{{ __('Enter Project ZIP/Postal Code') }}">
                        <p id="err_project_zipcode" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Project Start date') }}</label>
                        <input type="date" class="form-control" name="project_startdate">
                        <p id="err_project_startdate" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Estimated Guest/Worker Count') }}</label>
                        <input type="number" step="1" class="form-control" name="worker_count" placeholder="{{ __('Enter Estimated Guest/Worker Count') }}">
                        <p id="err_worker_count" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Explain your project or ask our United Rentals experts a question') }}</label>
                        <textarea class="form-control" name="details" placeholder="{{ __('Briefly describe your upcoming project or ask a question for one of our experts.') }}"></textarea>
                        <p id="err_details" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="button text-center mt-30">
                      <button type="submit" class="main-btn">{{ __('Request A Quote') }}</button>
                    </div>

                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-8">
          @php $sliderImages = json_decode($details->slider_images); @endphp

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

          <div class="description-wrapper">
            <h3 class="title mb-2">{{ $details->title }}</h3>
            <h6>{{ optional($details->vendor)->shop_name }}</h6>
            <div class="vendor-name">
              @if ($details->vendor)
                {{ __('By') }}
                <a href="{{ route('frontend.vendor.details', $details->vendor->username) }}">
                  {{ $vendor = optional($details->vendor)->username }}
                </a>
              @else
                {{ __('By') }} {{ __('Admin') }}</a>
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

                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#features">{{ __('Features') }}</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#reviews">{{ __('Reviews') }}</a>
                </li>
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

  {{-- equipment search form start --}}
  <form class="d-none" action="{{ route('all_equipment') }}" method="GET">
    <input type="hidden" id="category-id" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- equipment search form end --}}
@endsection

