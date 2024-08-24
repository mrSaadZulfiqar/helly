@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Bookings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
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
        <a class="btn btn-primary btn-sm" href="{{ route('admin.equipment_booking.create') }}"><i class="fa fa-plus"></i>
            Booking</a>
    </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form id="searchForm" action="{{ route('admin.equipment_booking.bookings') }}" method="GET">
                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Booking Number') }}</label>
                      <input name="booking_no" type="text" class="form-control" placeholder="Search Here..."
                        value="{{ !empty(request()->input('booking_no')) ? request()->input('booking_no') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Vendors') }}</label>
                      <select class="form-control h-42 select2" name="vendor"
                        onchange="document.getElementById('searchForm').submit()">
                        <option {{ request()->input('vendor') == 'all' ? 'selected' : '' }} value="all">
                          {{ __('All') }}</option>
                        <option {{ request()->input('vendor') == 'admin' ? 'selected' : '' }} value="admin">
                          {{ __('Admin') }}
                        </option>
                        @foreach ($vendors as $item)
                          <option value="{{ $item->id }}"
                            {{ request()->input('vendor') == $item->id ? 'selected' : '' }}>
                            {{ $item->username }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment') }}</label>
                      <select class="form-control h-42" name="payment_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
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
                          <option value="" {{ empty(request()->input('shipping_type')) ? 'selected' : '' }}>
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
                        <option value="" {{ empty(request()->input('shipping_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="pending" {{ request()->input('shipping_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="taken" {{ request()->input('shipping_status') == 'taken' ? 'selected' : '' }}>
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
                        <option value="" {{ empty(request()->input('return_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="0" {{ request()->input('return_status') == '0' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="1" {{ request()->input('return_status') == '1' ? 'selected' : '' }}>
                          {{ __('Returned') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right"
                data-href="{{ route('admin.equipment_booking.bulk_delete') }}" class="card-header-button">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
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
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Booking No.') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
                        <th scope="col">{{ __('Customer') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Vendor Received') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>

                        @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                          <th scope="col">{{ __('Shipping Type') }}</th>
                        @endif

                        <th scope="col">{{ __('Shipping Status') }}</th>
                        <th scope="col">{{ __('Route') }}</th>
                        <th scope="col">{{ __('Return Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $booking)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $booking->id }}">
                          </td>
                          <td>{{ '#' . $booking->booking_number }}</td>
                          <td>

                            <a target="_blank"
                              href="{{ route('equipment_details', $booking->equipmentTitle->slug) }}">{{ strlen($booking->equipmentTitle->title) > 20 ? mb_substr($booking->equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle->title }}</a>
                          </td>
                          <td>
                            @php
                              $vendor = $booking->vendor()->first();
                            @endphp
                            @if ($vendor)
                              <a target="_blank"
                                href="{{ route('admin.vendor_management.vendor_details', ['id' => $vendor->id, 'language' => $defaultLang->code]) }}">{{ $vendor->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>
                            @php
                              $user = $booking->user()->first();
                            @endphp
                            @if ($user)
                              <a target="_blank"
                                href="{{ route('admin.user_management.user.details', ['id' => $user->id]) }}"
                                class="">{{ $user->username }}</a>
                            @else
                              <a href="javascript:void(0)" class="">{{ __('Guest') }}</a>
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->booking_type))
                              {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->grand_total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                            @else
                              <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#priceMsgModal-{{ $booking->id }}">
                                {{ __('Requested') }}
                              </a>
                            @endif
                          </td>

                          <td>
                            @if ($booking->vendor_id != null)
                              {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->received_amount }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                            @else
                              {{ '-' }}
                            @endif
                          </td>

                          <td>
                            @if ($booking->gateway_type == 'online')
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                              </h2>
                            @else
                              @if ($booking->payment_status == 'pending')
                                <form id="paymentStatusForm-{{ $booking->id }}" class="d-inline-block"
                                  action="{{ route('admin.equipment_booking.update_payment_status', ['id' => $booking->id]) }}"
                                  method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($booking->payment_status == 'completed') bg-success @elseif ($booking->payment_status == 'pending') bg-warning text-dark @elseif($booking->payment_status == 'rejected') bg-danger @endif"
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm-{{ $booking->id }}').submit()">
                                    <option value="completed"
                                      {{ $booking->payment_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="pending"
                                      {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $booking->payment_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                <span
                                  class="badge badge-{{ $booking->payment_status == 'completed' ? 'success' : 'danger' }}">{{ ucfirst($booking->payment_status) }}</span>
                              @endif
                            @endif
                          </td>

                          @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                            <!--<td>{{ ucwords($booking->shipping_method) }}</td>-->
                            <td>Pickup & Drop off</td>
                          @endif

                          <td>
                            <form id="shippingStatusForm-{{ $booking->id }}" class="d-inline-block"
                              action="{{ route('admin.equipment_booking.update_shipping_status', ['id' => $booking->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($booking->shipping_status == 'pending') bg-warning text-dark @elseif ($booking->shipping_status == 'delivered' || $booking->shipping_status == 'taken') bg-primary @else bg-success @endif"
                                name="shipping_status"
                                onchange="document.getElementById('shippingStatusForm-{{ $booking->id }}').submit()">
                                <option value="pending" {{ $booking->shipping_status == 'pending' ? 'selected' : '' }}>
                                  {{ __('Pending') }}
                                </option>

                                @if ($booking->shipping_method == 'self pickup')
                                  <option value="taken" {{ $booking->shipping_status == 'taken' ? 'selected' : '' }}>
                                    {{ __('Taken') }}
                                  </option>
                                @else
                                  <option value="delivered"
                                    {{ $booking->shipping_status == 'delivered' ? 'selected' : '' }}>
                                    {{ __('Delivered') }}
                                  </option>
                                @endif
                              </select>
                            </form>
                          </td>
                          
                           <td>
                            <a href="{{ route('admin.advanceroute') }}?booking={{ $booking->id }}" class="btn btn-sm btn-primary">Route</a>    
                          </td>

                          <td>
                            @if ($booking->security_deposit_amount > 0)
                              <form id="returnStatusForm-{{ $booking->id }}" class="d-inline-block"
                                action="{{ route('admin.equipment_booking.update_return_status2', ['id' => $booking->id]) }}"
                                method="post">
                                @csrf
                                <select
                                  class="form-control form-control-sm @if ($booking->return_status == 0) bg-danger  @else bg-success @endif returnStatus"
                                  name="return_status" data-id="{{ $booking->id }}" data-security_deposit_amount="{{ $booking->security_deposit_amount }}">
                                  <option value="1" @selected($booking->return_status == 1)>
                                    {{ __('Yes') }}
                                  </option>

                                  <option value="0" @selected($booking->return_status == 0)>
                                    {{ __('No') }}
                                  </option>
                                </select>
                              </form>
                            @else
                              <form id="returnStatusForm-main{{ $booking->id }}" class="d-inline-block"
                                action="{{ route('admin.equipment_booking.update_return_status2', ['id' => $booking->id]) }}"
                                method="post">
                                @csrf
                                <select
                                  class="form-control form-control-sm @if ($booking->return_status == 0) bg-danger  @else bg-success @endif"
                                  name="status"
                                  onchange="document.getElementById('returnStatusForm-main{{ $booking->id }}').submit()">
                                  <option value="1" @selected($booking->return_status == 1)>
                                    {{ __('Yes') }}
                                  </option>

                                  <option value="0" @selected($booking->return_status == 0)>
                                    {{ __('No') }}
                                  </option>
                                </select>
                              </form>
                            @endif
                          </td>

                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>
                    
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <a href="{{ route('admin.equipment_booking.edit', ['id' => $booking->id, 'language' => request()->input('language')]) }}"
                                        class="dropdown-item">
                                        {{ __('Edit') }}
                                    </a>
                                <a href="{{ route('admin.equipment_booking.details', ['id' => $booking->id, 'language' => request()->input('language')]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                @if (!is_null($booking->attachment))
                                  <a href="#" class="dropdown-item" data-toggle="modal"
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

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.equipment_booking.delete', ['id' => $booking->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @includeWhen($booking->price_message, 'backend.instrument.booking.show-message')

                        @includeWhen($booking->attachment, 'backend.instrument.booking.show-receipt')
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
  @includeIf('backend.instrument.booking.return_modal')
@endsection
