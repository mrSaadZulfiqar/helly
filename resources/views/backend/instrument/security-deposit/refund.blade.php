@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Security Deposit Refunds') }}</h4>
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
        <a href="#">{{ __('Security Deposit Refunds') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">{{ __('Security Deposit Refunds') }}</div>
            </div>

            <div class="col-lg-4 ">
              <form action="" class="d-flex" id="SearchForm" placeholder="Enter Booking Id">
                <input type="text" placeholder="Booking ID" value="{{ request()->input('booking_id') }}"
                  class="form-control" name="booking_id">
                <select name="status" id="" class="form-control"
                  onchange="document.getElementById('SearchForm').submit()">
                  <option selected value="">{{ __('Select Status') }}</option>
                  <option @selected(request()->input('status') == '0') value="0">{{ __('Pending') }}</option>
                  <option @selected(request()->input('status') == '1') value="1">{{ __('Refunded') }}</option>
                </select>
              </form>
            </div>

            <div class="col-lg-2 offset-lg-1 mt-2 mt-lg-0">

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.security-deposit.request.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($collection) == 0)
                <h3 class="text-center mt-2">{{ __('NO SECURITY DEPOSIT REFUND FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Booking Id') }}</th>
                        <th scope="col">{{ __('Customer') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
                        <th scope="col">{{ __('Refund Type') }}</th>
                        <th scope="col">{{ __('Security Deposit') }}</th>
                        <th scope="col">{{ __('Partial  Amount') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $refund)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $refund->id }}">
                          </td>
                          <td>
                            <a
                              href="{{ route('admin.equipment_booking.details', ['id' => @$refund->booking_id, 'language' => $defaultLang->code]) }}">#{{ @$refund->booking->booking_number }}</a>
                          </td>
                          <td>
                            @if ($refund->booking)
                              @if ($refund->booking->user_id == null)
                                {{ @$refund->booking->name }}
                                <br>
                                <small>{{ @$refund->booking->email }}</small>
                              @else
                                <a
                                  href="{{ route('admin.user_management.user.details', ['id' => @$refund->booking->user_id]) }}">{{ @$refund->booking->user->username }}</a>
                                <br>
                                <small>{{ @$refund->booking->user->email }}</small>
                              @endif
                            @else
                              {{ '-' }}
                            @endif

                          </td>

                          <td>
                            @if ($refund->booking)
                              @if (!is_null($refund->booking->vendor_id))
                                <a
                                  href="{{ route('admin.vendor_management.vendor_details', ['id' => @$refund->booking->vendor_id, 'language' => $defaultLang->code]) }}">{{ @$refund->booking->vendor->username }}</a>
                                <br>
                                <small>{{ @$refund->booking->vendor->email }}</small>
                              @else
                                {{ 'admin' }}
                              @endif
                            @else
                              {{ '-' }}
                            @endif


                          </td>

                          <td>
                            {{ ucfirst(str_replace('_', ' ', $refund->refund_type)) }}
                          </td>

                          <td>
                            {{ @$refund->booking->currency_symbol_position == 'left' ? @$refund->booking->currency_symbol : '' }}
                            {{ @$refund->booking->security_deposit_amount }}
                            {{ @$refund->booking->currency_symbol_position == 'right' ? @$refund->booking->currency_symbol : '' }}
                          </td>
                          <td>
                            @if ($refund->refund_type == 'partial')
                              {{ @$refund->booking->currency_symbol_position == 'left' ? @$refund->booking->currency_symbol : '' }}
                              {{ $refund->partial_amount }}
                              {{ @$refund->booking->currency_symbol_position == 'right' ? @$refund->booking->currency_symbol : '' }}
                            @else
                              {{ '-' }}
                            @endif

                          </td>
                          <td>
                            <form id="refundModalForm-{{ $refund->id }}"
                              action="{{ route('admin.security-deposit.refund-status', ['id' => $refund->id]) }}"
                              method="POST">
                              @csrf
                              <select
                                class="form-control refundModal form-control-sm @if ($refund->refund_status == 0) bg-danger  @else bg-success @endif "
                                data-id="{{ $refund->id }}"
                                data-security_deposit_amount="{{ @$refund->booking->security_deposit_amount }}"
                                name="refund_status">
                                <option value="0" @selected($refund->refund_status == 0)>
                                  {{ __('Pending') }}
                                </option>

                                <option value="1" @selected($refund->refund_status == 1)>
                                  {{ __('Refunded') }}
                                </option>
                              </select>
                            </form>
                          </td>

                          <td>
                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.security-deposit.request.delete', ['id' => $refund->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
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
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              {{ $collection->appends([
                      'booking_id' => request()->input('booking_id'),
                      'status' => request()->input('status'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @includeIf('backend.instrument.security-deposit.refund_modal')
@endsection
