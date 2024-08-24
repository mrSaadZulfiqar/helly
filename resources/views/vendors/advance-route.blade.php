@extends('vendors.layout')

@section('content')
  <div class="mt-2 mb-4 text-center">
    <h2 class="pb-2 welcome">{{ __('Welcome back,') }} <b class="bold-name">{{ Auth::guard('vendor')->user()->username . '!' }}</b></h2>
  </div>
  @if (Session::get('secret_login') != 1)
    @if (Auth::guard('vendor')->user()->status == 0 && $admin_setting->vendor_admin_approval == 1)
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          {{ $admin_setting->admin_approval_notice != null ? $admin_setting->admin_approval_notice : 'Your account is deactive!' }}
        </div>
      </div>
    @endif
  @endif
<style>

@media (min-width: 992px) {
    .page-inner {
        padding-right: 4rem;
        padding-left: 4rem;
    }
}

.panel {
  border: none !important;
}
.panel-heading{
  border-bottom: none !important;
}
.panel-primary>.panel-heading {
  background-image: none !important;
  background-color: var(--primary-color) !important;
}

div#message {
    margin: 0;
}
.adp-summary, .adp-directions, .adp-stepicon, .adp-maneuver {
    color: #fff !important;
}

.adp-maneuver {
    filter: invert(1);
}
</style>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('custom-plugins/advance-route/css/bootstrap.min.css') }}">

<!-- Optional theme -->
<link rel="stylesheet" href="{{ asset('custom-plugins/advance-route/css/bootstrap-theme.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('custom-plugins/advance-route/css/style.css') }}">

<link rel="stylesheet" media="print" type="text/css" href="{{ asset('custom-plugins/advance-route/css/print.css') }}">
      
