@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Drivers') }}</h4>
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
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Drivers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Drivers') }}</div>
              <a href="{{ route('admin.vendor_management.add_driver') }}" class="btn btn-secondary btn-sm">Add Driver</a>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.vendor_management.bulk_delete_driver') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.vendor_management.drivers') }}" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="{{ __('Search By Username or Email ID') }}"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($drivers) == 0)
                <h3 class="text-center">{{ __('NO DRIVERS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($drivers as $driver)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $driver->id }}">
                          </td>
                          <td>{{ $driver->username }}</td>
                          <td>{{ $driver->email }}</td>
                          <td>{{ empty($driver->contact_number) ? '-' : $driver->contact_number }}</td>
                          <td>
                            <form id="accountStatusForm-{{ $driver->id }}" class="d-inline-block"
                              action="{{ route('admin.vendor_management.vendor.update_account_status', ['id' => $driver->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $driver->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $driver->id }}').submit()">
                                <option value="1" {{ $driver->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $driver->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                          {{ $driver->vendor_id }}
                          </td>

                          <td>
                             <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <!-- <a href="{{ route('admin.vendor_management.driver_details', ['id' => $driver->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a> -->

                                <a href="{{ route('admin.edit_management.driver_edit', ['id' => $driver->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                 <a href="{{ route('admin.vendor_management.driver.change_password', ['id' => $driver->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.vendor_management.driver.delete', ['id' => $driver->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>

                                <!-- <a target="_blank"
                                  href="{{ route('admin.vendor_management.vendor.secret_login', ['id' => $driver->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Secret Login') }}
                                </a> -->
                              </div>
                            </div>
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
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $drivers->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('script')
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection

