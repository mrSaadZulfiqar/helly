<style>
.pricing-info .vendor-name {
    visibility: visible;
}
    .header-area-one .header-navigation .primary-menu .location-inputs .location-inp {
        padding: 7px 13px !important;
    }

    span.current-location-nav {
        position: absolute;
        top: 16px;
        right: 26px;
        color: var(--primary-color);
        display: inline-flex;
        cursor: pointer;
        background: #fff;
    }
    

.custom-equipment-card {
    background-color: white;
    width: 328px;
    border-radius: 1.2rem;
    display: flex;

}

.custom-equipment-image {
    max-width: 100%;
    width: 100px;
    height: 180px;
    border-radius: 1.2rem 0rem 0rem 1.2rem;

}
.equipment-card-title {
    font-size: 14px;
    font-weight: 700;
    padding: 0.4rem 0.4rem 0rem 0.4rem !important;
    margin: 0px 0px !important;
}

.equipment-card-info {
    font-size: 12px;
    padding: 0.4rem 0.4rem 0rem 0.4rem !important;
    margin: 0px 0px !important;

}


.div-bottom-info {
    position: absolute;
    bottom: 0px;
    width: 100%;
    left: 0px;
    right: 0px;
    background-color: #f1f8ea;
    border-radius: 0rem 0rem 1.2rem 0rem;



}

.custom-equipment-content {
    position: relative;
    width: 228px;

}

.equipment-price-text {
    font-size: 20px;
    font-weight: 700;
    padding: 0.4rem 0.4rem 0rem 0.4rem !important;
    margin: 0px 0px !important;


}

.equipment-card-vendor {
    font-size: 10px;
    padding: 0rem 0.4rem 0.4rem 0.4rem !important;
    margin: 0px 0px !important;

}

.div-bottom-info-child {
    position: relative;
}

.equipment-view-detail {
    position: absolute;
    bottom: 10px;
    right: 10px;
    padding: 2px 8px;
    font-size: 12px;
    background-color: #3387E2;
    border: 1px solid #3387E2;
    border-radius: 6px;
    font-weight: 700;
    color: #fff;


}

.equipment-view-detail:focus {
    outline: 1px solid #3387E2;

}