<link rel="stylesheet" type="text/css" href="{{ asset('custom-plugins/advance-route/css/accordion.css') }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="{{ asset('custom-plugins/advance-route/js/accordion.js') }}"></script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>-->



  <div class="page-header">
    <h4 class="page-title">{{ __('Route Management') }}</h4>
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
        <a href="#">{{ __('Route Management') }}</a>
      </li>
      <!-- <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Advance Route') }}</a>
      </li> -->
    </ul>
  </div>

  <div class="row route-management-box">
      @if(isset($_GET['booking']) && $_GET['booking'] != '')
      <div class="col-md-12 p-5 text-right">
          <form id="route_assign_form" method="post" action="{{ route('vendor.assign.bookingroute', $_GET['booking']) }}">
              @csrf
              <input type="hidden" name="selected_route" id="selected_route">
              <button type="submit" class="btn btn-success">Assign Route</button>
          </form>
      </div>
      @endif
    <div class="col-md-5">
        <div id="message"></div>


          <div class="panel panel-primary bg-dark2">
            <div class="panel-heading">
              <h3 class="panel-title">Define Route</h3>
            </div>
            <div class="panel-body">


              <form class="" role="form" id="multiple-destination">
                
                <div class="form-group m-0">
                  <label class="sr-only" for="start">Starting Location :</label>
                  <input type="text" class="form-control" id="start" value="{{ $vendor_location }}" placeholder="Enter Starting Location">
                </div>

                <div class="form-group text-center m-0 p-0">
                  <button type="button" class="btn btn-success btn-sm" onClick="_ZNRPL_Swap();" style='margin-top:5px;'><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></button>
                </div>

                <div class="form-group m-0">
                  <label class="sr-only" for="end">Destination Location :</label>
                  <input type="text" class="form-control" value="{{ $booking_location }}" id="end" placeholder="Enter Destination Location">
                </div>

                <div class="form-group m-0">
                  <select class="form-control" id="mode" name="mode">
                    <option value="DRIVING">Driving</option>
                    <option value="WALKING">Walking</option>
                    <option value="BICYCLING">Bicycling</option>
                    <option value="TRANSIT">Transit</option>
                  </select>
                </div>

                <div class="form-group m-0">
                  <select class="form-control" id="distance_unit" name="distance_unit">
                    <option value="KM">KM</option>
                    <option value="Miles">Miles</option>
                  </select>
                </div>

                <div class="form-group mb-0">
                <button type="button" class="btn btn-success btn-sm" onClick="_ZNRPL_Add_Element();"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>


                </div>
                

              </form>

              <div class="form-group row m-0" style="justify-content: space-around;">

                <button type="button" class="btn btn-success col-md-5 " onClick="calcRoute();" style=''>Get Shortest Route</button>

                <button type="button" class="btn btn-primary col-md-5 " id="TrafficToggle" style=''>Show/Hide Traffic</button>

              </div>

              <div class="form-group row m-0" style="justify-content: space-around;">
                <button type="button" class="btn btn-primary col-md-8 " id="WeatherToggle" style='' onclick="WeatherWidget();">Show/Hide Weather Forcast</button>

                <a href="{{ route('admin.advanceroute') }}" class="btn btn-danger col-md-2" style=''>Reset</a>

              </div>
              
            </div>
          </div>

          <div id="transit-details" class="panel panel-primary bg-dark2">
            <div class="panel-heading">
              <h3 class="panel-title">Transit Details: </h3>
            </div>
            <div class="panel-body">

              <form class="" role="form" id="transit-details">

                <table class="table table-bordered table-hover">

                  <tr><td><b>No. of Adults (<span id="adult_transit"></span>): </b></td><td><div class="form-group">
                    <label class="sr-only" for="start">No. of Adults (<span id="adult_transit"></span>):</label>
                    <input type="text" class="form-control" id="adults" placeholder="Enter No. of Adults">
                  </div></td></tr>


                  <tr><td><b>No. of Childrens (<span id="childrens_transit"></span>): </b></td><td><div class="form-group">
                    <label class="sr-only" for="start">No. of Childrens (<span id="childrens_transit"></span>):</label>
                    <input type="text" class="form-control" id="childrens" placeholder="Enter No. of Childrens">
                  </div></td></tr>


                  <tr><td><b>No. of Senior Citizens (<span id="senior_citizens_transit"></span>): </b></td><td><div class="form-group">
                    <label class="sr-only" for="start">No. of Senior Citizens (<span id="senior_citizens_transit"></span>):</label>
                    <input type="text" class="form-control" id="senior_citizens" placeholder="Enter No. of Senior Citizens">
                  </div></td></tr>

                </table>








                <button type="button" class="btn btn-success" onClick="_ZNRPL_Transit_Calculator();"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Transit Cost Calculator</button>



              </form> 

            </div>
          </div>

          <div id="fuel-calculator" class="panel panel-primary bg-dark2">
            <div class="panel-heading">
              <h3 class="panel-title">Total Fuel Cost: </h3>
            </div>
            <div class="panel-body">

              <form class="" role="form" id="fuel-calculator">

                <div class="form-group m-0">
                  <select class="form-control" id="fuel_type" name="fuel_type">
                    <option value="Gasoline">Gasoline</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Liquefied Petroleum">Liquefied Petroleum</option>
                    <option value="Compressed Natural Gas">Compressed Natural Gas</option>
                    <option value="Ethanol">Ethanol</option>
                    <option value="Bio-diesel">Bio-diesel</option>
                  </select>
                </div>

                <div class="form-group m-0">
                  <label class="sr-only" for="start">Fuel Per Liter Cost :</label>
                  <input type="text" class="form-control" id="fuel_rate" placeholder="Enter Fuel Per Liter Cost">
                </div>

                <div class="form-group m-0">
                  <label class="sr-only" for="end">Per Liter Car Mileage :</label>
                  <input type="text" class="form-control" id="mileage" placeholder="Enter Per Liter Car Mileage">
                </div>
                <div class="form-group row m-0" style="justify-content: space-around;">
                  <button type="button" class="btn btn-success col-md-12" onClick="_ZNRPL_Fuel_Calculator();"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Fuel Cost to Trip Cost</button>
                </div>


              </form> 

            </div>
          </div>

          <div id="trip_summary" class="panel panel-primary bg-dark2">
            <div class="panel-heading">
              <div class="text-left" style="float:left;padding-top:10px;"><h3 class="panel-title">Route Cost: </h3></div>

              <div class="text-right"><a href="#" class="btn btn-success text-right" onClick="printContent('trip_summary')"><i class="glyphicon glyphicon-print"></i></a></div>

            </div>
            <div class="panel-body">
              <div id="trip_cost"></div>

              <div id="fuel_cost"></div>

            </div>
          </div>

          <div id="trip_directions" class="panel panel-primary bg-dark2">
            <div class="panel-heading">

              <div class="text-left" style="float:left;padding-top:10px;"><h3 class="panel-title">Distance: <span id="total"></span></h3></div>

              <div class="text-right"><a href="#" class="btn btn-success text-right" onClick="printContent('trip_directions')"><i class="glyphicon glyphicon-print"></i></a></div>


            </div>
            <div class="panel-body">
              <div id="directionsPanel"></div>

            </div>
          </div>

        
    </div>

    <div class="col-md-7">
        
              <div id="trip_map" class="panel panel-primary bg-dark2">
                <div class="panel-heading">

                  <div class="text-left" style="float:left;padding-top:10px;"><h3 class="panel-title">Route Defined</h3></div><div class="text-right"><a href="#" class="btn btn-success text-right" onClick="printContent('trip_map')"><i class="glyphicon glyphicon-print"></i></a><span id="share"></span></div>


                </div>
                <div class="panel-body map_padding">

                  <input id="pac-input" class="controls" type="text" placeholder="Search Nearby Places">
                  <div id="map-canvas" style="height:500px;" ></div>
                </div>
              </div>

            
    </div>
  </div>

  <!-- Analytics -->

  <div id="livezilla_tracking" style="display:none"></div>
    <!-- Analytics -->
    
    <script>
        
        
        @if($booking_location != '' && $vendor_location != '')
        $(document).ready(function(){
           $('.request-loader').addClass('show');
           setTimeout(function(){ 
               $('#start').val('{{ $vendor_location }}');
               calcRoute();
               $('.request-loader').removeClass('show');
           }, 1000);
           
           $('#route_assign_form').submit(function(){
               var share_link__ = $('span#share a').attr('href');
               $('#selected_route').val(share_link__);
           });
        });
        @endif
    </script>
@endsection

@section('script')

<script src="{{ asset('custom-plugins/advance-route/js/routeplannerapi.js?v=2') }}"></script>

@endsection
