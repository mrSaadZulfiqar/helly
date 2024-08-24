@extends('vendors.layout')

@section('content')
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
    
  <div class="page-header">
    <h4 class="page-title">{{ __('Charge Additional Tonnage') }}</h4>
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
        <a href="#">{{ __('Equipment Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Bookings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Charge Additional Tonnage') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @php
      $position = $details->currency_symbol_position;
      $currency = $details->currency_symbol;
    @endphp

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Booking No.') . ' ' . '#' . $details->booking_number }}
          </div>
        </div>

        <div class="card-body">
            @if($additional_tonnage_charge_rate > 0)
                <h3>Additonal Tonnage Charge Rate: <b>{{ $currency }} {{ $additional_tonnage_charge_rate }} Per Tonne </b></h3>
                <h3>Allowed Weight: <b>{{ $allowed_weight }}</b></h3>
                <form action="{{ route('vendor.equipment_booking.process_charge_additional_tonnage', $details->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Enter Weight (Tonne)</label>
                        <input required type="number" class="form-control" name="total_weight" step=".01" min="0">
                    </div>
                    
                    <div class="form-group">
                        <h3>Take Picture of Weight Proof</h3>
                        <input type="file" class="d-none" name="helly_proof_of_weight" id="helly_proof_of_weight">
                        
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
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            @else
                <h3>There is not additional tonnage charge for this booking.</h3>
            @endif
        </div>
      </div>
    </div>

   
  </div>
@endsection

@section('script')
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
		  
		  document.querySelector( "#helly_proof_of_weight" ).files = dT.files;
		  
		  $("#helly_proof_of_weight").attr('data-proofattached','yes');
		} );

		  console.log(capture_image_url);
	  });
	});
</script>
@endsection
