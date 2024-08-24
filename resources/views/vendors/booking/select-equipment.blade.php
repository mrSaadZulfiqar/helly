@extends('vendors.layout')

@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Select Equipment') }}</div>
            </div>

          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('vendor.equipment_booking.accept_status_by_other', ['id' => $booking_id]) }}"
                              method="post">
                              @csrf
              @if (count($allEquipment) == 0)
                <h3 class="text-center mt-2">{{ __('NO EQUIPMENT FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          Select
                        </th>
                        <th scope="col">{{ __('Thumbnail Image') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($allEquipment as $equipment)
                        <tr>
                          <td>
                              <input type="radio" class="" name="selected_booking_equipment" value="{{ $equipment->id }}" required>
                            
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
                          
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
                <button type="submit" class="btn btn-primary">Accept</button>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>
@endsection