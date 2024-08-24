@extends('frontend.layout')

@section('pageHeading')
{{ __('Equipment Bookings') }}
@endsection

@section('content')

<!--====== Start Equipment Bookings Section ======-->
<script>
  function checkImage(url, callback) {
        var img = new Image();
        img.onload = function () {
            callback(true);
        };
        img.onerror = function () {
            callback(false);
        };
        img.src = url;
    }
</script>
<section class="user-dashboard ">
  <div class="container-fluid">
    <div style="min-height: 100vh" class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div style="padding-block: 20px">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Booking List') }}</h4>
                  </div>

                  <div class="main-info">
                    @if (count($bookings) == 0)
                    <div class="row text-center mt-2">
                      <div class="col">
                        <h4>{{ __('No Booking Found') . '!' }}</h4>
                      </div>
                    </div>
                    @else
                    <div class="main-table">
                      <div class="table-responsive">
                        <table id="user-datatable"
                          class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                          <thead>
                            <tr>
                              <th>{{ __('Booking Number') }}</th>
                              <th>{{ __('Vendor') }}</th>
                              <th>{{ __('Equipment') }}</th>
                              <th>{{ __('Date') }}</th>
                              <th>{{ __('Payment Status') }}</th>
                              <th>Swap/Return/Relocate</th>
                              @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')
                              <th>Branch</th>
                              <th>Job Number</th>
                              <th>Po Number</th>
                              <th>Status</th>
                              @endif
                              <th>{{ __('Photos of delivery') }}</th <th>{{ __('Action') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($bookings as $booking)
                            <tr>
                              <td>{{ '#' . $booking->booking_number }}</td>
                              <td>
                                @php
                                $vendor = $booking->vendor()->first();
                                @endphp
                                @if ($vendor)
                                <a class="text-primary" target="_blank"
                                  href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ $vendor->username
                                  }}</a>
                                @else
                                <span class="badge badge-success">{{ __('Admin') }}</span>
                                @endif
                              </td>
                              <td>
                                <a class="text-primary" target="_blank"
                                  href="{{ route('equipment_details', ['slug' => $booking->equipmentInfo->slug]) }}"
                                  target="_blank">
                                  {{ strlen($booking->equipmentInfo->title) > 20 ?
                                  mb_substr($booking->equipmentInfo->title, 0, 20, 'UTF-8') . '...' :
                                  $booking->equipmentInfo->title }}
                                </a>
                              </td>
                              <td>{{ date_format($booking->created_at, 'M d, Y') }}</td>
                              <td>
                                @if ($booking->payment_status == 'completed')
                                <span class="completed {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{
                                  __('Completed') }}</span>
                                @elseif ($booking->payment_status == 'pending')
                                <span class="pending {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{
                                  __('Pending') }}</span>
                                @else
                                <span class="rejected {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{
                                  __('Rejected') }}</span>
                                @endif
                              </td>
                              <td>
                                @if($booking->shipping_status == 'delivered' || $booking->shipping_status == 'swaped' ||
                                $booking->shipping_status == 'relocated')


                                <?php if(is_equipment_temporary_toilet_type($booking->equipmentInfo->equipment_category_id)){ ?>

                                <a href="{{ route('user.equipment_booking.additional_service', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary mb-3">{{ __('Additional Service') }}</a>
                                <a href="{{ route('user.equipment_booking.return_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary mb-3">{{ __('Pickup') }}</a>
                                <a href="{{ route('user.equipment_booking.relocate_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary">{{ __('Relocate') }}</a>

                                <?php } ?>

                                <?php if(is_equipment_multiple_charges($booking->equipmentInfo->equipment_category_id)){ ?>

                                <a href="{{ route('user.equipment_booking.swap_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary mb-3">{{ __('Swap') }}</a>
                                <a href="{{ route('user.equipment_booking.return_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary mb-3">{{ __('Pickup') }}</a>
                                <a href="{{ route('user.equipment_booking.relocate_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary">{{ __('Relocate') }}</a>

                                <?php } ?>

                                <?php if(is_equipment_storage_container_type($booking->equipmentInfo->equipment_category_id)){ ?>

                                <a href="{{ route('user.equipment_booking.return_equipment', ['id' => $booking->id]) }}"
                                  class="btn btn-secondary mb-3">{{ __('Pickup') }}</a>

                                <?php } ?>

                                @endif
                              </td>
                              @if(auth()->user()->owner_id || auth()->user()->account_type == 'corperate_account')
                              @php
                              $branch = \App\Models\CompanyBranch::find($booking->branch_id);
                              @endphp
                              <td>{{ ((isset($branch->name)) ? $branch->name : '') }}</td>
                              <td>{{ $booking->job_number }}</td>
                              <td>{{ $booking->po_number }}</td>
                              <td>
                                @if($booking->return_status == 1)
                                {{ "closed" }}
                                @else
                                {{ "Open" }}
                                @endif
                              </td>
                              @endif
                              <td>
                                @php
                                $booking_updates = \App\Models\BookingUpdate::where('booking_id', $booking->id)->get();
                                @endphp
                                @if(count($booking_updates) > 0)
                                @foreach($booking_updates as $booking_update)
                                @php
                                $booking_update_details = json_decode($booking_update->update_details);
                                @endphp
                                @if($booking_update_details != "")
                                @if(gettype($booking_update_details) != "array")
                                @foreach($booking_update_details as $booking_update_detail)
                                <script>
                                  checkImage("{{$booking_update_detail}}", function (isImageValid) {
                                                                      if (isImageValid) {
                                                                          console.log("valid");
                                                                          document.getElementById("{{$booking_update_detail}}_status").classList.remove("d-none");
                                                                      } else {
                                                                          console.log("invalid");
                                                                          document.getElementById("{{$booking_update_detail}}_status").remove();
                                                                      }
                                                                  });
                                </script>
                                <img src="{{$booking_update_detail}}" style="height: 60px;width: 60px;"
                                  class="d-none image_open_full_view" id="{{$booking_update_detail}}_status">
                                @endforeach
                                @endif
                                @endif
                                @endforeach
                                @endif
                              </td>
                              <td>
                                <a href="{{ route('user.equipment_booking.details', ['id' => $booking->id]) }}"
                                  class="btn">{{ __('Details') }}</a>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--====== End Equipment Bookings Section ======-->


@endsection