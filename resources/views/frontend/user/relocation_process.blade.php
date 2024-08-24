@extends('frontend.layout')

@section('pageHeading')
{{ __('Relocate Equipment') }}
@endsection

@section('content')

<style>
  input#location_field {
    height: 50px;
    font-size: 16px;
    background-color: #fff;
    border: 2px solid #dfe9f4;
    padding: 0 20px;
    border-radius: 5px;
    width: 100%;
  }

  .relocation-field i.far.fa-location {
    position: absolute;
    top: 16px;
    right: 20px;
    color: var(--primary-color);
  }
</style>
<!--====== Start Equipment Bookings Section ======-->
<section class="user-dashboard">
  <div class="container-fluid">
    
    <div style="min-height: 100vh" class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-navbar')
      </div>
      <div class="col-lg-9">
        <div style="padding-block:20px">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Relocate Equipment') }}</h4>
                  </div>
  
                  <div class="main-info text-center">
                    <h6 class="text-center mb-3">Relocation Charges ${{ $relocation_fee }}</h6>
                    <p></p>
                    <form method="get"
                      action="{{ route('user.equipment_booking.relocate_equipment', ['id' => $booking_id]) }}">
                      <div class="form_group">
  
                        <!--code by AG start-->
                        <div class="input-wrap mb-3 relocation-field">
  
                          <input required type="text" placeholder="{{ __('Enter Your Relocate Location') }}"
                            id="location_field" name="relocation_address" value="">
                          <i class="far fa-location"></i>
                          <input type="hidden" name="lat" id="location_lat" value="">
                          <input type="hidden" name="long" id="location_long" value="">
                        </div>
                        <!--code by AG end-->
                      </div>
                      <input type="hidden" name="process_relocation" value="true">
                      <button type="submit" class="btn btn-primary">Process Relocate</button>
                    </form>
  
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
@section('script')
<script>
  var searchInput = 'location_field';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('location_lat').value = near_place.geometry.location.lat();
            document.getElementById('location_long').value = near_place.geometry.location.lng();
        });
    });
</script>
@endsection