@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Equipment') }}</h4>
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
        <a href="#">{{ __('Equipment') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Equipment') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-3">
              <div class="card-title d-inline-block">{{ __('All Equpment') }}</div>
            </div>


            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>
            <div class="col-lg-3">
              <form id="searchForm" action="{{ route('admin.equipment_management.all_equipment') }}" method="GET">
                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                <select class="form-control select2" name="vendor"
                  onchange="document.getElementById('searchForm').submit()">
                  <option disabled>{{ __('Select a Vendor') }}</option>
                  <option value="">{{ __('All') }}</option>
                  <option {{ request()->input('vendor') == 'admin' ? 'selected' : '' }} value="admin">
                    {{ __('Admin') }}
                  </option>
                  @foreach ($vendors as $item)
                    <option value="{{ $item->id }}" {{ request()->input('vendor') == $item->id ? 'selected' : '' }}>
                      {{ $item->username }}</option>
                  @endforeach
                </select>
              </form>
            </div>

            <div class="col-lg-3  mt-2 mt-lg-0">
              <a href="{{ route('admin.equipment_management.create_equipment') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Equpment') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.equipment_management.bulk_delete_equipment') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($allEquipment) == 0)
                <h3 class="text-center mt-2">{{ __('NO EQUIPMENT FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Thumbnail Image') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Quantity') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($allEquipment as $equipment)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $equipment->id }}">
                          </td>
                          <td>
                            <img
                              src="{{ asset('assets/img/equipments/thumbnail-images/' . $equipment->thumbnail_image) }}"
                              alt="equipment image" width="40">
                          </td>
                          <td>
                            @if ($equipment->vendor)
                              <a target="_blank"
                                href="{{ route('admin.vendor_management.vendor_details', ['id' => $equipment->vendor_id, 'language' => $defaultLang->code]) }}">{{ $vendor = optional($equipment->vendor)->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>
                            <a target="_blank"
                              href="{{ route('equipment_details', $equipment->slug) }}">{{ strlen($equipment->title) > 20 ? mb_substr($equipment->title, 0, 20, 'UTF-8') . '...' : $equipment->title }}</a>
                          </td>
                          <td>{{ $equipment->categoryName }}</td>
                          <td>{{ $equipment->quantity }}</td>
                          <td>
                            <form id="featuredForm-{{ $equipment->id }}" class="d-inline-block"
                              action="{{ route('admin.equipment_management.update_featured', ['id' => $equipment->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $equipment->is_featured == 'yes' ? 'bg-success' : 'bg-danger' }}"
                                name="is_featured"
                                onchange="document.getElementById('featuredForm-{{ $equipment->id }}').submit()">
                                <option value="yes" {{ $equipment->is_featured == 'yes' ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="no" {{ $equipment->is_featured == 'no' ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1"
                              href="{{ route('admin.equipment_management.edit_equipment', ['id' => $equipment->id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.equipment_management.delete_equipment', ['id' => $equipment->id]) }}"
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
          {{ $allEquipment->appends([
                  'language' => request()->input('language'),
                  'vendor' => request()->input('vendor'),
              ])->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
