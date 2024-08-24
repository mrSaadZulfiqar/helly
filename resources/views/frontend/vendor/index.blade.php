@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_page }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_page }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->vendor_page_title : '',
  ])


  <!-- Author-single-area start -->
  <div class="author-area pt-130 pb-100">
    <div class="container">
      <div class="authors-search-filter mb-60">
        <form action="{{ route('frontend.vendors') }}" method="GET">
          <div class="search-filter-form">
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="form_group">
                  <input type="text" class="form_control" placeholder="{{ __('username / shop name') }}" name="us_name"
                    value="{{ request()->input('us_name') }}">
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="form_group">
                  <input type="text" class="form_control" value="{{ request()->input('location') }}"
                    placeholder="{{ __('Enter Shop Location') }}" name="location">
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="form_group">
                  <select class="wide nice-select1" name="rating">
                    <option value="">{{ __('Show All Rating') }}</option>
                    <option {{ request()->input('rating') == 5 ? 'selected' : '' }} value="5">
                      {{ __('5 Star Rating') }}</option>
                    <option {{ request()->input('rating') == 4 ? 'selected' : '' }} value="4">
                      {{ __('4 Star And Higher') }}</option>
                    <option {{ request()->input('rating') == 3 ? 'selected' : '' }} value="3">
                      {{ __('3 Star And Higher') }}</option>
                    <option {{ request()->input('rating') == 2 ? 'selected' : '' }} value="2">
                      {{ __('2 Star And Higher') }}</option>
                    <option {{ request()->input('rating') == 1 ? 'selected' : '' }} value="1">
                      {{ __('1 Star And Higher') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="form_group">
                  <button class="search-btn">{{ __('Search') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="row">
        <div class="col-lg-12">
          @if (count($vendors) > 0)
            <div class="row">
              @php
                $vendorIds = [];
              @endphp
              @foreach ($vendors as $item)
                @if (!in_array($item->id, $vendorIds))
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-center border p-3 mb-30">
                      <figure class="card-img mx-auto mb-20">
                        <a href="{{ route('frontend.vendor.details', $item->username) }}" target="_self"
                          title="{{ $item->username }}">
                          @if ($item->photo != null)
                            <img class="rounded-lg" src="{{ asset('assets/admin/img/vendor-photo/' . $item->photo) }}"
                              alt="Author">
                          @else
                            <img class="rounded-lg" src="{{ asset('assets/img/user.png') }}" alt="Author">
                          @endif

                        </a>
                      </figure>
                      <div class="card-content">
                        <h6 class="card-title mb-1"><a href="{{ route('frontend.vendor.details', $item->username) }}"
                            target="_self" title="{{ $item->shop_name }}">{{ $item->shop_name }}</a>
                        </h6>


                        <h6 class="card-title mb-1 vendor_username_list"><a
                            href="{{ route('frontend.vendor.details', $item->username) }}" target="_self"
                            title="{{ $item->username }}">{{ $item->username }}</a></h6>

                        <div class="ratings mt-1">
                          <div class="rate">
                            @php
                              $avg_rating = App\Models\Instrument\EquipmentReview::where('vendor_id', $item->id)->avg('rating');
                              $review_count = App\Models\Instrument\EquipmentReview::where('vendor_id', $item->id)->count();
                            @endphp
                            <div class="rating-icon" style="width: {{ $avg_rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">( {{ $review_count }}
                            @if ($review_count <= 1)
                              {{ __('Rating') }}
                            @else
                              {{ __('Ratings') }}
                            @endif
                            )
                          </span>
                        </div>

                        <div class="mb-15 font-sm">
                          @php
                            $equipment_count = App\Models\Instrument\Equipment::where('vendor_id', $item->id)->count();
                          @endphp

                          <span>{{ $equipment_count }}
                            @if ($equipment_count <= 1)
                              {{ __('Item') }}
                            @else
                              {{ __('Items') }}
                            @endif
                          </span>
                        </div>
                        <a href="{{ route('frontend.vendor.details', $item->username) }}" target="_self"
                          title="{{ $item->username }}" class="btn-text">
                          {{ __('View Profile') }} </a>
                      </div>
                    </div>
                  </div>
                  @php
                    array_push($vendorIds, $item->id);
                  @endphp
                @endif
              @endforeach

            </div>
            {{ $vendors->links() }}
          @else
            <h2 class="text-center mb-30">{{ __('No Vendor Found') . '!' }}</h2>
          @endif
          <div class="text-center mt-70 pb-30">
            {!! showAd(3) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Author-single-area start -->

@endsection
