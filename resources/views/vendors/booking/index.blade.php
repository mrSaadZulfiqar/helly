@extends('vendors.layout')

@section('content')
<script>
    function checkImage(url, callback) {
        var img = new Image();
        img.onload = function () {
            callback(true);
        };
        img.onerror = function () {
            callback(false);
        };
        img.src = url;
    }
</script>
    <div class="page-header">
        <h4 class="page-title">{{ __('Bookings') }}</h4>
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
                <a href="#">{{ __('Equipment Booking') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Bookings') }}</a>
            </li>
        </ul>
    </div>
    <div class="w-100 d-flex justify-content-end mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('vendor.equipment_booking.create') }}"><i class="fa fa-plus"></i>
            Booking</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!--code by AG start-->
                @if (count($declined_bookings) > 0)
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>Declined By Other Vendor</h4>

                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Booking No.') }}</th>
                                                <th scope="col">{{ __('Accept') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Customer') }}</th>
                                                <th scope="col">{{ __('Total') }}</th>
                                                <th scope="col">{{ __('Received Amount') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>

                                                @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                                                    <th scope="col">{{ __('Shipping Type') }}</th>
                                                @endif

                                                <!-- code by AG start -->
                                                <th scope="col">{{ __('Route') }}</th>
                                                <!-- code by AG end -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($declined_bookings as $booking)
                                                <tr>
                                                    <td>{{ '#' . $booking->booking_number }}</td>

                                                    <!--code by AG start-->
                                                    <td>

                                                        <form id="acceptStatusForm-{{ $booking->id }}"
                                                            class="d-inline-block"
                                                            action="{{ route('vendor.equipment_booking.select_equipment_for_accept_booking', ['id' => $booking->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <select class="form-control form-control-sm"
                                                                name="accept_status"
                                                                onchange="document.getElementById('acceptStatusForm-{{ $booking->id }}').submit()">

                                                                <option value="">Select</option>
                                                                <option value="accepted">Accept</option>
                                                                <!--<option value="decline">Decline</option>-->

                                                            </select>
                                                        </form>

                                                    </td>
                                                    <!-- code by AG end -->

                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('equipment_details', $booking->equipmentTitle->slug) }}">{{ strlen($booking->equipmentTitle->title) > 20 ? mb_substr($booking->equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle->title }}</a>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $user = $booking->user()->first();
                                                        @endphp
                                                        @if ($user)
                                                            {{ $user->username }}
                                                        @else
                                                            {{ __('Guest') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (is_null($booking->booking_type))
                                                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->grand_total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                        @else
                                                            <a href="#" class="btn btn-sm btn-primary"
                                                                data-toggle="modal"
                                                                data-target="#priceMsgModal-{{ $booking->id }}">
                                                                {{ __('Requested') }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->received_amount }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                    </td>
                                                    <td>
                                                        @if ($booking->gateway_type == 'online')
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Completed') }}</span>
                                                            </h2>
                                                        @else
                                                            @if ($booking->payment_status == 'completed')
                                                                <h2 class="d-inline-block"><span
                                                                        class="badge badge-success">{{ __('Completed') }}</span>
                                                                @elseif($booking->payment_status == 'pending')
                                                                    <h2 class="d-inline-block"><span
                                                                            class="badge badge-warning">{{ __('Pending') }}</span>
                                                                    @elseif($booking->payment_status == 'rejected')
                                                                        <h2 class="d-inline-block"><span
                                                                                class="badge badge-danger">{{ __('Rejected') }}</span>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                                                        <td>{{ ucwords($booking->shipping_method) }}</td>
                                                    @endif

                                                    <!-- code by AG start -->
                                                    <td>
                                                        <a href="{{ route('vendor.advanceroute') }}?booking={{ $booking->id }}"
                                                            class="btn btn-sm btn-primary">Route</a>
                                                    </td>
                                                    <!-- code by AG end -->

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
                <!--code by AG end-->

                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="searchForm" action="{{ route('vendor.equipment_booking.bookings') }}" method="GET">
                                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Booking Number') }}</label>
                                            <input name="booking_no" type="text" class="form-control"
                                                placeholder="{{ __('Search Here...') }}"
                                                value="{{ !empty(request()->input('booking_no')) ? request()->input('booking_no') : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Payment') }}</label>
                                            <select class="form-control h-42" name="payment_status"
                                                onchange="document.getElementById('searchForm').submit()">
                                                <option value=""
                                                    {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="completed"
                                                    {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                                                    {{ __('Completed') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                                                    {{ __('Rejected') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    @if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 1)
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>{{ __('Shipping Type') }}</label>
                                                <select class="form-control h-42" name="shipping_type"
                                                    onchange="document.getElementById('searchForm').submit()">
                                                    <option value=""
                                                        {{ empty(request()->input('shipping_type')) ? 'selected' : '' }}>
                                                        {{ __('All') }}
                                                    </option>
                                                    <!--<option value="self pickup"-->
                                                    <!--  {{ request()->input('shipping_type') == 'self pickup' ? 'selected' : '' }}>-->
                                                    <!--  {{ __('Self Pickup') }}-->
                                                    <!--</option>-->
                                                    <option value="two way delivery"
                                                        {{ request()->input('shipping_type') == 'two way delivery' ? 'selected' : '' }}>
                                                        {{ __('Pickup & Drop off') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Shipping') }}</label>
                                            <select class="form-control h-42" name="shipping_status"
                                                onchange="document.getElementById('searchForm').submit()">
                                                <option value=""
                                                    {{ empty(request()->input('shipping_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request()->input('shipping_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="taken"
                                                    {{ request()->input('shipping_status') == 'taken' ? 'selected' : '' }}>
                                                    {{ __('Taken') }}
                                                </option>
                                                <option value="delivered"
                                                    {{ request()->input('shipping_status') == 'delivered' ? 'selected' : '' }}>
                                                    {{ __('Delivered') }}
                                                </option>
                                                <option value="returned"
                                                    {{ request()->input('shipping_status') == 'returned' ? 'selected' : '' }}>
                                                    {{ __('Returned') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>{{ __('Return Status') }}</label>
                                            <select class="form-control h-42" name="return_status"
                                                onchange="document.getElementById('searchForm').submit()">
                                                <option value=""
                                                    {{ empty(request()->input('return_status')) ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="0"
                                                    {{ request()->input('return_status') == '0' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="1"
                                                    {{ request()->input('return_status') == '1' ? 'selected' : '' }}>
                                                    {{ __('Returned') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($bookings) == 0)
                                <h3 class="text-center mt-3">{{ __('NO BOOKING FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-2" style="width: max-content;">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Booking No.') }}</th>
                                                <th scope="col">{{ __('Accept') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Customer') }}</th>
                                                <th scope="col">{{ __('Total') }}</th>
                                                <th scope="col">{{ __('Received Amount') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>

                                                @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                                                    <th scope="col">{{ __('Shipping Type') }}</th>
                                                @endif


                                                <th scope="col">{{ __('Shipping Status') }}</th>
                                                <th scope="col">{{ __('Photos Of Delievery') }}</th>
                                                <th scope="col">{{ __('Return Status') }}</th>
                                                
                                                <th scope="col">{{ __('PO Number') }}</th>
                                                <th scope="col">{{ __('Job Number') }}</th>


                                                <!-- code by AG start -->
                                                <th scope="col">{{ __('Route') }}</th>
                                                <th scope="col">{{ __('Driver') }}</th>
                                                <!-- code by AG end -->

                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $serial => $booking)
                                                <tr>
                                                    <td>{{ '#' . $booking->booking_number }}</td>

                                                    <!--code by AG start-->
                                                    <td>
                                                        @if ($booking->accept_status == 'pending')
                                                            <form id="acceptStatusForm-{{ $booking->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('vendor.equipment_booking.accept_status', ['id' => $booking->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <select class="form-control form-control-sm"
                                                                    name="accept_status"
                                                                    onchange="document.getElementById('acceptStatusForm-{{ $booking->id }}').submit()">

                                                                    <option value="">Select</option>
                                                                    <option value="accepted">Accept</option>
                                                                    <option value="decline">Decline</option>

                                                                </select>
                                                            </form>
                                                        @else
                                                            {{ $booking->accept_status }}
                                                        @endif
                                                    </td>
                                                    <!-- code by AG end -->

                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('equipment_details', $booking->equipmentTitle->slug) }}">{{ strlen($booking->equipmentTitle->title) > 20 ? mb_substr($booking->equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle->title }}</a>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $user = $booking->user()->first();
                                                        @endphp
                                                        @if ($user)
                                                            {{ $user->username }}
                                                        @else
                                                            {{ __('Guest') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (is_null($booking->booking_type))
                                                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->grand_total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                        @else
                                                            <a href="#" class="btn btn-sm btn-primary"
                                                                data-toggle="modal"
                                                                data-target="#priceMsgModal-{{ $booking->id }}">
                                                                {{ __('Requested') }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->received_amount }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                                                    </td>
                                                    <td>
                                                        @if ($booking->gateway_type == 'online')
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Completed') }}</span>
                                                            </h2>
                                                        @else
                                                            @if ($booking->payment_status == 'completed')
                                                                <h2 class="d-inline-block"><span
                                                                        class="badge badge-success">{{ __('Completed') }}</span>
                                                                @elseif($booking->payment_status == 'pending')
                                                                    <h2 class="d-inline-block"><span
                                                                            class="badge badge-warning">{{ __('Pending') }}</span>
                                                                    @elseif($booking->payment_status == 'rejected')
                                                                        <h2 class="d-inline-block"><span
                                                                                class="badge badge-danger">{{ __('Rejected') }}</span>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                                                        <!--<td>{{ ucwords($booking->shipping_method) }}</td>-->
                                                        <td>Pickup & Drop off</td>
                                                    @endif


                                                    <td>
                                                        @if ($booking->accept_status == 'accepted')
                                                            <form id="shippingStatusForm-{{ $booking->id }}"
                                                                class="d-inline-block update_shipping_status_form"
                                                                action="{{ route('vendor.equipment_booking.update_shipping_status', ['id' => $booking->id]) }}"
                                                                method="post" enctype="multipart/form-data">
                                                                @csrf

                                                                <!--code by AG start-->
                                                                @if (
                                                                    $booking->shipping_status != 'delivered' &&
                                                                        $booking->shipping_status != 'swaped' &&
                                                                        $booking->shipping_status != 'pickedup_from_customer' &&
                                                                        $booking->shipping_status != 'returned' &&
                                                                        $booking->shipping_status != 'relocated')




                                                                    @if (!empty($shippingStatus))
                                                                        @php $current_status = ''; @endphp
                                                                        @foreach ($shippingStatus as $status)
                                                                            @if ($current_status != '')
                                                                                <input type="hidden"
                                                                                    name="shipping_status"
                                                                                    value="{{ $status->slug }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-primary">{{ $status->name }}</button>

                                                                                @if ($status->slug == 'delivered' || $status->slug == 'swaped')
                                                                                    <input type="file" class="d-none"
                                                                                        name="helly_proof_of_delivery"
                                                                                        id="helly_proof_of_delivery">
                                                                                @endif
                                                                            @break
                                                                        @endif

                                                                        @if ($booking->shipping_status == $status->slug)
                                                                            @php $current_status = $status->slug; @endphp
                                                                            <p class="mb-0">{{ $status->name }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                <p class="mb-0">{{ $booking->shipping_status }}</p>
                                                            @endif

                                                            <!--code by AG end-->

                                                            <!--commented by AG start-->
                                                            <!--<select-->
                                                            <!--  class="form-control form-control-sm @if ($booking->shipping_status == 'pending')
bg-warning text-dark
@elseif ($booking->shipping_status == 'delivered' || $booking->shipping_status == 'taken')
bg-primary
@else
bg-success
@endif"-->
                                                            <!--  name="shipping_status"-->
                                                            <!--  onchange="document.getElementById('shippingStatusForm-{{ $booking->id }}').submit()">-->
                                                            <!--  <option value="pending" {{ $booking->shipping_status == 'pending' ? 'selected' : '' }}>-->
                                                            <!--    {{ __('Pending') }}-->
                                                            <!--  </option>-->

                                                            <!--  @if ($booking->shipping_method == 'self pickup')
-->
                                                            <!--    <option value="taken" {{ $booking->shipping_status == 'taken' ? 'selected' : '' }}>-->
                                                            <!--      {{ __('Taken') }}-->
                                                            <!--    </option>-->
                                                        <!--  @else-->
                                                            <!--    <option value="delivered"-->
                                                            <!--      {{ $booking->shipping_status == 'delivered' ? 'selected' : '' }}>-->
                                                            <!--      {{ __('Delivered') }}-->
                                                            <!--    </option>-->
                                                            <!--
@endif-->
                                                            <!--</select>-->

                                                            <!--commented by AG end-->

                                                        </form>
                                                    @endif
                                                </td>
                                               <td>
                                                    @php
                                                        $booking_updates = \App\Models\BookingUpdate::where('booking_id', $booking->id)->get();
                                                    @endphp
                                                    @if(count($booking_updates) > 0)
                                                        @foreach($booking_updates as $booking_update)
                                                        @php
                                                            $booking_update_details = json_decode($booking_update->update_details);
                                                        @endphp
                                                            @if($booking_update_details != "")
                                                                @if(gettype($booking_update_details) != "array")
                                                                    @foreach($booking_update_details as $booking_update_detail)
                                                                        <script>
                                                                            checkImage("{{$booking_update_detail}}", function (isImageValid) {
                                                                                if (isImageValid) {
                                                                                    console.log("valid");
                                                                                    document.getElementById("{{$booking_update_detail}}_status").classList.remove("d-none");
                                                                                } else {
                                                                                    console.log("invalid");
                                                                                    document.getElementById("{{$booking_update_detail}}_status").remove();
                                                                                }
                                                                            });
                                                                        </script>
                                                                        <img src="{{$booking_update_detail}}" style="height: 60px;width: 60px;" class="d-none" id="{{$booking_update_detail}}_status">
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @endforeach 
                                                    @endif
                                               </td>
                                                <td>
                                                    @if ($booking->accept_status == 'accepted')
                                                        @if ($booking->return_status == 1)
                                                            <span class="badge badge-success">
                                                                {{ __('Yes') }}
                                                            </span>
                                                        @else
                                                            @if ($booking->security_deposit_amount > 0)
                                                                <form id="returnStatusForm-{{ $booking->id }}">
                                                                    @csrf
                                                                    <select
                                                                        class="form-control form-control-sm @if ($booking->return_status == 0) bg-danger  @else bg-success @endif returnStatus"
                                                                        name="return_status"
                                                                        data-id="{{ $booking->id }}"
                                                                        data-security_deposit_amount="{{ $booking->security_deposit_amount }}">
                                                                        <option value="1"
                                                                            @selected($booking->return_status == 1)>
                                                                            {{ __('Yes') }}
                                                                        </option>

                                                                        <option value="0"
                                                                            @selected($booking->return_status == 0)>
                                                                            {{ __('No') }}
                                                                        </option>
                                                                    </select>
                                                                </form>
                                                            @else
                                                                <form id="returnStatusForm-main{{ $booking->id }}"
                                                                    action="{{ route('vendor.equipment_booking.update_return_status', ['booking_id' => $booking->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <select
                                                                        class="form-control form-control-sm @if ($booking->return_status == 0) bg-danger  @else bg-success @endif"
                                                                        name="status"
                                                                        onchange="document.getElementById('returnStatusForm-main{{ $booking->id }}').submit()">
                                                                        <option value="1"
                                                                            @selected($booking->return_status == 1)>
                                                                            {{ __('Yes') }}
                                                                        </option>

                                                                        <option value="0"
                                                                            @selected($booking->return_status == 0)>
                                                                            {{ __('No') }}
                                                                        </option>
                                                                    </select>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ ($booking->po_number ) ? $booking->po_number : '' }}</td>
                                                <td>{{ ($booking->job_number ) ? $booking->job_number : '' }}</td>

                                                <!-- code by AG start -->
                                                <td>
                                                    <a href="{{ route('vendor.advanceroute') }}?booking={{ $booking->id }}"
                                                        class="btn btn-sm btn-primary">Route</a>
                                                </td>

                                                <td>
                                                    @if ($booking->accept_status == 'accepted')
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#createModal-{{ $serial }}"
                                                            class="btn btn-primary btn-sm float-lg-right float-left"><i
                                                                class="fas fa-edit"></i>
                                                            {{ __('Manage Driver Schedule') }}
                                                        </a>




                                                        <div class="modal fade" id="createModal-{{ $serial }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalCenterTitle"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLongTitle">
                                                                            {{ __('Driver Schedule') }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form
                                                                            id="assignDriverForm-{{ $booking->id }}"
                                                                            class="d-block"
                                                                            action="{{ route('vendor.equipment_booking.assign_driver', ['id' => $booking->id]) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="">{{ __('Select Driver') . '*' }}</label>
                                                                                <select name="driver"
                                                                                    class="form-control">
                                                                                    <option value="" disabled>
                                                                                        Select Driver</option>
                                                                                    <?php if(!empty( $drivers )){ ?>
                                                                                    <?php foreach( $drivers as $driver ){ ?>
                                                                                    <option <?php echo isset($booking->driver_id) && $booking->driver_id == $driver['id'] ? 'selected' : ''; ?>
                                                                                        value="<?php echo $driver['id']; ?>">
                                                                                        <?php echo $driver['username']; ?></option>
                                                                                    <?php } ?>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <?php 
                                                                            $driver_schedule_data = \App\Models\BookingDriverSchedule::where('booking_id', $booking->id)->first();
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="">{{ __('Date And Time') . '*' }}</label>
                                                                                <input type="datetime-local"
                                                                                    class="form-control date_and_time"
                                                                                    name="date_and_time"
                                                                                    id=""
                                                                                    value="{{ $driver_schedule_data ? $driver_schedule_data->date_and_time : ''  }}"
                                                                                    placeholder="{{ __('Enter Date And Time') }}"
                                                                                    required>
                                                                                <p id="help_date_and_time"
                                                                                    class="mt-2 mb-0 text-info em">
                                                                                    Please choose a date and time after
                                                                                    the current one. Once you select a
                                                                                    date beyond today, the 'Add' button
                                                                                    will be enabled.</p>
                                                                                @error('date_and_time')
                                                                                    <p id="err_date_and_time"
                                                                                        class="mt-2 mb-0 text-danger em">
                                                                                        Please fill this field</p>
                                                                                @enderror
                                                                            </div>






                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-dismiss="modal">
                                                                            {{ __('Close') }}
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm addbtn"
                                                                            disabled>
                                                                            {{ __('Save') }}
                                                                        </button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    @endif
                                                </td>
                                                <!-- code by AG end -->

                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            {{ __('Select') }}
                                                        </button>

                                                        <div class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <a href="{{ route('vendor.equipment_booking.edit', ['id' => $booking->id, 'language' => request()->input('language')]) }}"
                                                                class="dropdown-item">
                                                                {{ __('Edit') }}
                                                            </a>
                                                            <a href="{{ route('vendor.equipment_booking.details', ['id' => $booking->id, 'language' => request()->input('language')]) }}"
                                                                class="dropdown-item">
                                                                {{ __('Details') }}
                                                            </a>

                                                            @if (!is_null($booking->attachment))
                                                                <a href="#" class="dropdown-item"
                                                                    data-toggle="modal"
                                                                    data-target="#receiptModal-{{ $booking->id }}">
                                                                    {{ __('Receipt') }}
                                                                </a>
                                                            @endif

                                                            @if (!is_null($booking->invoice))
                                                                <a href="{{ asset('assets/file/invoices/equipment/' . $booking->invoice) }}"
                                                                    class="dropdown-item" target="_blank">
                                                                    {{ __('Invoice') }}
                                                                </a>
                                                            @endif

                                                            <!--code by AG start-->
                                                            @if ($booking->shipping_status == 'delivered' || $booking->shipping_status == 'swaped')
                                                                <a href="{{ route('vendor.equipment_booking.charge_additional_tonnage', ['id' => $booking->id]) }}"
                                                                    class="dropdown-item" target="_blank">
                                                                    {{ __('Charge Additional Tonnage') }}
                                                                </a>
                                                            @endif
                                                            <!--code by AG end-->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            @includeWhen($booking->attachment, 'vendors.booking.show-receipt')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="mt-3 text-center">
                    <div class="d-inline-block mx-auto">
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
        </div>
    </div>
</div>


@includeIf('vendors.booking.return_modal')

<!--code by AG start-->
<style>
    video#camera-stream {
        width: 300px;
        height: 300px;
        background: rgba(0, 0, 0, 0.2);
        -webkit-transform: scaleX(-1);
        /* mirror effect while using front cam */
        transform: scaleX(-1);
        /* mirror effect while using front cam */
    }

    #canvasCapture {
        width: 300px;
        height: 300px;
        -webkit-transform: scaleX(-1);
        /* mirror effect while using front cam */
        transform: scaleX(-1);
        /* mirror effect while using front cam */
    }

    div#img-preview {
        width: 33%;
        margin: 10px 0px;
    }

    div#img-preview img {
        width: 100%;
    }

    .cr-photo-capture-option {
        width: 100%;
        text-align: center;
    }
</style>
<div class="modal fade" id="proof_of_delivery_helly">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Submit image as a proof of delivery</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="cr-photo-capture-option">
                    <div class="">
                        <b>Capture Image</b><br>
                        <video id="camera-stream" class="border border-5 border-danger"></video>
                    </div>
                    <div class="">
                        <button type="button" disabled id="flip-btn" class="btn btn-sm btn-warning">
                            Flip Camera
                        </button>
                        <button type="button" id="capture-camera" class="btn btn-sm btn-primary">
                            Take Photo
                        </button>
                    </div>
                    <div class="mt-3">
                        <b></b>
                        <br>
                        <div id="img-preview" style="display:none"></div>
                        <canvas style="display:none" id="canvasCapture"
                            class="bg-light shadow border border-5 border-success">
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit-proof-and-make-delivered"
                    data-dismiss="modal">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!--code by AG end-->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.date_and_time').change(function() {
            var selectedDate = new Date($(this).val());
            var currentDate = new Date();

            if (selectedDate > currentDate) {
                $('.addbtn').prop('disabled', false);
            } else {
                $('.addbtn').prop('disabled', true);
            }
        });
        $('form.update_shipping_status_form').submit(function(event) {
            var shipping_status = $(this).find('input[name="shipping_status"]').val();

            if (shipping_status == 'delivered' || shipping_status == 'swaped') {
                var proofattached = $(this).find('#helly_proof_of_delivery').attr('data-proofattached');

                if (proofattached != 'yes') {
                    event.preventDefault();
                    $("#proof_of_delivery_helly").modal();
                    $('.submit-proof-and-make-delivered').attr('data-proofformid', $(this).attr('id'));
                }

            }

        });

        $(document).on('click', '.submit-proof-and-make-delivered', function() {
            var form_id_ = $(this).attr('data-proofformid');
            var proofattached = $('#' + form_id_).find('#helly_proof_of_delivery').attr(
                'data-proofattached');

            if (proofattached == 'yes') {
                $('#' + form_id_).submit();
            }
        });
    });
</script>
<script>
    jQuery(document).ready(function($) {
        let on_stream_video = document.querySelector('#camera-stream');
        let canvas = document.querySelector('#canvasCapture');
        // flip button element
        let flipBtn = document.querySelector('#flip-btn');

        // default user media options
        let constraints = {
            audio: false,
            video: true
        }
        let shouldFaceUser = true;

        // check whether we can use facingMode
        let supports = navigator.mediaDevices.getSupportedConstraints();
        if (supports['facingMode'] === true) {
            flipBtn.disabled = false;
        }

        let stream = null;

        function capture() {
            constraints.video = {
                width: {
                    min: 300,
                    ideal: 300,
                    max: 300,
                },
                height: {
                    min: 300,
                    ideal: 300,
                    max: 300
                },
                facingMode: shouldFaceUser ? 'user' : 'environment'
            }
            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(mediaStream) {
                    stream = mediaStream;
                    on_stream_video.srcObject = stream;
                    on_stream_video.play();
                })
                .catch(function(err) {
                    console.log(err)
                });
        }

        flipBtn.addEventListener('click', function() {
            if (stream == null) return
            // we need to flip, stop everything
            stream.getTracks().forEach(t => {
                t.stop();
            });
            // toggle / flip
            shouldFaceUser = !shouldFaceUser;
            capture();
        })

        capture();

        document.getElementById("capture-camera").addEventListener("click", function() {
            // Elements for taking the snapshot
            $('#img-preview').hide();
            $('#canvasCapture').show();
            const video = document.querySelector('video');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            var capture_image_url = canvas.toDataURL("image/png");
            // convert to Blob (async)
            canvas.toBlob((blob) => {
                const file = new File([blob], "myproof.png");
                const dT = new DataTransfer();
                dT.items.add(file);

                var form_id_ = $('.submit-proof-and-make-delivered').attr('data-proofformid');
                document.querySelector("#" + form_id_ + " #helly_proof_of_delivery").files = dT
                    .files;

                $("#" + form_id_ + " #helly_proof_of_delivery").attr('data-proofattached',
                    'yes');
            });

            console.log(capture_image_url);
        });
    });
</script>
@endsection
