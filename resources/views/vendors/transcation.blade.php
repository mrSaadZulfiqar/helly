@extends('vendors.layout')


@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Transaction') }}</h4>
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
        <a href="#">{{ __('Transaction') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Transaction') }}</div>
            </div>

            <div class="col-lg-4">
              <form action="" method="get">
                <input type="text" value="{{ request()->input('transcation_id') }}" name="transcation_id"
                  placeholder="Enter Transaction Id" class="form-control">
              </form>
            </div>

            <div class="col-lg-4 ">
              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.transcation.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($transcations) == 0)
                <h3 class="text-center mt-3">{{ __('NO TRANSACTION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Transcation Id') }}</th>
                        <th scope="col">{{ __('Transcation Type') }}</th>
                        <th scope="col">{{ __('Payment Method') }}</th>
                        <th scope="col">{{ __('Pre Balance') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('After Balance') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($transcations as $transcation)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $transcation->id }}">
                          </td>
                          <td>#{{ $transcation->transcation_id }}</td>
                          <td>
                            @if ($transcation->transcation_type == 1)
                              {{ 'Equipment Booking' }}
                            @elseif ($transcation->transcation_type == 2)
                              {{ 'Withdraw' }}
                            @elseif ($transcation->transcation_type == 3)
                              {{ 'Balance Added' }}
                            @elseif ($transcation->transcation_type == 4)
                              {{ 'Balance Subtracted' }}
                            @endif
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 2)
                              @php
                                $method = $transcation->method()->first();
                              @endphp
                              @if ($method)
                                {{ $method->name }}
                              @else
                                {{ '-' }}
                              @endif
                            @else
                              {{ $transcation->payment_method }}
                            @endif
                          </td>
                          <td>
                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->pre_balance }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>

                          <td>
                            @if ($transcation->transcation_type == 2 || $transcation->transcation_type == 4)
                              {{ '(-)' }}
                            @else
                              {{ '(+)' }}
                            @endif

                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->grand_total }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>

                          <td>
                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->after_balance }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 2)
                              @if ($transcation->payment_status == 0)
                                <span class="badge badge-warning">Pending</span>
                              @elseif ($transcation->payment_status == 1)
                                <span class="badge badge-success">Approved</span>
                              @elseif ($transcation->payment_status == 2)
                                <span class="badge badge-danger">Rejected</span>
                              @endif
                            @else
                              @if ($transcation->payment_status == 1)
                                <span class="badge badge-success">Paid</span>
                              @else
                                <span class="badge badge-danger">Unpaid</span>
                              @endif
                            @endif
                          </td>

                          <td>
                            @if ($transcation->transcation_type == 1)
                              @php
                                $booking = $transcation->booking()->first();
                              @endphp
                              @if ($booking)
                                <a target="_blank" class="btn btn-secondary btn-sm mr-1"
                                  href="{{ asset('assets/file/invoices/equipment/' . $booking->invoice) }}">
                                  <i class="fas fa-eye"></i>
                                </a>
                              @endif
                            @endif
                          </td>
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
          {{ $transcations->appends([
                  'transcation_id' => request()->input('transcation_id'),
              ])->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
