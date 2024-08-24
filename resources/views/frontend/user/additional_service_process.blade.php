@extends('frontend.layout')

@section('pageHeading')
  {{ __('Request Additional Services') }}
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
    <div class="container">
      <div class="row">
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
                      <h4>{{ __('Request Additional Services') }}</h4>
                    </div>
  
                    <div class="main-info text-center">
                     <h6 class="text-center mb-3">Additional Services  ${{ $additional_service_cost }} /service</h6>
                     <p></p>
                     <form method="get" action="{{ route('user.equipment_booking.additional_service', ['id' => $booking_id]) }}">
                         <div class="form_group">
                        
                            <!--code by AG start-->
                            <div class="input-wrap mb-3 relocation-field">
                                <label>Number Of Additional Services</label>
                                <input class="m-auto p-1" required type="number" min="1" step="1" id="additional_services_count" name="additional_services_count">
                                
                            </div>
                            <!--code by AG end-->
                          </div>
                         <input type="hidden" name="process_additional_service" value="true">
                         <button type="submit" class="btn btn-primary">Request</button>
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

@endsection