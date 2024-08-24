@extends('vendors.layout')

@section('content')
<style>
    .vendor-interest-form-main {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background: #ffffff8a;
}

.vendor-interest-form-main form {
    width: 50%;
    background: #fff;
    margin: 0 auto;
    margin-top: 5%;
    box-shadow: 0px 0px 10px #0009;
    border-radius: 10px;
    padding: 2%;
        height: 75%;
    overflow: auto;
}
a.vendor-interest-close {
    position: sticky;
    font-size: 25px;
    right: 0;
    top: 0;
    float: right;
    background: #fff;
    padding: 15px;
    border: 1px solid;
    border-radius: 10px;
    line-height: 1;
}
.vendor-interest-form-main .equipments_handel_fields>label:first-child {font-size: 20px !IMPORTANT;font-weight: 700;}

.vendor-interest-form-main .equipments_handel_fields label {
    width: 100%;
}

.vendor-interest-form-main .equipments_handel_fields input, .vendor-interest-form-main .equipments_handel_fields select {
    width: 100%;
    padding: 10px 5px;
}
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Equipment') }}</h4>
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
        <a href="#">{{ __('Equipment') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Equipment') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Equipment') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Equipment') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.equipment_management.all_equipment', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              {{-- <div class="ml-2">
                <label for=""><strong>{{ __('Slider Images') . '*' }} <i data-toggle="tooltip" data-placement="top" title="Images are not required but recommended" class="fa fa-info-circle" aria-hidden="true"></i></strong></label>
                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                  @csrf
                  <div class="fallback"></div>
                </form>
                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
              </div> --}}

              <form id="equipmentForm" action="{{ route('vendor.equipment_management.store_equipment') }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div id="slider-image-id"></div>

                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }} <i data-toggle="tooltip" data-placement="top" title="Images are not required but recommended" class="fa fa-info-circle" aria-hidden="true"></i></label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="thumbnail_image">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image Size: 370x430') }}</p>
                </div>
                
                
                <div class="mt-5">
                  @foreach ($languages as $language)
                    <div class="version">
                      {{-- <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button"
                            class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                            data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div> --}}

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Title') }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group equipment-fields-box {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php $categories = $language->categories; @endphp

                                <label>{{ __('Category') . '*' }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control">
                                  <option selected disabled>{{ __('Select a Category') }}</option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
							  
							  <!-- code by AG start -->
                                <div class="agcd-equipment-field-container"></div>
                              <!-- code by AG end -->
                            </div>
                            <!--<div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $locations = App\Models\Instrument\Location::where([['vendor_id', Auth::guard('vendor')->user()->id], ['language_id', $language->id]])
                                      ->orderBy('id', 'desc')
                                      ->get();
                                @endphp

                                <label>{{ __('Locations') . '*' }}</label>
                                <select id="mySelect2{{ $language->id }}" name="{{ $language->code }}_location_ids[]"
                                  class="form-control remove_locations select2 {{ $language->id }}_locations" multiple>
                                  @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div> -->
                          </div>

                          <div class="row d-none">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Features') . '*' }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_features"
                                  placeholder="{{ __('Enter Equipment Features') }}" rows="7"></textarea>
                                <p class="text-warning mt-1 mb-0">
                                  {{ __('To seperate the features, enter a new line after each feature.') }}
                                </p>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') }}</label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_description"
                                  placeholder="{{ __('Enter Equipment Description') }}" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          {{-- <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}"></textarea>
                              </div>
                            </div>
                          </div> --}}

                          <div class="row">
                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }} <strong
                                        class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Quantity') . '*' }}</label>
                      <input type="number" class="form-control" name="quantity"
                        placeholder="{{ __('Enter Quantity') }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Minimum Booking Days') . '*' }}</label>
                      <input type="number" class="form-control" name="min_booking_days"
                        placeholder="{{ __('Enter Number of Days') }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Maximum Booking Days') . '*' }}</label>
                      <input type="number" class="form-control" name="max_booking_days"
                        placeholder="{{ __('Enter Number of Days') }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Offer') . ' (' . __('in Percentage') . ')' }}</label>
                      <input type="number" class="form-control" name="offer"
                        placeholder="{{ __('Enter Offer Amount') }}">
                    </div>
                  </div>
                </div>

                <div class="row">
                  @php $currencyText = $currencyInfo->base_currency_text; @endphp

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Per Day Price') . ' (' . $currencyText . ')' }}</label>
                      <input type="number" class="form-control" name="per_day_price"
                        placeholder="{{ __('Enter Per Day Price') }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Per Week Price') . ' (' . $currencyText . ')' }}</label>
                      <input type="number" class="form-control" name="per_week_price"
                        placeholder="{{ __('Enter Per Week Price') }}">
                    </div>Security Deposit Amount (USD)
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Per Month Price') . ' (' . $currencyText . ')' }}</label>
                      <input type="number" class="form-control" name="per_month_price"
                        placeholder="{{ __('Enter Per Month Price') }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Security Deposit Amount') . ' (' . $currencyText . ')' }}</label>
                      <input type="number" class="form-control" name="security_deposit_amount"
                        placeholder="{{ __('Enter Security Deposit Amount') }}">
                    </div>
                  </div>
                    @php
                        $locations = \App\Models\Instrument\Location::where('vendor_id',Auth::guard('vendor')->user()->id)->get();
                    @endphp
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Select Warehouse') }}</label>
                      <select class="form-control" name="location_id" required>
                          <option disabled selected>Select Location</option>
                          @foreach($locations as $location)
                          <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>

                </div>

                
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="equipmentForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    'use strict';
    const imgUpUrl = "{{ route('vendor.equipment_management.upload_slider_image') }}";
    const imgRmvUrl = "{{ route('vendor.equipment_management.remove_slider_image') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/slider-image.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
  
  <!-- code by AG start -->
  <script>
    $(document).ready(function(){
        let is_multiple_charges = 0;
        
        // script for temporary toilet equipment case start
        
        $(document).on('change', '#rental_days_included, #base_price-cd-temporary-toilet-cat', function(){
            $('input[name="min_booking_days"]').val(1);
            $('input[name="max_booking_days"]').val(1000);
            
            var rental_days_included = $('#rental_days_included').val();
            var base_price = $('#base_price-cd-temporary-toilet-cat').val();
            if(rental_days_included != ''){
                if(rental_days_included == 'Monthly'){
                    rental_days_included = 30;
                }
                var price_daily = base_price / rental_days_included;
                
                
                $('input[name="per_day_price"]').val(price_daily.toFixed(2));
            }
        });
        
        // script for temporary toilet equipment case end
        
        // script for Dumpsters/ Portable Storage Container equipment case start
        
        $(document).on('change', '#rental_days', function(){
            $('input[name="min_booking_days"]').val($(this).val());
            $('input[name="max_booking_days"]').val(1000);
            
            var rental_days = $('#rental_days').val();
            var base_price = $('#base_price').val();
            if(rental_days > 0 && base_price > 0){
                var price_daily = base_price / rental_days;
                
                
                $('input[name="per_day_price"]').val(price_daily.toFixed(2));
            }
        });
        
        $(document).on('change', '#base_price', function(){
            var rental_days = $('#rental_days').val();
            var base_price = $('#base_price').val();
            if(rental_days > 0 && base_price > 0){
                var price_daily = base_price / rental_days;
                $('input[name="per_day_price"]').val(price_daily.toFixed(2));
            }
            
        });
        
        // script for Dumpsters/ Portable Storage Container equipment case end
        $(document).on('change','.equipment-fields-box select',function(){
            var category_id = $('select[name="en_category_id"]').val();
            var category_name_ = $('select[name="en_category_id"]').find('option[value="'+category_id+'"]').text();
            if(category_name_ == 'Dumpster'){
                var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                category_name_ = category_name_+'-'+type___;
                
            }
            if(category_name_ == 'Temporary Toilets'){
                var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                category_name_ = category_name_+'-'+type___;
            }
            if(category_name_ == 'Portable Storage Containers'){
                var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                category_name_ = category_name_+'-'+type___;
            }
            
            var rand_suffix_ = '{{ time() }}-{{ rand(10,10000) }}';
            
            $('input[name="en_title"]').val(category_name_+'-'+rand_suffix_);
        });
        
        
        $(document).on('change','select[name="en_category_id"]', function(){
            is_multiple_charges = 0;
            $('input[name="per_week_price"]').parent().parent().show();
            $('input[name="per_month_price"]').parent().parent().show();
            
            $('input[name="min_booking_days"]').prop('readonly', false);
            $('input[name="max_booking_days"]').prop('readonly', false);
            $('input[name="per_day_price"]').prop('readonly', false);
            $('input[name="per_day_price"]').val('');
            
            var category_id = $(this).val();
             var category_name_ = $(this).find('option[value="'+category_id+'"]').text();
             
            $('.agcd-equipment-field-container').html('');
            $('.request-loader').addClass('show');
            $.ajax({
                url: '{{ route("vendor.equipment_management.get_equipment_fields") }}',
                method: 'GET',
                data: {category_id:category_id},
                success: function (data) {
                    $('.request-loader').removeClass('show');

                    if (data.status == 'success') {
                    //location.reload();
                        $('.agcd-equipment-field-container').html(data.fields_html);
                        $('.vendor-interest-form-main').remove();
                        $('body').append(data.vendor_interest_equipment_form);
                        
                        is_multiple_charges = data.is_multiple_charges;
                        
                        if(data.is_multiple_charges || data.is_temporary_toilet || data.is_storage_container){
                            $('input[name="per_week_price"]').parent().parent().hide();
                            $('input[name="per_month_price"]').parent().parent().hide();
                            
                            $('input[name="min_booking_days"]').prop('readonly', true);
                            $('input[name="max_booking_days"]').prop('readonly', true);
                            $('input[name="per_day_price"]').prop('readonly', true);
                        }
                        
                        var rand_suffix_ = '{{ time() }}-{{ rand(10,10000) }}';
                        
                        if(category_name_ == 'Dumpster'){
                            var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                            category_name_ = category_name_+'-'+type___;
                            
                        }
                        if(category_name_ == 'Temporary Toilets'){
                            var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                            category_name_ = category_name_+'-'+type___;
                        }
                        if(category_name_ == 'Portable Storage Containers'){
                            var type___ = $('.equipment-fields-box input[value="Type"]').prev().prev().val();
                            category_name_ = category_name_+'-'+type___;
                        }
                        
                        $('input[name="en_title"]').val(category_name_+'-'+rand_suffix_);
                    }
                },
                error: function (error) {
                    let errors = ``;

                    for (let x in error.responseJSON.errors) {
                    errors += `<li>
                            <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
                        </li>`;
                    }


                    $('#equipmentErrors ul').html(errors);
                    $('#equipmentErrors').show();

                    $('.request-loader').removeClass('show');

                    $('html, body').animate({
                    scrollTop: $('#equipmentErrors').offset().top - 100
                    }, 1000);
                }
                });
        });
    });
  </script>
  <!-- code by AG end -->
@endsection
