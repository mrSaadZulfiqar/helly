@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Customer Details') }}</h4>
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
        <a href="#">{{ __('Customers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Customer Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('Profile Picture') }}</div>
            </div>

            <div class="card-body text-center py-4">
              <img
                src="{{ empty($userInfo->image) ? asset('assets/img/profile.jpg') : asset('assets/img/users/' . $userInfo->image) }}"
                alt="image" width="150">
            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
              <div class="card-title">{{ __('Customer Information') }}</div>
            </div>

            <div class="card-body">
              <div class="user-information">
                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Name') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->first_name . ' ' . $userInfo->last_name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Username') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->username }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Email') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->email }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Phone') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->contact_number }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Address') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->address }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('City') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->city }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('State') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->state }}
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-2">
                    <strong>{{ __('Country') . ' :' }}</strong>
                  </div>

                  <div class="col-lg-10">
                    {{ $userInfo->country }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">{{ __('All Equipment Bookings') }}</h4>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  @if (count($bookings) == 0)
                    <h3 class="text-center mt-3">{{ __('NO BOOKING FOUND') . '!' }}</h3>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mt-2">
                        <thead>
                          <tr>
                            <th scope="col">
                              <input type="checkbox" class="bulk-check" data-val="all">
                            </th>
                            <th scope="col">{{ __('Booking No.') }}</th>
                            <th scope="col">{{ __('Title') }}</th>
                            <th scope="col">{{ __('Price') }}</th>
                            <th scope="col">{{ __('Payment Status') }}</th>

                            @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                              <th scope="col">{{ __('Shipping Type') }}</th>
                            @endif

                            <th scope="col">{{ __('Shipping Status') }}</th>
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
                                {{ strlen($booking->equipmentTitle) > 20 ? mb_substr($booking->equipmentTitle, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle }}
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
                                @if ($booking->gateway_type == 'online')
                                  <h2 class="d-inline-block"><span
                                      class="badge badge-success">{{ __('Completed') }}</span></h2>
                                @else
                                  <form id="paymentStatusForm-{{ $booking->id }}" class="d-inline-block"
                                    action="{{ route('admin.equipment_booking.update_payment_status', ['id' => $booking->id]) }}"
                                    method="post">
                                    @csrf
                                    <select
                                      class="form-control form-control-sm @if ($booking->payment_status == 'completed') bg-success @elseif ($booking->payment_status == 'pending') bg-warning text-dark @else bg-danger @endif"
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
                                @endif
                              </td>

                              @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                                <td>{{ ucwords($booking->shipping_method) }}</td>
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
                                    <option value="pending"
                                      {{ $booking->shipping_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>

                                    @if ($booking->shipping_method == 'self pickup')
                                      <option value="taken"
                                        {{ $booking->shipping_status == 'taken' ? 'selected' : '' }}>
                                        {{ __('Taken') }}
                                      </option>
                                    @else
                                      <option value="delivered"
                                        {{ $booking->shipping_status == 'delivered' ? 'selected' : '' }}>
                                        {{ __('Delivered') }}
                                      </option>
                                    @endif

                                    <option value="returned"
                                      {{ $booking->shipping_status == 'returned' ? 'selected' : '' }}>
                                      {{ __('Returned') }}
                                    </option>
                                  </select>
                                </form>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ __('Select') }}
                                  </button>

                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('admin.equipment_booking.details', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
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

                            @includeIf('backend.instrument.booking.show-message')

                            @includeIf('backend.instrument.booking.show-receipt')
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row mt-3">
                <div class="d-inline-block mx-auto">
                  {{ $bookings->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
