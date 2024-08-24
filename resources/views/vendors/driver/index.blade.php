@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Driver') }}</h4>
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
        <a href="#">{{ __('Driver') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Driver') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Driver') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('vendors.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('vendor.driver_management.create_driver') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Driver') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.driver_management.bulk_delete_driver') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($allDriver) == 0)
                <h3 class="text-center mt-2">{{ __('NO DRIVER FOUND') . '!' }}</h3>
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
                        <th scope="col">{{ __('Phone') }}</th>
                        
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($allDriver as $driver)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $driver->id }}">
                          </td>
                          <td>{{ $driver->username }}</td>
                          <td>{{ $driver->email }}</td>
                          <td>{{ empty($driver->contact_number) ? '-' : $driver->contact_number }}</td>
                          <td>
                            
                            
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <a href="{{ route('vendor.driver_management.edit_driver', ['id' => $driver->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                 <a href="{{ route('vendor.driver_management.change_password', ['id' => $driver->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>
                                
                                

                                <form class="deleteForm d-inline-block"
                                  action="{{ route('vendor.driver_management.delete_driver', ['id' => $driver->id]) }}"
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
