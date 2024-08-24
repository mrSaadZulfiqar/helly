@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Report') }}</h4>
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
        <a href="#">{{ __('Report') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form action="{{ route('vendor.equipment_booking.report') }}" method="GET">
                <div class="row no-gutters">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('From') }}</label>
                      <input name="from" type="text" class="form-control datepicker" placeholder="Select Start Date"
                        value="{{ !empty(request()->input('from')) ? request()->input('from') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('To') }}</label>
                      <input name="to" type="text" class="form-control datepicker" placeholder="Select To Date"
                        value="{{ !empty(request()->input('to')) ? request()->input('to') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Gateways') }}</label>
                      <select class="form-control h-42" name="payment_gateway">
                        <option value="" {{ empty(request()->input('payment_gateway')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>

                        @if (count($onlineGateways) > 0)
                          @foreach ($onlineGateways as $onlineGateway)
                            <option value="{{ $onlineGateway->keyword }}"
                              {{ request()->input('payment_gateway') == $onlineGateway->keyword ? 'selected' : '' }}>
                              {{ $onlineGateway->name }}
                            </option>
                          @endforeach
                        @endif

                        @if (count($offlineGateways) > 0)
                          @foreach ($offlineGateways as $offlineGateway)
                            <option value="{{ $offlineGateway->name }}"
                              {{ request()->input('payment_gateway') == $offlineGateway->name ? 'selected' : '' }}>
                              {{ $offlineGateway->name }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Status') }}</label>
                      <select class="form-control h-42" name="payment_status">
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

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Shipping Status') }}</label>
                      <select class="form-control h-42" name="shipping_status">
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
                    <button type="submit" class="btn btn-primary btn-sm ml-lg-3 card-header-button">
                      {{ __('Submit') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <a href="{{ route('vendor.equipment_booking.export_report') }}"
                class="btn btn-success btn-sm float-lg-right card-header-button">
                <i class="fas fa-file-export"></i> {{ __('Export') }}
              </a>
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
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Booking No.') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Customer Contact Number') }}</th>
                        <th scope="col">{{ __('Customer Email') }}</th>
                        <th scope="col">{{ __('Equipment') }}</th>
                        <th scope="col">{{ __('Start Date') }}</th>
                        <th scope="col">{{ __('End Date') }}</th>
                        <th scope="col">{{ __('Shipping Method') }}</th>
                        <th scope="col">{{ __('Location') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Shipping Cost') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('Grand Total') }}</th>
                        <th scope="col">{{ __('Received Amount') }}</th>
                        <th scope="col">{{ __('Commision') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Shipping Status') }}</th>
                        <th scope="col">{{ __('Booking Date') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $booking)
                        <tr>
                          <td>{{ '#' . $booking->booking_number }}</td>
                          <td>{{ $booking->name }}</td>
                          <td>{{ $booking->contact_number }}</td>
                          <td>{{ $booking->email }}</td>
                          <td>
                            {{ strlen($booking->equipmentTitle) > 20 ? mb_substr($booking->equipmentTitle, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle }}
                          </td>
                          <td>{{ $booking->startDate }}</td>
                          <td>{{ $booking->endDate }}</td>
                          <td>{{ ucwords($booking->shipping_method) }}</td>
                          <td>{{ $booking->location }}</td>
                          <td>
                            @if (is_null($booking->total))
                              {{ __('Requested') }}
                            @else
                              {{ $booking->currency_text_position == 'left' ? $booking->currency_text . ' ' : '' }}{{ $booking->total }}{{ $booking->currency_text_position == 'right' ? ' ' . $booking->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->discount))
                              -
                            @else
                              {{ $booking->currency_text_position == 'left' ? $booking->currency_text . ' ' : '' }}{{ $booking->discount }}{{ $booking->currency_text_position == 'right' ? ' ' . $booking->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->shipping_cost))
                              -
                            @else
                              {{ $booking->currency_text_position == 'left' ? $booking->currency_text . ' ' : '' }}{{ $booking->shipping_cost }}{{ $booking->currency_text_position == 'right' ? ' ' . $booking->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->tax))
                              -
                            @else
                              {{ $booking->currency_text_position == 'left' ? $booking->currency_text . ' ' : '' }}{{ $booking->tax }}{{ $booking->currency_text_position == 'right' ? ' ' . $booking->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->grand_total))
                              -
                            @else
                              {{ $booking->currency_text_position == 'left' ? $booking->currency_text . ' ' : '' }}{{ $booking->grand_total }}{{ $booking->currency_text_position == 'right' ? ' ' . $booking->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '' }}{{ $booking->received_amount }}{{ $booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '' }}
                          </td>

                          <td>
                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '' }}{{ $booking->comission }}{{ $booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '' }}
                          </td>
                          <td>
                            {{ is_null($booking->payment_method) ? '-' : $booking->payment_method }}
                          </td>
                          <td>
                            @if ($booking->payment_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @elseif ($booking->payment_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($booking->shipping_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($booking->shipping_status == 'taken')
                              <span class="badge badge-primary">{{ __('Taken') }}</span>
                            @elseif ($booking->shipping_status == 'delivered')
                              <span class="badge badge-primary">{{ __('Delivered') }}</span>
                            @else
                              <span class="badge badge-success">{{ __('Returned') }}</span>
                            @endif
                          </td>
                          <td>{{ $booking->createdAt }}</td>
                        </tr>
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
              @if (count($bookings) > 0)
                {{ $bookings->appends([
                        'from' => request()->input('from'),
                        'to' => request()->input('to'),
                        'payment_gateway' => request()->input('payment_gateway'),
                        'payment_status' => request()->input('payment_status'),
                        'shipping_status' => request()->input('shipping_status'),
                    ])->links() }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