.equipment-view-detail:hover {
    color: #FFF;

}
.gm-style .gm-style-iw-c{
    padding: 0px 0px;
}
.gm-style-iw-d{
        overflow: hidden !important;
}
.gm-style .gm-style-iw-c{
    border-radius: 1.2rem 1.2rem 1.2rem 1.2rem;
}
.gm-ui-hover-effect{
        top: 0px !important;
    right: 0px !important;
}
.pagination-item {
    height: 35px;
    width: 35px;
    border-radius: 50%;
    background: #10191b;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0rem 0.2rem;

}
.pagination-item-active{
    height: 35px;
    width: 35px;
    border-radius: 50%;
    background: #3387e2;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0rem 0.2rem;
}
.pagination-item:hover{
    background: #3387e2;
    color: #fff;
}
.pagination-item-next,.pagination-item-prev{
    height: 35px;
    background: #10191b;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0rem 0.2rem;
    padding: 0.4rem 1rem;
}
.pagination-item-next{
    border-radius:  0px 10px 10px 0px;   
}
.pagination-item-prev{
    border-radius: 10px 0px 0px 10px;   
}
.disabled{
     opacity: .4;
  cursor: default !important;
  pointer-events: none;
}
.booking-btn{
    width: 100%;
    text-align: center;
    padding: 5px 15px;
    font-size: 18px;
    font-weight: 600;
    color: #fff;
    background-color: #0E2B5C;
}
</style>
@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading ? $pageHeading->equipment_page_title : '' }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo ? $seoInfo->meta_keyword_equipment : '' }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_equipment }}
    @endif
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', [
        'breadcrumb' => $bgImg->breadcrumb,
        'title' => $pageHeading ? $pageHeading->equipment_page_title : '',
    ])
    <!--====== Start Equipments section ======-->
    <!-- Font Awesome -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <script src="https://kit.fontawesome.com/6e76ce4fba.js" crossorigin="anonymous"></script>

    <style>
        .header-area-one .header-navigation .primary-menu .location-inputs .location-inp {
            border: 0.5px solid #c1c1c1;
            padding: 2px 13px;
            color: #c1c1c1;
            width: 250px;
            font-size: 13px;
            cursor: pointer;
        }

        .header-area-one .header-navigation .primary-menu .location-inputs .location-inp span {
            overflow: hidden;
            width: 100%;
        }

        .dropdown-menus {
            width: 320px;
        }

        .sorry-eqip-text h4 {
            font-size: 35px;
            padding: 15px;
            background: #0e2b5c;
            color: #fff;
        }

        .d_loca {
            background-color: #3387e2;
        }

        .d_loca:hover {
            background-color: #3387e2;
        }

        .marker-label {
            background-color: #ea4335;
            color: white !important;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 80px;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        .pac-item {
            overflow: visible;
        }

        .new_date_icon {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .main-label-content {
            min-height: 48px;
            width: 220px;
            display: flex;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;
            background: rgb(255, 255, 255);
            border-radius: 10px;
        }

        .main-label-content:hover {
            box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 4px -1px, rgba(0, 0, 0, 0.1) 0px 4px 6px -1px;
            cursor: pointer;
        }

        .main-label-img {
            height: 40px;
            width: 52px;
        }

        .main-label-image-parent-div {
            width: 52px;
            height: 40px;
            display: flex;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            align-items: center;
            padding: 4px 8px;
            border-radius: 8px;
            background: rgb(255, 255, 255);
            margin-right: 8px;

        }

        .main-label-img {
            width: 100%;
            height: auto;
        }


        .main-label-img[alt] {
            font-size: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main-label-inner-content {

            font-family: "Open Sans";
            font-weight: 600;
            font-size: 12px;
            line-height: 20px;
            letter-spacing: 0.4px;
            text-decoration: none;
            font-style: normal;
            color: rgb(29, 30, 37);
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            width: 150px;
        }

        .pricing-img a {
            background-size: contain !important;
        }
    </style>
    <section class="pricing-area pricing-list-section pt-130 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sidebar-widget-area">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="widget price-range-widget mb-50">
                                    <h4 class="widget-title">{{ __('Filter Price') }}</h4>
                                    <div id="range-slider" class="mb-20"></div>
                                    <div class="price-number d-flex">
                                        <span class="text">{{ __('Price') . ' :' }}</span>
                                        <span class="amount"><input type="text" id="amount" readonly></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- code by AG start -->
                                <div class="widget radius-range-widget mb-50">
                                    <h4 class="widget-title">{{ __('Filter Radius') }}</h4>
                                    <div id="range-slider-radius" class="mb-20"></div>
                                    <div class="radius-number d-flex">
                                        <span class="text">{{ __('Radius') . ' :' }}</span>
                                        <span class="radius"><input type="text" id="radius" readonly></span>
                                        <div class="km-mile">
                                            <label>
                                                <input type="radio" name="unit-selector" value="km">
                                                <span>Km</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="unit-selector" value="mile" checked>
                                                <span>Mile</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- code by AG end -->
                            </div>
                            <div class="col-md-4">
                                <div class="widget price-range-widget mb-50">
                                    <h4 class="widget-title">{{ __('Filter Tonnage') }}</h4>
                                    <div id="range-tonnage" class="mb-20"></div>
                                    <div class="price-number d-flex">
                                        <span class="text">{{ __('Tonnage') . ' :' }}</span>
                                        <span class="amount"><input type="text" id="ton" readonly></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="equipments-search-filter mb-60">
                <div class="search-filter-form">
                    <form action="{{ route('all_equipment') }}" method="GET" id="equipmentPage_form" >
                        <div class="row">

                            <!-- code by AG start -->
                            <div class="col-lg-4 mb-2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="radius" value="25">
                                        <input type="text" name="address"
                                            value="{{ !empty(request()->input('address')) ? request()->input('address') : '' }}"
                                            id="location_field" placeholder="Enter Address"
                                            class="form_control pac-target-input">
                                        <div id="autocomplete-results" class="autocomplete-results"></div>
                                        <span class="current-location-nav" onclick="getLocation()">
                                            <i class="far fa-location" style="font-family:'Font Awesome 5 Pro'"></i>
                                        </span>
                                        <input type="hidden" name="lat" id="location_lat"
                                            value="{{ !empty(request()->input('lat')) ? request()->input('lat') : '' }}">
                                        <input type="hidden" name="long" id="location_long"
                                            value="{{ !empty(request()->input('long')) ? request()->input('long') : '' }}">
                                    </div>
                                </div>
                            </div>
                            <!-- code by AG end -->



                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form_group">
                                    <div class="input-wrap">
                                        <input type="text" name="dates" class="form_control" id="date-range"
                                            placeholder="{{ __('Search By Date') }}"
                                            value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}"
                                            readonly>
                                        <i class="far fa-calendar-alt" style="font-family:'Font Awesome 5 Pro'"></i>
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form_group">
                                    <select class="form_control" name="sort" id="sort-search">
                                        <option selected disabled>{{ __('Sort By') }}</option>
                                        <option {{ request()->input('sort') == 'new' ? 'selected' : '' }} value="new">
                                            {{ __('New Equipment') }}
                                        </option>
                                        <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
                                            {{ __('Old Equipment') }}
                                        </option>
                                        <option {{ request()->input('sort') == 'ascending' ? 'selected' : '' }}
                                            value="ascending">
                                            {{ __('Price') . ': ' . __('Ascending') }}
                                        </option>
                                        <option {{ request()->input('sort') == 'descending' ? 'selected' : '' }}
                                            value="descending">
                                            {{ __('Price') . ': ' . __('Descending') }}
                                        </option>
                                    </select>
                                </div>
                            </div>




                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form_group">
                                    <button type="submit" class="search-btn" onclick="validateMyForm(event);">{{ __('Search') }}</button>
                                </div>
                            </div>

                            <?php echo $advance_search_fields; ?>


                        </div>
                    </form>
                </div>
            </div>
            
            @if (count($allDataHere) == 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cta-content-box cta-content-box-one content-white text-center"
                            style="padding: 25px 0px;margin-bottom: 10px;background: #3387e2;">
                            <h3 class="text-white">Couldn't find what you are looking for?</h3>

                            <a href="{{ route('request_a_quote_page.page') }}" class="main-btn">Request a quote</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="row equipments-list-wrapper">
                        
                        @if (count($allDataHere) == 0)
                            <div class="row text-center mt-5">
                                <div class="col">
                                    <h4>{{ __('No Equipment Found') . '!' }}</h4>

                                </div>
                            </div>
                        @else
                            @foreach ($allDataHere as $key => $equipment)

                                <div class="col-lg-12 col-md-12 col-sm-12 mb-2 pricing-item pricing-item-three d-block" data-key="{{$key}}">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="pricing-img" style="height: 169px !important">
                                                <a style="background-size: contain !important;background-repeat: no-repeat;"
                                                    href="{{ route('equipment_details', ['slug' => $equipment['equipment_data']['slug']]) }}"
                                                    class="d-block">
                                                    @if (isset($equipment['equipment_data']['thumbnail_image']) && $equipment['equipment_data']['thumbnail_image'] != '')
                                                        <img data-src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment['equipment_data']['thumbnail_image']) }}"
                                                            alt="image" class="lazy bg-img">
                                                    @endif

                                                    @if (get_category_placeholder_image($equipment['equipment_data']['equipment_category_id']) != '')
                                                        <img data-src="{{ get_category_placeholder_image($equipment['equipment_data']['equipment_category_id']) }}"
                                                            alt="image" class="lazy bg-img">
                                                    @endif
                                                </a>


                                                @if (!empty($equipment['equipment_data']['offer']))
                                                    <span
                                                        class="discount">{{ $equipment['equipment_data']['offer'] . '% ' . __('off') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            @php
                                                $position = $currencyInfo->base_currency_symbol_position;
                                                $symbol = $currencyInfo->base_currency_symbol;
                                                $vendor_name = \App\Models\VendorInfo::where('vendor_id', $equipment['equipment_data']['vendor_id'])->first('shop_name');
                                                $equipmentDetail = \App\Models\EquipmentFieldsValue::where('equipment_id', $equipment['equipment_data']['id'])->first();
                                                $equipmentDetailData = json_decode($equipmentDetail->multiple_charges_settings);
                                            @endphp
                                            
                                            <div class="pricing-info d-flex flex-column justify-content-between" style="padding-bottom: 0px;height: 100%">
                                                <div class="pricing-body" style="padding: 0px 0px;">
                                                    <h5 class="title mb-0">
                                                        <a
                                                            href="{{ route('equipment_details', ['slug' => $equipment['equipment_data']['slug']]) }}">
                                                            {{ strlen($equipment['equipment_data']['title']) > 25 ? mb_substr($equipment['equipment_data']['title'], 0, 25, 'UTF-8') . '...' : $equipment['equipment_data']['title'] }}
                                                        </a>

                                                    </h5>
                                                    <div class="vendor-name">
                                                        @if ($equipment['equipment_data']['vendor'])
                                                            {{ __('By') }}
                                                            <a
                                                                href="{{ route('frontend.vendor.details', $equipment['equipment_data']['vendor']['username']) }}">
                                                                    {{ $equipment['equipment_data']['vendor']['username'] }}
                                                            </a>
                                                        @else
                                                            {{ __('By') }} {{ __('Admin') }}
                                                        @endif
                                                    </div>
                                                    <div class="price-option">
                                                        @if (!empty($equipment['equipment_data']['per_day_price']) && 0)
                                                            <span class="span-btn day"
                                                                style="margin-bottom: 10px !important;padding: 4px 6px;">
                                                                {{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['per_day_price'] }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Day') }}
                                                            </span>
                                                        @endif

                                                        @if (!empty($equipment['equipment_data']['per_week_price']) && 0)
                                                            <span class="span-btn week"
                                                                style="margin-bottom: 10px !important;padding: 4px 6px;">{{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['per_week_price'] }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Week') }}</span>
                                                        @endif

                                                        @if (!empty($equipment['equipment_data']['per_month_price']) && 0)
                                                            <span class="span-btn month"
                                                                style="padding: 4px 6px;">{{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['per_month_price'] }}{{ $position == 'right' ? $symbol : '' }}{{ '/' . __('Month') }}</span>
                                                        @endif
                                                    </div>


                                                </div>
                                                <div class="row">
                                                    @isset($equipmentDetailData->live_load)
                                                    <div class="col-lg-6">
                                                        <p class="m-0 p-0"><strong>Rental Days:</strong> {{$equipmentDetailData->rental_days}}</p>
                                                    </div>
                                                    @endisset
                                                    @isset($equipmentDetailData->live_load)
                                                    <div class="col-lg-6">
                                                        <p class="m-0 p-0"><strong>Live Load:</strong> {{$equipmentDetailData->live_load}}</p>
                                                    </div>
                                                    @endisset
                                                    @isset($equipmentDetailData->live_load)
                                                    <div class="col-lg-6">
                                                        <p class="m-0 p-0"><strong>Allowed Ton:</strong> {{$equipmentDetailData->allowed_ton}}</p>
                                                    </div>
                                                    @endisset
                                                    @isset($equipmentDetailData->live_load)
                                                    <div class="col-lg-6">
                                                        <p class="m-0 p-0"><strong>Daily Cost:</strong> {{$equipmentDetailData->additional_daily_cost}}</p>
                                                    </div>
                                                    @endisset
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="price-info d-flex align-items-center" style="padding: 0px 0px;width: 50%;">
                                                  
                                                        <!-- code by AG start -->
                                                        @if (is_equipment_request_for_price($equipment['equipment_data']['equipment_category_id']))
                                                            <div class="price-tag">
                                                                <h4 class="m-0 p-0">Request A Quote</h4>
                                                            </div>
                                                        @else
                                                            @if (is_equipment_multiple_charges($equipment['equipment_data']['equipment_category_id']) ||
                                                                    is_equipment_temporary_toilet_type($equipment['equipment_data']['equipment_category_id']) ||
                                                                    is_equipment_storage_container_type($equipment['equipment_data']['equipment_category_id']))
                                                                <div class="price-tag">
                                                                    @if (!empty($equipment['equipment_data']['multiple_charges_settings']['base_price']))
                                                                        <h4 class="m-0 p-0">
                                                                            {{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['multiple_charges_settings']['base_price'] }}{{ $position == 'right' ? $symbol : '' }}
                                                                        </h4>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <!-- code by AG end -->
                                                                @if (!empty($equipment['equipment_data']['lowest_price']))
                                                                    <span>{{ __('Starts From') }}</span>
                                                                @endif
    
                                                                <div class="price-tag">
                                                                    @if (!empty($equipment['equipment_data']['lowest_price']))
                                                                        <h4 class="m-0 p-0">
                                                                            {{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['lowest_price'] }}{{ $position == 'right' ? $symbol : '' }}
                                                                        </h4>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('equipment_details', ['slug' => $equipment['equipment_data']['slug']]) }}" class="booking-btn">Book Now</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php $newSerial = 0; ?>
                            @if (isset($equipment['additional_addresses']))
                                @if (count($equipment['additional_addresses']) > 0)
                                        <input type="hidden" class="vendor_lat"
                                            value="{{ $equipment['additional_addresses'][$newSerial]['latitude'] }}">
                                        <input type="hidden" class="vendor_lng"
                                            value="{{ $equipment['additional_addresses'][$newSerial]['longitude'] }}">
                                        <input type="hidden" class="equipment_title"
                                            value="{{ $equipment['equipment_data']['title'] }}">
                                        <input type="hidden" class="equipment_img"
                                            value="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment['equipment_data']['thumbnail_image']) }}">
                                        <input type="hidden" class="equipment_icon"
                                            value="https://static.vecteezy.com/system/resources/thumbnails/015/096/219/small/circle-round-icon-blue-gradient-colours-holiday-celebration-ball-design-elements-png.png">
                                        <input type="hidden" class="equipment_link"
                                            value="{{ route('equipment_details', ['slug' => $equipment['equipment_data']['slug']]) }}">
                                        <input type="hidden" class="quantity"
                                            value="{{ $equipment['equipment_data']['quantity'] }}">
                                        <input type="hidden" class="min_booking_days"
                                            value="{{ $equipment['equipment_data']['min_booking_days'] }}">
                                        <input type="hidden" class="max_booking_days"
                                            value="{{ $equipment['equipment_data']['max_booking_days'] }}">
                                            <input type="hidden" class="avgRating"
                                            value="{{ $equipment['equipment_data']['avgRating'] }}">
                                        @php
                                            $vendor_name = \App\Models\VendorInfo::where('vendor_id', $equipment['equipment_data']['vendor_id'])->first('shop_name');
                                        @endphp
                                        <input type="hidden" class="vendor_name" value="{{ $vendor_name->shop_name }}">
                                        @if (!empty($equipment['equipment_data']['multiple_charges_settings']['base_price']))
                                            <input type="hidden" class="equipment_price"
                                                value="{{ $position == 'left' ? $symbol : '' }}{{ $equipment['equipment_data']['multiple_charges_settings']['base_price'] }}{{ $position == 'right' ? $symbol : '' }}">
                                        @endif
                                        <?php $newSerial++; ?>
                                @endif
                            @endif
                            @endforeach





                          {!! $pagination_links !!}



                        @endif
                    </div>

                </div>
                <div class="col-lg-6">
                    <div style="height: calc(100vh - 116px);width: 100%;position: sticky;top: 116px;">
                        
                    <div class="border" id="map" style="height: calc(100vh - 116px);">

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Equipments section ======-->

    <form class="d-none" action="{{ route('all_equipment') }}" method="GET">
        <input type="hidden" id="keyword-id" name="keyword"
            value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">

        <input type="hidden" id="sort-id" name="sort"
            value="{{ !empty(request()->input('sort')) ? request()->input('sort') : '' }}">

        <input type="hidden" id="date-id" name="dates"
            value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">

        <input type="hidden" id="category-id" name="category"
            value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

        <input type="hidden" id="pricing-id" name="pricing"
            value="{{ !empty(request()->input('pricing')) ? request()->input('pricing') : '' }}">

        <input type="hidden" id="min-id" name="min"
            value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

        <input type="hidden" id="max-id" name="max"
            value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">
            
        <input type="hidden" id="min-tonnage-id" name="min_ton"
            value="{{ !empty(request()->input('min_ton')) ? request()->input('min_ton') : '' }}">

        <input type="hidden" id="max-tonnage-id" name="max_ton"
            value="{{ !empty(request()->input('max_ton')) ? request()->input('max_ton') : '' }}">

        <!-- code by AG start -->
        <input type="hidden" id="radius-id" name="radius"
            value="{{ !empty(request()->input('radius')) ? request()->input('radius') : '' }}">
        <input type="hidden" id="unit-id" name="unit"
            value="{{ !empty(request()->input('unit')) ? request()->input('unit') : 'mile' }}">

        <input type="hidden" id="address-id" name="address"
            value="{{ !empty(request()->input('address')) ? request()->input('address') : '' }}">
        <input type="hidden" id="lat-id" name="lat"
            value="{{ !empty(request()->input('lat')) ? request()->input('lat') : '' }}">
        <input type="hidden" id="long-id" name="long"
            value="{{ !empty(request()->input('long')) ? request()->input('long') : '' }}">
        <input type="hidden" id="page-id" name="page"
            value="{{ !empty(request()->input('page')) ? request()->input('page') : '' }}">


        <input type="hidden" id="dumpster_type-id" name="dumpster_type"
            value="{{ !empty(request()->input('dumpster_type')) ? request()->input('dumpster_type') : '' }}">
        <input type="hidden" id="dumpster_ton-id" name="dumpster_ton"
            value="{{ !empty(request()->input('dumpster_ton')) ? request()->input('dumpster_ton') : '' }}">
        <input type="hidden" id="dumpster_rentaldays-id" name="dumpster_rentaldays"
            value="{{ !empty(request()->input('dumpster_rentaldays')) ? request()->input('dumpster_rentaldays') : '' }}">


        <input type="hidden" id="container_size-id" name="container_size"
            value="{{ !empty(request()->input('container_size')) ? request()->input('container_size') : '' }}">
        <input type="hidden" id="container_rentaldays-id" name="container_rentaldays"
            value="{{ !empty(request()->input('container_rentaldays')) ? request()->input('container_rentaldays') : '' }}">


        <input type="hidden" id="toilets_type-id" name="toilets_type"
            value="{{ !empty(request()->input('toilets_type')) ? request()->input('toilets_type') : '' }}">
        <input type="hidden" id="toilets_services-id" name="toilets_services"
            value="{{ !empty(request()->input('toilets_services')) ? request()->input('toilets_services') : '' }}">
        <input type="hidden" id="toilets_rental-id" name="toilets_rental"
            value="{{ !empty(request()->input('toilets_rental')) ? request()->input('toilets_rental') : '' }}">
        <!-- code by AG end -->

        <button type="submit" id="submitBtn"></button>
    </form>

    <style>
        .widget.radius-range-widget .ui-widget.ui-widget-content {
            border: none;
            background: #DFDADA;
            height: 8px;
            border-radius: 100px;
        }

        .widget.radius-range-widget .ui-slider .ui-slider-range {
            background-color: var(--primary-color);
            border-radius: 0px;
        }

        .widget.radius-range-widget .ui-widget-content .ui-state-default {
            background: var(--primary-color);
            border: none;
            width: 15px;
            height: 15px;
            outline: none;
            border-radius: 50%;
        }

        .widget.radius-range-widget .radius-number {
            justify-content: space-between;
        }

        .widget.radius-range-widget .radius-number span {
            font-weight: 500;
            font-size: 14px;
        }

        .widget.radius-range-widget .radius-number span.text {
            width: 60%;
        }

        .widget.radius-range-widget .radius-number span.radius {
            width: 20%;
        }

        .widget.radius-range-widget .radius-number span.radius input {
            width: 100%;
            border: none;
            background-color: transparent;
            font-weight: 500;
            font-size: 14px;
            color: var(--body-color);
        }

        .km-mile input {
            display: none;
        }

        .km-mile input:checked+span {
            background: #64ad42;
            color: #fff;
        }

        .km-mile span {
            border: 1px solid #64ad42;
            display: block;
            padding: 0px 5px;
            cursor: pointer;
            margin: 0 2px;
        }

        input#radius {
            color: #000;
            font-weight: 600;
        }

        .km-mile {
            display: inline-flex;
        }
    </style>
@endsection

@section('script')
    <script>
        'use strict';
        
        function validateMyForm(event)
        {
            if($('#location_long').val() == "" || $('#location_lat').val() == ""){
                event.preventDefault();   
                alert("Please Select Autocomplete location");
            }
            
        }
        let currency_info = {!! json_encode($currencyInfo) !!};
        let position = currency_info.base_currency_symbol_position;
        let symbol = currency_info.base_currency_symbol;
        let min_price = {{ (int) $minPrice ?? 0 }};
        let max_price = {{ (int) $maxPrice ?? 100 }};
        let curr_min = {{ !empty(request()->input('min')) ? request()->input('min') : (int) $minPrice ?? 0 }};
        let curr_max = {{ !empty(request()->input('max')) ? request()->input('max') : (int) $maxPrice ?? 100 }};
        
        let min_ton = {{ (int) $minAllowedTon ?? 0 }};
        let max_ton = {{ (int) $maxAllowedTon ?? 10 }};
        let curr_min_ton = {{ !empty(request()->input('min_ton')) ? request()->input('min_ton') :  0 }};
        let curr_max_ton = {{ !empty(request()->input('max_ton')) ? request()->input('max_ton') : 10 }};

        // code by AG start
        let curr_radius = {{ !empty(request()->input('radius')) ? request()->input('radius') : 25 }};
        let curr_unit = '<?php echo !empty(request()->input('unit')) ? request()->input('unit') : 'mile'; ?>';
        console.log(curr_unit);
        // code by AG end
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/equipment.js?v=90') }}"></script>
   
    <script>
       

        var searchInput = 'location_field';

        function initialize() {
            var input = document.getElementById('location_field');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    console.error("No location data found for input.");
                    return;
                }
                document.getElementById('location_lat').value = place.geometry.location.lat();
                document.getElementById('location_long').value = place.geometry.location.lng();
            });
        }

         var markers = [];
        function initMap() {
            @if (!empty(request()->get('address')))
                var mapOptions = {
                    center: {
                        lat: {{ request()->get('lat') }},
                        lng: {{ request()->get('long') }}
                    },
                    zoom: 10,
                };
            @else
                var old_lat = parseFloat(document.getElementsByClassName('vendor_lat')[0].value);
                var old_lng = parseFloat(document.getElementsByClassName('vendor_lng')[0].value);
                var mapOptions = {
                    center: {
                        lat: old_lat,
                        lng: old_lng
                    },
                    zoom: 10,
                };
            @endif
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);
            var infowindow = new google.maps.InfoWindow();
            var locations = [];

            var vendorLatElements = document.getElementsByClassName('vendor_lat');
            var vendorLngElements = document.getElementsByClassName('vendor_lng');
            var vendorNameElements = document.getElementsByClassName('vendor_name');
            var equipmentTitleElements = document.getElementsByClassName('equipment_title');
            var equipmentImageElements = document.getElementsByClassName('equipment_img');
            var equipmentIconElements = document.getElementsByClassName('equipment_icon');
            var equipmentPriceElements = document.getElementsByClassName('equipment_price');
            var equipmentLinkElements = document.getElementsByClassName('equipment_link');
            var equipmentQuantityElements = document.getElementsByClassName('quantity');
            var equipmentMinBookingDaysElements = document.getElementsByClassName('min_booking_days');
            var equipmentMaxBookingDaysElements = document.getElementsByClassName('max_booking_days');
            var equipmentAvgRatingElements = document.getElementsByClassName('avgRating');

            for (var j = 0; j < vendorLatElements.length; j++) {
                var latitude = parseFloat(vendorLatElements[j].value);
                var longitude = parseFloat(vendorLngElements[j].value);
                var ven_name = vendorNameElements[j].value;
                var eq_title = equipmentTitleElements[j].value;
                var eq_image = equipmentImageElements[j].value;
                var eq_icon = equipmentIconElements[j].value;
                var eq_price = equipmentPriceElements[j].value;
                var eq_link = equipmentLinkElements[j].value;
                var eq_qty = equipmentQuantityElements[j].value;
                var eq_min_days = equipmentMinBookingDaysElements[j].value;
                var eq_max_days = equipmentMaxBookingDaysElements[j].value;
                var eq_avg_ratig = equipmentAvgRatingElements[j].value;

                var location = {
                    lat: latitude,
                    lng: longitude,
                    text: eq_title,
                    icon: eq_icon,
                    image: eq_image,
                    price: eq_price,
                    vendor: ven_name,
                    link: eq_link,
                    qty: eq_qty,
                    minDays: eq_min_days,
                    maxDays: eq_max_days,
                    avgRating: eq_avg_ratig
                };
                locations.push(location);
            }

            var iconSize = new google.maps.Size(32, 32);
          

            locations.forEach(function(location, index) {
                var marker = new google.maps.Marker({
                    position: {
                        lat: location.lat,
                        lng: location.lng
                    },
                    map: map,
                    icon: {
                        url: location.image,
                        scaledSize: iconSize
                    },
                    draggable: false,
                });

                var content1 = `<div class="custom-equipment-card" id="card_${index}">
        <div class="custom-equipment-image-div">
            <img class="custom-equipment-image"
                src="${location.image}"
                alt="">
        </div>
        <div class="custom-equipment-content">
            <div class="custom-content">
                <p class="equipment-card-title"><a href="${location.link}">${location.text.substring(0, 20).concat('...')}</a></p>
                <p class="equipment-card-info"><strong>Min Booking Days :</strong> ${location.minDays}</p>
                <p class="equipment-card-info"><strong>Max Booking Days :</strong> ${location.maxDays}</p>
                <p class="equipment-card-info"><strong>Qty :</strong> ${location.qty}</p>
                <p class="equipment-card-info"><strong>Avg Rating :</strong> ${location.avgRating}</p>
            </div>
            <div class="div-bottom-info">
                <div class="div-bottom-info-child">
                    <p class="equipment-price-text">${location.price} </p>
                    <p class="equipment-card-vendor"><strong>Vendor :</strong> ${location.vendor.substring(0, 15).concat('...')}</p>
                    <a class="equipment-view-detail" href="${location.link}">View Detail</a>
                </div>
            </div>
        </div>
    </div>`;

    markers.push(marker);

// Add event listener for mouseover
    marker.addListener('mouseover', function() {
        infowindow.setContent(content1);
        infowindow.open(map, marker);
    });

  

                marker.addListener('click', function() {
                    infowindow.setContent(content1);
                    infowindow.open(map, marker);
                });
            });
                simulateMarkerMouseOver(markers[0]);

        }

        // Load the Google Maps API and initialize the map
        google.maps.event.addDomListener(window, "load", function() {
            initialize();
            initMap();
        });

function simulateMarkerMouseOver(marker) {
    google.maps.event.trigger(marker, 'mouseover');
}

   $('.pricing-item-three').mouseenter(function(){
             var markerIndex = $(this).data('key');
    
    // Trigger the mouseover event for the corresponding marker
    simulateMarkerMouseOver(markers[markerIndex]);
    
    // You can perform other actions here if needed

            
        })

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            $('#location_lat').val(position.coords.latitude);
            $('#location_long').val(position.coords.longitude);
            location.latitude = position.coords.latitude;
            location.longitude = position.coords.longitude;

            var geocoder = new google.maps.Geocoder();
            var latLng = new google.maps.LatLng(location.latitude, location.longitude);

            if (geocoder) {
                geocoder.geocode({
                    'latLng': latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        console.log(results[0].formatted_address);
                        $('#location_field').val(results[0].formatted_address);
                    } else {
                        // $('#address').html('Geocoding failed: '+status);
                        console.log("Geocoding failed: " + status);
                    }
                }); //geocoder.geocode()
            }
        }
     
    </script>
    <!-- code by AG end -->
@endsection
