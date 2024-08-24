@extends('vendors.layout')
@section('content')
    <style>
        .inventory-card-heading {}

        .inventory-card-image {
            height: 45px;
            width: 45px;
            border-radius: 50%;
        }

        .inventory-heading {
            font-size: 17px;
            font-weight: 700;
            line-height: 25px;
        }

        .inventory-card-filters {}

        .inventory-btn-filter {
            padding: 0.4rem 1rem;

            background-color: #fff;
            color: #1D2338;
            border-radius: 20px;
            font-weight: 700;
        }

        .btn-active {
            background-color: #1D2338;
            color: #fff;
        }

        .map_parent-div {
            height: 100vh;
            width: 100%;
        }

        .inventory-list {
            padding: 0.5rem 0rem !important;
            border-bottom: 1px solid lightgray;
        }
    </style>
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

        .gm-style .gm-style-iw-c {
            padding: 0px 0px;
        }

        .gm-style-iw-d {
            overflow: hidden !important;
        }

        .gm-style .gm-style-iw-c {
            border-radius: 1.2rem 1.2rem 1.2rem 1.2rem;
        }

        .gm-ui-hover-effect {
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

        .pagination-item-active {
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

        .pagination-item:hover {
            background: #3387e2;
            color: #fff;
        }

        .pagination-item-next,
        .pagination-item-prev {
            height: 35px;
            background: #10191b;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0rem 0.2rem;
            padding: 0.4rem 1rem;
        }

        .pagination-item-next {
            border-radius: 0px 10px 10px 0px;
        }

        .pagination-item-prev {
            border-radius: 10px 0px 0px 10px;
        }

        .disabled {
            opacity: .4;
            cursor: default !important;
            pointer-events: none;
        }

        .booking-btn {
            width: 100%;
            text-align: center;
            padding: 5px 15px;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            background-color: #0E2B5C;
        }

        #tabs-nav {
            padding: 0;
            list-style: none;
            display: flex;
            padding: 10px;
            justify-content: space-around;
        }

        ul#tabs-nav li {
            flex: 50%;
            text-align: center;
            padding: 10px 0px;
            border-radius: 5px;
            cursor: pointer;
        }

        ul#tabs-nav li a {
            color: #000;
        }

        ul#tabs-nav li.active a,
        ul#tabs-nav li:hover a {
            color: #fff;
        }

        ul#tabs-nav li:hover,
        ul#tabs-nav li.active {
            background-color: #1f283e;
            flex: 50%;
        }
    </style>
    <div class="page-header">
        <h4 class="page-title">{{ __('Inventory Management') }}</h4>
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
                <a href="#">{{ __('Inventory Management') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-8">
                            <div class="border map_parent-div" id="mapa">

                            </div>
                        </div>
                        <div class="col-lg-4 m-0 p-0" style="border-left: 1px solid lightgray;">
                            <div class="inventory-card-heading">

                            </div>


                            <ul id="tabs-nav">
                                <li class="@if ($show_bookings == 'delivered') active @endif d-flex"><a class="flex-1"
                                        href="?show_booking=delivered&language={{ $_GET['language'] }}">Delivered </a></li>
                                <li class="@if ($show_bookings == 'return') active @endif d-flex"><a class="flex-1"
                                        href="?show_booking=return&language={{ $_GET['language'] }}">Return</a></li>
                            </ul>
                            <div id="tabs-content">
                                <div id="tab1" class="tab-content">
                                    <ul class="m-0 p-0 list-unstyled mt-3 inventory-ul-list">
                                        @php
                                            $old_equipment_count = 0;
                                        @endphp
                                        @foreach ($bookings as $booking)
                                        
                                            @php
                                            
                                                $equipment = \App\Models\Instrument\Equipment::find(
                                                    $booking->equipmentTitle->equipment_id,
                                                );
                                                if ($show_bookings == 'return') {
                                                    $location = \App\Models\Instrument\Location::where(
                                                        'id',
                                                        $equipment->location_id,
                                                    )->first();
                                                }
                                                $old_equipment_count++;
                                                
                                            @endphp

                                            <li class="m-0 p-0 inventory-list pricing-item-three" data-key="{{ $old_equipment_count }}">
                                                <div class="d-flex align-items-center px-3">
                                                    <img src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}"
                                                        class="inventory-card-image">
                                                    <div style="padding-left: 0.6rem">
                                                        <p class="m-0 p-0 inventory-heading">
                                                            <a target="_blank"
                                                                href="{{ route('equipment_details', $booking->equipmentTitle->slug) }}">{{ strlen($booking->equipmentTitle->title) > 20 ? mb_substr($booking->equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle->title }}</a>
                                                        </p>
                                                        <p class="m-0 p-0 inventory-location">
                                                            @php
                                                                $user = $booking->user()->first();
                                                            @endphp
                                                            @if ($user)
                                                                {{ $user->username }}
                                                            @else
                                                                {{ __('Guest') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php $newSerial = 0; ?>
                                            @if (isset($booking->lat))
                                                @if ($show_bookings == 'return')
                                                    <input type="hidden" class="vendor_lat"
                                                        value="{{ $location->latitude }}">
                                                    <input type="hidden" class="vendor_lng"
                                                        value="{{ $location->longitude }}">
                                                @elseif($show_bookings == 'delivered')
                                                    <input type="hidden" class="vendor_lat" value="{{ $booking->lat }}">
                                                    <input type="hidden" class="vendor_lng" value="{{ $booking->lng }}">
                                                @endif
                                                <input type="hidden" class="equipment_title"
                                                    value="{{ $booking->equipmentTitle->title }}">

                                                <input type="hidden" class="equipment_image"
                                                    value="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}">
                                                <input type="hidden" class="booking_number"
                                                    value="{{ $booking->booking_number }}">
                                                @php
                                                    $user = $booking->user()->first();
                                                @endphp
                                                @if ($user)
                                                    <input type="hidden" class="custumer" value="{{ $user->username }}">
                                                @else
                                                    <input type="hidden" class="custumer" value="{{ __('Guest') }}">
                                                @endif
                                                <input type="hidden" class="booking_total" value="{{ $booking->total }}">
                                                <input type="hidden" class="booking_id" value="{{ $booking->id }}">
                                                <input type="hidden" class="booking_received_amount"
                                                    value="{{ $booking->received_amount }}">
                                                <input type="hidden" class="language" value="{{ $_GET['language'] }}">
                                                @php
                                                    $vendor_name = \App\Models\VendorInfo::where(
                                                        'vendor_id',
                                                        $booking->vendor_id,
                                                    )->first('shop_name');
                                                @endphp
                                                <input type="hidden" class="vendor_name"
                                                    value="{{ $vendor_name->shop_name }}">
                                                <?php $newSerial++; ?>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="d-inline-block mx-auto mt-5">
                                {{ $bookings->appends([
                                        'booking_no' => request()->input('booking_no'),
                                        'payment_status' => request()->input('payment_status'),
                                        'shipping_type' => request()->input('shipping_type'),
                                        'shipping_status' => request()->input('shipping_status'),
                                        'language' => request()->input('language'),
                                    ])->links() }}
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12 mt-5">
                        <h4 class="page-title">{{ __('Warehouses') }}</h4>
                        <div class="row">
                            @foreach ($all_warehouses as $key => $warehouse)
                                @php
                                    $old_equipment_count = $old_equipment_count + count($warehouse['equipments']);
                                @endphp
                                <div class="col-md-3 pricing-item-three" data-key="{{ $old_equipment_count }}">
                                    <a href="#">
                                        <div class="card card-stats card-primary card-round"
                                            style="bacground: #1a2035 !important">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-stats">
                                                        <div class="numbers">
                                                            <h4 class="card-title">{{ $warehouse['warehouse']->name }}</h4>
                                                            <p class="card-category"><strong>Charge:
                                                                </strong>{{ $warehouse['warehouse']->charge }}</p>
                                                            <p class="card-category"><strong>Eqiupments:
                                                                </strong>{{ count($warehouse['equipments']) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @foreach ($warehouse['equipments'] as $equipment)
                                    @php
                                        $equipment_content = \App\Models\Instrument\EquipmentContent::where(
                                            'equipment_id',
                                            $equipment->id,
                                        )->first('title');
                                    @endphp
                                    
                                    <input type="hidden" class="vendor_lat"
                                        value="{{ $warehouse['warehouse']->latitude }}"  data-warehouse-id="{{$warehouse['warehouse']->id}}">
                                    <input type="hidden" class="vendor_lng"
                                        value="{{ $warehouse['warehouse']->longitude }}">
                                    <input type="hidden" class="equipment_title"
                                        value="{{ $warehouse['warehouse']->name }}">

                                    <input type="hidden" class="equipment_image"
                                        value="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}">
                                    <input type="hidden" class="booking_number" value="">
                                    <input type="hidden" class="custumer" value="">
                                    <input type="hidden" class="booking_total" value="">
                                    <input type="hidden" class="booking_id" value="">
                                    <input type="hidden" class="booking_received_amount" value="">
                                    <input type="hidden" class="language" value="{{ $_GET['language'] }}">
                                    @php
                                        $vendor_name = \App\Models\VendorInfo::where(
                                            'vendor_id',
                                            $equipment->vendor_id,
                                        )->first('shop_name');
                                    @endphp
                                    <input type="hidden" class="vendor_name" value="{{ $vendor_name->shop_name }}">
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
    <!--code by AG end-->
@endsection
@section('script')
    <script>
        $('.inventory-btn-filter').on("click", function() {
            $('.inventory-btn-filter').removeClass('btn-active');
            $(this).addClass('btn-active');
        });

        var markers = [];
        var currentInfowindow = null;

        function initMap() {
            var old_lat = parseFloat(document.getElementsByClassName('vendor_lat')[0].value);
            var old_lng = parseFloat(document.getElementsByClassName('vendor_lng')[0].value);
            var mapOptions = {
                center: {
                    lat: old_lat,
                    lng: old_lng
                },
                zoom: 10,
            };
            var map = new google.maps.Map(document.getElementById("mapa"), mapOptions);
            var locations = [];

            var vendorLatElements = document.getElementsByClassName('vendor_lat');
            var vendorLngElements = document.getElementsByClassName('vendor_lng');
            var vendorNameElements = document.getElementsByClassName('vendor_name');
            var equipmentTitleElements = document.getElementsByClassName('equipment_title');
            var custumer = document.getElementsByClassName('custumer');
            var bookingId = document.getElementsByClassName('booking_id');
            var bookingNumber = document.getElementsByClassName('booking_number');
            var bookingTotal = document.getElementsByClassName('booking_total');
            var bookingReceivedAmount = document.getElementsByClassName('booking_received_amount');
            var equipment_image = document.getElementsByClassName('equipment_image');

            for (var j = 0; j < vendorLatElements.length; j++) {
                var latitude = parseFloat(vendorLatElements[j].value);
                var longitude = parseFloat(vendorLngElements[j].value);
                var ven_name = vendorNameElements[j].value;
                var eq_title = equipmentTitleElements[j].value;
                var bk_num = bookingNumber[j].value;
                var customer_name = custumer[j].value;
                var bk_total = bookingTotal[j].value;
                var bk_rec_amount = bookingReceivedAmount[j].value;
                var booking_id = bookingId[j].value;
                var eq_image = equipment_image[j].value;
                let warehouse = false;
                if(vendorLatElements[j].hasAttribute("data-warehouse-id")){
                    warehouse = vendorLatElements[j].getAttribute("data-warehouse-id");
                }else{
                    warehouse = false;
                }
                var location = {
                    lat: latitude,
                    lng: longitude,
                    text: eq_title,
                    vendor: ven_name,
                    bk_num: bk_num,
                    customer_name: customer_name,
                    bk_total: bk_total,
                    bk_rec_amount: bk_rec_amount,
                    booking_id: booking_id,
                    eq_image: eq_image,
                    warehouse: warehouse
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
                        url: location.eq_image,
                        scaledSize: iconSize
                    },
                    draggable: true,
                });

                var detailUrl = "{{ route('vendor.equipment_booking.details', ['id' => ':id', 'language' => request()->input('language')]) }}";
                detailUrl = detailUrl.replace(':id', location.booking_id);
                
                if(location.warehouse) {
                    var content1 = "";
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("vendor.warehouse.equipment") }}',
                        data: {'id': location.warehouse},
                        success: function (responseData) {
                            if(responseData.data) {
                                let data = responseData.data;
                                console.log(data)
                                content1 = `<div class="custom-equipment-card" id="card_${index}">
                                    <div class="custom-equipment-image-div">
                                        <img class="custom-equipment-image"
                                            src="${location.eq_image}"
                                            alt="">
                                    </div>
                                    <div class="custom-equipment-content">
                                        <div class="custom-content">
                                            <p class="equipment-card-title"><a href="${detailUrl}">${location.text.substring(0, 20).concat('...')}</a></p>`;
                                
                                // Dynamically construct equipment info
                                for(let conindex = 0; conindex < data.length; conindex++) {
                                    content1 += `<p class="equipment-card-info"><strong>Equipment Name :</strong>${data[conindex]}</p>`;
                                }
                                
                                content1 += `<a class="equipment-view-detail" href="${detailUrl}">View Detail</a>
                                        </div>
                                        <div class="div-bottom-info">
                                        </div>
                                    </div>
                                </div>`;
                
                                // Now you can use content1 as needed, for example appending it to a container in your HTML.
                                // Example: $('#container').append(content1);
                            }
                        }
                    });
                }else{
                    var content1 = `<div class="custom-equipment-card" id="card_${index}">
                        <div class="custom-equipment-image-div">
                            <img class="custom-equipment-image"
                                src="${location.eq_image}"
                                alt="">
                        </div>
                        <div class="custom-equipment-content">
                            <div class="custom-content">
                                    <p class="equipment-card-title"><a href="${detailUrl}">${location.text.substring(0, 20).concat('...')}</a></p>
                                    ${location.bk_num ? `<p class="equipment-card-info"><strong>Booking Number :</strong> #${location.bk_num}</p>` : ''}
                                    ${location.customer_name ? `<p class="equipment-card-info"><strong>Customer :</strong> ${location.customer_name}</p>` : ''}
                                    ${location.bk_total ? `<p class="equipment-card-info"><strong>Total :</strong> $${location.bk_total}</p>` : ''}
                                    ${location.bk_rec_amount ? `<p class="equipment-card-info"><strong>Recieved Amount :</strong> $${location.bk_rec_amount}</p>` : ''}
                                    ${location.vendor.length > 15 ? `<p class="equipment-card-vendor"><strong>Vendor :</strong> ${location.vendor.substring(0, 15).concat('...')}</p>` : `<p class="equipment-card-vendor"><strong>Vendor :</strong> ${location.vendor}</p>`}
                                    <a class="equipment-view-detail" href="${detailUrl}">View Detail</a>
                                </div>
                            <div class="div-bottom-info">
                                <div class="div-bottom-info-child">
                                    <p class="equipment-card-vendor"><strong>Vendor :</strong> ${location.vendor.substring(0, 15).concat('...')}</p>
                                    <a class="equipment-view-detail" href="${detailUrl}">View Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }
                

                markers.push(marker);

                marker.addListener('mouseover', function() {
                    if (currentInfowindow) {
                        currentInfowindow.close();
                    }
                    const infowindow = new google.maps.InfoWindow({
                        content: content1
                    });
                    infowindow.open(map, marker);
                    currentInfowindow = infowindow;
                });

                marker.addListener('click', function() {
                    if (currentInfowindow) {
                        currentInfowindow.close();
                    }
                    const infowindow = new google.maps.InfoWindow({
                        content: content1
                    });
                    infowindow.open(map, marker);
                    currentInfowindow = infowindow;
                });
            });
            simulateMarkerMouseOver(markers[0]);
        }

        function simulateMarkerMouseOver(marker) {
            google.maps.event.trigger(marker, 'mouseover');
        }

        $('.pricing-item-three').mouseenter(function() {
            var markerIndex = $(this).data('key');
            for (let i = 0; i < markerIndex; i++) {
                simulateMarkerMouseOver(markers[i]);
            }
        });

        google.maps.event.addDomListener(window, "load", function() {
            initMap();
        });
    </script>
@endsection
