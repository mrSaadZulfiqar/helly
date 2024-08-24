@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Shipping Status') }}</h4>
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
        <a href="#">{{ __('Equipment Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shipping Status') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Shipping Status') }}</div>
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <!--<a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i> {{ __('Add') }}</a>-->
            
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($shippingStatus) == 0)
                <h3 class="text-center mt-2">{{ __('NO SHIPPING STATUS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Slug') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($shippingStatus as $status)

                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            {{ $status->name }}  
                          </td>
                          <td>{{ $status->slug }}</td>
                          
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#" data-toggle="modal" data-target="#editModal" data-id="{{ $status->id }}" data-name="{{ $status->name }}" data-slug="{{ $status->slug }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                              {{ __('Edit') }}
                            </a>

                            <!--<form class="deleteForm d-inline-block" action="{{ route('admin.equipment_booking.settings.delete_shippingstatus', ['id' => $status->id]) }}" method="post">-->
                            <!--  @csrf-->
                            <!--  <button type="submit" class="btn btn-danger btn-sm deleteBtn">-->
                            <!--    <span class="btn-label">-->
                            <!--      <i class="fas fa-trash"></i>-->
                            <!--    </span>-->
                            <!--    {{ __('Delete') }}-->
                            <!--  </button>-->
                            <!--</form>-->
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

  {{-- create modal --}}
  @include('backend.shipping-status.create')

  {{-- edit modal --}}
  @include('backend.shipping-status.edit')
@endsection
