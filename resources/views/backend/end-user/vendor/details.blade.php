@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Vendor Details') }}</h4>
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
        <a href="#">{{ __('Registered Vendor') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="row">

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('Vendor Information') }}</div>
              <h2 class="text-center">
                @if ($vendor->photo != null)
                  <img class="vendor_photo"
                    src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="..."
                    class="uploaded-img">
                @else
                  <img class="vendor_photo" src="{{ asset('assets/img/noimage.jpg') }}"
                    alt="..." class="uploaded-img">
                @endif

              </h2>
            </div>

            <div class="card-body">
              <div class="payment-information">
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Name') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Username') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->username }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Company Name') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->shop_name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Email') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->email }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Phone') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->phone }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Country') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->country }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('City') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->city }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('State') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->state }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Zip Code') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->zip_code }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Address') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->address }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Details') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ optional($vendor->vendor_info)->details }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Balance') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                    {{ $vendor->amount != null ? $vendor->amount : '0.00' }}
                    {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-8">
            
            <!--code by AG start-->
            <div class="row">
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                          <div class="h4 card-title">{{ __('Vendor Company Information') }}</div>
                        </div>
            
                        <div class="card-body">
                            <?php if(isset($signup_equipments['equipments']) && !empty($signup_equipments['equipments'])){ ?>
                                <h3>{{ __('Equipment Types') }}</h3>
                                <?php foreach($signup_equipments['equipments'] as $equipments){ 
                                    $fields = $signup_equipments['equipments_fields'][$equipments];
                                ?>
                                    <h4>{{ $equipments }}</h4>
                                    <?php if(!empty($fields)){ ?>
                                        <ul class="list">
                                        <?php foreach($fields as $key => $field){ ?>
                                            <li>
                                                <div class="row mb-2">
                                                  <div class="col-lg-4">
                                                    <strong>{{ ucwords($key) }} :</strong>
                                                  </div>
                                                  <div class="col-lg-8">
                                                    {{ $field }}
                                                  </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    
                    
                </div>
            
            </div>
            <!--code by AG end-->
            
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-4">
                  <div class="card-title d-inline-block">{{ __('All Equipment') }}</div>
                </div>

                <div class="col-lg-3">
                  @includeIf('backend.partials.languages')
                </div>

                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                  <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                    data-href="{{ route('admin.equipment_management.bulk_delete_equipment') }}">
                    <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                  </button>
                  <a href="{{ route('admin.edit_management.vendor_edit', ['id' => $vendor->id]) }}"
                    class="btn btn-info btn-sm float-right mr-2"> <i class="fas fa-edit"></i> Edit Profile</a>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="col-lg-12">
                @if (count($allEquipment) == 0)
                  <h3 class="text-center mt-2">{{ __('NO EQUIPMENT FOUND') . '!' }}</h3>
                @else
                  <div class="table-responsive">
                    <table class="table table-striped mt-3" id="basic-datatables">
                      <thead>
                        <tr>
                          <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                          </th>
                          <th scope="col">{{ __('Thumbnail Image') }}</th>
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
        </div>
        
      </div>
      
    </div>
  @endsection
