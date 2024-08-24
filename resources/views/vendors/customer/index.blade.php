@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Customer') }}</h4>
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
        <a href="#">{{ __('Customer') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Customer') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Customer') }}</div>
            </div>

            <!--<div class="col-lg-3">-->
            <!--  @includeIf('vendors.partials.languages')-->
            <!--</div>-->

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('vendor.customer_management.create_customer') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Customer') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.customer_management.bulk_delete_customer') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($allCustomer) == 0)
                <h3 class="text-center mt-2">{{ __('NO CUSTOMER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('SMS') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($allCustomer as $customer)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $customer->id }}">
                          </td>
                          <td>{{ $customer->username }}</td>
                          <td>{{ $customer->email }}</td>
                          
                          <td>
                            <form id="emailStatusForm-{{ $customer->id }}" class="d-inline-block"
                              action="{{ route('vendor.customer_management.customer.update_email_status', ['id' => $customer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ !is_null($customer->email_verified_at) ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $customer->id }}').submit()">
                                <option value="verified" {{ !is_null($customer->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="not verified" {{ is_null($customer->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Not Verified') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          
                          <td>
                            <form id="accountStatusForm-{{ $customer->id }}" class="d-inline-block"
                              action="{{ route('vendor.customer_management.customer.update_account_status', ['id' => $customer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $customer->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $customer->id }}').submit()">
                                <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="2" {{ $customer->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <!--{{ empty($customer->contact_number) ? '-' : $customer->contact_number }}-->
                          <td><a href="{{ route('vendor.customer_management.calling', $customer->id) }}?phone_={{$customer->contact_number}}"><i class="fa fa-phone"></i>  </a></td>
                          
                           <td><a href="{{ route('vendor.customer_management.chat', $customer->id) }}?phone_={{$customer->contact_number}}"><i class="fa fa-comment"></i>  </a></td>
                          <td>
                              
                            <a class="btn btn-secondary btn-sm mr-1"
                              href="{{ route('vendor.customer_management.edit_customer', ['id' => $customer->id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('vendor.customer_management.delete_customer', ['id' => $customer->id]) }}"
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

        <div class="card-footer"></div>
      </div>
    </div>
  </div>
@endsection
