@extends('drivers.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Bookings Assigned To You') }}</h4>
    <!--<ul class="breadcrumbs">-->
    <!--  <li class="nav-home">-->
    <!--    <a href="{{ route('driver.dashboard') }}">-->
    <!--      <i class="flaticon-home"></i>-->
    <!--    </a>-->
    <!--  </li>-->
    <!--  <li class="separator">-->
    <!--    <i class="flaticon-right-arrow"></i>-->
    <!--  </li>-->
    <!--  <li class="nav-item">-->
    <!--    <a href="#">{{ __('Equipment Booking') }}</a>-->
    <!--  </li>-->
    <!--  <li class="separator">-->
    <!--    <i class="flaticon-right-arrow"></i>-->
    <!--  </li>-->
    <!--  <li class="nav-item">-->
    <!--    <a href="#">{{ __('Bookings') }}</a>-->
    <!--  </li>-->
    <!--</ul>-->
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <form id="searchForm" action="{{ route('driver.equipment_booking.bookings') }}" method="GET">
                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Booking Number') }}</label>
                      <input name="booking_no" type="text" class="form-control"
                        placeholder="{{ __('Search Here...') }}"
                        value="{{ !empty(request()->input('booking_no')) ? request()->input('booking_no') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment') }}</label>
                      <select class="form-control h-42" name="payment_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="rejected"
                          {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  @if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 1)
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label>{{ __('Shipping Type') }}</label>
                        <select class="form-control h-42" name="shipping_type"
                          onchange="document.getElementById('searchForm').submit()">
                          <option value="" {{ empty(request()->input('shipping_type')) ? 'selected' : '' }}>
                            {{ __('All') }}
                          </option>
                          <option value="self pickup"
                            {{ request()->input('shipping_type') == 'self pickup' ? 'selected' : '' }}>
                            {{ __('Self Pickup') }}
                          </option>
                          <option value="two way delivery"
                            {{ request()->input('shipping_type') == 'two way delivery' ? 'selected' : '' }}>
                            {{ __('Two Way Delivery') }}
                          </option>
                        </select>
                      </div>
                    </div>
                  @endif

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Shipping') }}</label>
                      <select class="form-control h-42" name="shipping_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('shipping_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>

                        @if( !empty($shippingStatus) )
                        @foreach($shippingStatus as $status)
                        <option value="{{ $status->slug }}" {{ request()->input('shipping_status') == $status->slug ? 'selected' : '' }}>
                          {{ __($status->name) }}
                        </option>
                        @endforeach
                        @endif
                        
                      </select>
                    </div>
                  </div>
                  <!-- <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Return Status') }}</label>
                      <select class="form-control h-42" name="return_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('return_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="0" {{ request()->input('return_status') == '0' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="1" {{ request()->input('return_status') == '1' ? 'selected' : '' }}>
                          {{ __('Returned') }}
                        </option>
                      </select>
                    </div>
                  </div> -->
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($bookings) == 0)
                <h3 class="text-center mt-3">{{ __('NO BOOKING FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Booking No.') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Customer') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <!-- <th scope="col">{{ __('Received Amount') }}</th> -->
                        <th scope="col">{{ __('Payment Status') }}</th>

                        @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                          <th scope="col">{{ __('Shipping Type') }}</th>
                        @endif

                        <th scope="col">{{ __('Shipping Status') }}</th>
                        
                        <th scope="col">{{ __('Route') }}</th>
                        
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $booking)
                        <tr>
                          <td>{{ '#' . $booking->booking_number }}</td>
                          <td>
                            <a target="_blank"
                              href="{{ route('equipment_details', $booking->equipmentTitle->slug) }}">{{ strlen($booking->equipmentTitle->title) > 20 ? mb_substr($booking->equipmentTitle->title, 0, 20, 'UTF-8') . '...' : $booking->equipmentTitle->title }}</a>
                          </td>
                          <td>
                            @php
                              $user = $booking->user()->first();
                            @endphp
                            @if ($user)
                              {{ $user->username }}
                            @else
                              {{ __('Guest') }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($booking->booking_type))
                              {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->grand_total }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                            @else
                              <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#priceMsgModal-{{ $booking->id }}">
                                {{ __('Requested') }}
                              </a>
                            @endif
                          </td>
                          <!-- <td>
                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}{{ $booking->received_amount }}{{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                          </td> -->
                          <td>
                            @if ($booking->gateway_type == 'online')
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                              </h2>
                            @else
                              @if ($booking->payment_status == 'completed')
                                <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                                @elseif($booking->payment_status == 'pending')
                                  <h2 class="d-inline-block"><span class="badge badge-warning">{{ __('Pending') }}</span>
                                  @elseif($booking->payment_status == 'rejected')
                                    <h2 class="d-inline-block"><span
                                        class="badge badge-danger">{{ __('Rejected') }}</span>
                              @endif
                            @endif
                          </td>

                          @if ($basicData->self_pickup_status == 1 || $basicData->two_way_delivery_status == 1)
                            <td>{{ ucwords($booking->shipping_method) }}</td>
                          @endif

                          <td>
                            <form id="shippingStatusForm-{{ $booking->id }}" class="d-inline-block update_shipping_status_form"
                              action="{{ route('driver.equipment_booking.update_shipping_status', ['id' => $booking->id]) }}"
                              method="post" enctype="multipart/form-data">
                              @csrf
                                
                                
                                @if($booking->shipping_status != 'delivered' && $booking->shipping_status != 'swaped' && $booking->shipping_status != 'pickedup_from_customer' && $booking->shipping_status != 'returned' && $booking->shipping_status != 'relocated')
                                
                                    
                                
                                    
                                    @if( !empty($shippingStatus) )
                                    @php $current_status = ''; @endphp
                                    @foreach($shippingStatus as $status)
                                    @if($current_status != '')
                                    <input type="hidden" name="shipping_status" value="{{ $status->slug }}">
                                    <button type="submit" class="btn btn-sm btn-primary">{{ $status->name }}</button>
                                        
                                        @if($status->slug == 'delivered' || $status->slug == 'swaped')
                                        <input type="file" class="d-none" name="helly_proof_of_delivery" id="helly_proof_of_delivery">
                                        @endif
                                    
                                    @break
                                    @endif
                                    
                                    @if($booking->shipping_status == $status->slug)
                                         @php $current_status = $status->slug; @endphp
                                         <p class="mb-0">{{ $status->name }}</p>
                                    @endif
                                    
                                    @endforeach
                                    @endif
                                    
                                @else
                                    <p class="mb-0">{{ $booking->shipping_status }}</p>
                                @endif
                              
                                
                              
                              <!--<select-->
                              <!--  class="form-control form-control-sm @if ($booking->shipping_status == 'pending') bg-warning text-dark @elseif ($booking->shipping_status == 'delivered' || $booking->shipping_status == 'taken') bg-primary @else bg-success @endif"-->
                              <!--  name="shipping_status"-->
                              <!--  onchange="document.getElementById('shippingStatusForm-{{ $booking->id }}').submit()">-->
                                
                              <!--  @if( !empty($shippingStatus) )-->
                              <!--  @foreach($shippingStatus as $status)-->
                              <!--  <option value="{{ $status->slug }}" {{ $booking->shipping_status == $status->slug ? 'selected' : '' }}>-->
                              <!--  {{ __($status->name) }}-->
                              <!--  </option>-->
                              <!--  @endforeach-->
                              <!--  @endif-->
                              <!--</select>-->
                            </form>
                          </td>
                          
                          <td>
                              @if($booking->selected_route != '')
                              <a href="{{ $booking->selected_route }}" target="_blank" class="btn btn-sm btn-primary">Route</a>    
                              @endif
                            
                          </td>

                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('driver.equipment_booking.details', ['id' => $booking->id, 'language' => 'en']) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                <!-- @if (!is_null($booking->attachment))
                                  <a href="#" class="dropdown-item" data-toggle="modal"
                                    data-target="#receiptModal-{{ $booking->id }}">
                                    {{ __('Receipt') }}
                                  </a>
                                @endif -->

                                <!-- @if (!is_null($booking->invoice))
                                  <a href="{{ asset('assets/file/invoices/equipment/' . $booking->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif -->
                              </div>
                            </div>
                          </td>
                        </tr>

                        @includeWhen($booking->attachment, 'drivers.booking.show-receipt')
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
              {{ $bookings->appends([
                      'booking_no' => request()->input('booking_no'),
                      'payment_status' => request()->input('payment_status'),
                      'shipping_type' => request()->input('shipping_type'),
                      'shipping_status' => request()->input('shipping_status'),
                      'language' => request()->input('language'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- @includeIf('vendors.booking.return_modal') -->
  
  <style>
    video#camera-stream  {
      width: 300px;
      height: 300px;
      background: rgba(0,0,0,0.2);
      -webkit-transform: scaleX(-1); /* mirror effect while using front cam */
      transform: scaleX(-1);         /* mirror effect while using front cam */
    }
    
    #canvasCapture  {
      width: 300px;
      height: 300px;
      -webkit-transform: scaleX(-1); /* mirror effect while using front cam */
      transform: scaleX(-1);         /* mirror effect while using front cam */
    }
    div#img-preview {
        width: 33%;
        margin: 10px 0px;
    }
    
    div#img-preview img {
        width: 100%;
    }
    .cr-photo-capture-option {
        width: 100%;
        text-align: center;
    }
    </style>
  <div class="modal fade" id="proof_of_delivery_helly">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Submit image as a proof of delivery</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="cr-photo-capture-option">
				<div class="">
				<b>Capture Image</b><br>
				<video id="camera-stream" class="border border-5 border-danger"></video>
			  </div>
			  <div class="">
				<button type="button" disabled id="flip-btn" class="btn btn-sm btn-warning">
				  Flip Camera
				</button>
				<button type="button" id="capture-camera" class="btn btn-sm btn-primary">
				  Take Photo
				</button>
			  </div>
			  <div class="mt-3">
				<b></b>
				<br>
				  <div id="img-preview" style="display:none"></div>
				<canvas style="display:none" id="canvasCapture" class="bg-light shadow border border-5 border-success">
				</canvas>
			  </div>
			</div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary submit-proof-and-make-delivered" data-dismiss="modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
       $('form.update_shipping_status_form').submit(function(event){
           var shipping_status = $(this).find('input[name="shipping_status"]').val();
           
           if(shipping_status == 'delivered' || shipping_status == 'swaped'){
               var proofattached = $(this).find('#helly_proof_of_delivery').attr('data-proofattached');
           
               if(proofattached != 'yes'){
                   event.preventDefault();
                   $("#proof_of_delivery_helly").modal();
                   $('.submit-proof-and-make-delivered').attr('data-proofformid',$(this).attr('id'));
               }
               
           }
            
       });
       
       $(document).on('click','.submit-proof-and-make-delivered', function(){
           var form_id_ = $(this).attr('data-proofformid');
           var proofattached = $('#'+form_id_).find('#helly_proof_of_delivery').attr('data-proofattached');
           
           if(proofattached == 'yes'){
               $('#'+form_id_).submit();
           }
       });
    });
</script>
<script>
	jQuery(document).ready(function($){
	    let on_stream_video = document.querySelector('#camera-stream');
		let canvas = document.querySelector('#canvasCapture');
	  // flip button element
	  let flipBtn = document.querySelector('#flip-btn');

	  // default user media options
	  let constraints = { audio: false, video: true }
	  let shouldFaceUser = true;

	  // check whether we can use facingMode
	  let supports = navigator.mediaDevices.getSupportedConstraints();
	  if( supports['facingMode'] === true ) {
		flipBtn.disabled = false;
	  }

	  let stream = null;

	  function capture() {
		constraints.video = {
			width: {
			min: 300,
			ideal: 300,
			max: 300,
		  },
		  height: {
			min: 300,
			ideal: 300,
			max: 300
		  },
		  facingMode: shouldFaceUser ? 'user' : 'environment'
		}
		navigator.mediaDevices.getUserMedia(constraints)
		  .then(function(mediaStream) {
			stream  = mediaStream;
			on_stream_video.srcObject = stream;
			on_stream_video.play();
		  })
		  .catch(function(err) {
			console.log(err)
		  });
	  }

	  flipBtn.addEventListener('click', function(){
		if( stream == null ) return
		// we need to flip, stop everything
		stream.getTracks().forEach(t => {
		  t.stop();
		});
		// toggle / flip
		shouldFaceUser = !shouldFaceUser;
		capture();
	  })

	  capture();

	  document.getElementById("capture-camera").addEventListener("click", function() {
		// Elements for taking the snapshot
		$('#img-preview').hide();
		  $('#canvasCapture').show();
		  const video = document.querySelector('video');
		  canvas.width = video.videoWidth;
		  canvas.height = video.videoHeight;
		  canvas.getContext('2d').drawImage(video, 0, 0);
		  
		  var capture_image_url = canvas.toDataURL("image/png");
		  // convert to Blob (async)
		canvas.toBlob( (blob) => {
		  const file = new File( [ blob ], "myproof.png" );
		  const dT = new DataTransfer();
		  dT.items.add( file );
		  
		  var form_id_ = $('.submit-proof-and-make-delivered').attr('data-proofformid');
		  document.querySelector( "#"+form_id_+" #helly_proof_of_delivery" ).files = dT.files;
		  
		  $("#"+form_id_+" #helly_proof_of_delivery").attr('data-proofattached','yes');
		} );

		  console.log(capture_image_url);
	  });
	});
</script>
@endsection
