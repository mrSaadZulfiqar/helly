@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Quotations') }}</h4>
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
        <a href="#">{{ __('Equipment Quotation') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Quotations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form id="searchForm" action="{{ route('admin.equipment_quotations.quotations') }}" method="GET">
                
                <div class="row">
                  
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

                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right"
                data-href="{{ route('admin.equipment_quotations.bulk_delete') }}" class="card-header-button">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($quotations) == 0)
                <h3 class="text-center mt-3">{{ __('NO QUOTATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Quotation No.') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
						<th scope="col">{{ __('Equipment') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($quotations as $quotation)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $quotation->id }}">
                          </td>
                          <td>{{ '#' . $quotation->id }}</td>
						  <td>{{ $quotation->first_name.' '.$quotation->last_name }}</td>
                          <td>

                           <a target="_blank"
                              href="{{ route('equipment_details', $quotation->equipmentSlug) }}">{{ strlen($quotation->equipmentTitle) > 20 ? mb_substr($quotation->equipmentTitle, 0, 20, 'UTF-8') . '...' : $quotation->equipmentTitle }}</a>
							
						  </td>
                          <td>
                            @php
                              $vendor = $quotation->vusername;
                            @endphp
                            @if ($vendor)
                              <a target="_blank"
                                href="{{ route('admin.vendor_management.vendor_details', ['id' => $quotation->vid, 'language' => 'en']) }}">{{ $vendor }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          
                          <td>
						  {{ $quotation->email }}
                          </td>
							
							<td>
						  {{ $quotation->phone }}
                          </td>


                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.equipment_quotations.details', ['id' => $quotation->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>


                                <form class="deleteForm d-block"
                                  action="{{ route('admin.equipment_quotations.delete', ['id' => $quotation->id]) }}"
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

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
