// ROUTE PLANNER : GOOGLE MAPS CUSTOM JAVASCRIPT Ver. 7.0
// WRITTEN BY : ZINCKSOFT.COM
// EMAIL : INFO@ZINCKSOFT.COM / ZINCKSOFT@GMAIL.COM
// WRITTEN FOR : CODECANYON
// DATED : 27/12/2016


// NAMESPACE
var _ZNRPL = {
latitude : 41.850033,
longitude : -87.6500523,
start:"",
end:"",
// enable get geo loacation from browser
getgeo:true,
// enable adsense settings
adsense:false,
publisherid:"ca-google-maps_apidocs",
adformat : "BANNER",
adposition : "RIGHT_BOTTOM",
adbackgroundColor: '#c4d4f3',
adborderColor: '#e5ecf9',
adtitleColor: '#0000cc',
adtextColor: '#000000',
adurlColor: '#009900',
map:"",
adUnit:"",
directionsDisplay:"",
directionsService:"",
distance_unit:"KM",
// Fuel Calculator Settings
enable_pricing:true,
unit_price:0.14,
currency_symbole:"$",
trip_distance:0,
fuel_calculator:true,
// Transit Default Cost
default_adult_cost:2,
per_km_adult_cost:2,
default_children_cost:2,
per_km_children_cost:2,
default_senior_citizen:2,
per_km_senior_citizen:2,

// Weather Widget Data
forcast:[],
enable_weather:1,
measurement:"F" // You Can Change to Fahrenheit (F), Celsius (C) and Kelvin (K) units    
};

// Language Namespace for translations
var _ZNRPL_LANG = {

    no_result_found:"No results found",
    geo_failed_error:"Geocoder failed due to:",
    next_location:"Next Location",
    enter_via_location:"Enter Via Location",
    humidity:"Humidity",
    infowindow_address:"Address",
    poi_nothing_found:"Sorry, nothing is found",
    poi_address:"Address : ",
    poi_phone:"Contact No. : ",
    poi_url:"Website : ",
    poi_rating:"Ratings : ",
    fuel_type_validation:"Please Select Fuel Type.",
    fuel_per_liter_price_validation:"Please Enter Fuel Per Liter Price.",
    fuel_mileage_validation:"Please Enter Per Liter Mileage of Vehical.",
    fuel_numeric_price_validation:"Please Enter Numeric Fuel Per Liter Price.",
    fuel_numeric_mileage_validation:"Please Enter Numeric Per Liter Mileage of Vehical.",
    fuel_get_route_first_validation:"Please Get Shortest Route First to Calculate Fuel Cost.",
    fuel_total_distance:"Total Distance",
    fuel_total_fuel_cost:"Total Trip Fuel Cost",
    fuel_total_needed:"Total Fuel Needed",
    fuel_total_cost:"Cost",
    transit_basic_rate:"Basic Rate",
    transit_adult_numeric_validation:"Please Enter Numeric No. of Adults.",
    transit_child_numeric_validation:"Please Enter Numeric No. of Childrens.",
    transit_senior_numeric_validation:"Please Enter Numeric No. of Senior Citizens.",
    transit_get_route_first_validation:"Please Get Shortest Route First to Transit Cost.",
    transit_total_distance:"Total Distance",
    transit_total_cost:"Total Transit Cost",
    transit_total_adult_cost:"Total Adult Cost",
    transit_total_children_cost:"Total Childrens Cost",
    transit_total_senior_cost:"Total Senior Citizens Cost"
    
    
};

var intTextBox = 0;
var waypoints = [];

// WEATHER WIDGET
var weatherData =[];

$(document).ready( function() {
    
    //hide transit calculator
    $("#transit-details").hide();
    
    //Show default transit values
    _ZNRPL_Transit_Prices();

    if (_ZNRPL.getgeo == true)
    {
      navigator.geolocation.getCurrentPosition(handle_geolocation_query);
        
      function handle_geolocation_query(position){
            
          _ZNRPL.latitude = position.coords.latitude;
          
          _ZNRPL.longitude = position.coords.longitude;
          
          _ZNRPL_Get_Address(_ZNRPL.latitude,_ZNRPL.longitude);
          
        }
        
    }
    
    if(_ZNRPL.start.length != 0)
        {
            document.getElementById("start").value = _ZNRPL.start;
        }
    
    if(_ZNRPL.end.length != 0)
        {
            document.getElementById("end").value = _ZNRPL.end;
        }
    
    if(_ZNRPL.fuel_calculator == false)
        {
            $("#fuel-calculator").hide();
        }
    
    
    // Hide fuel calculator on walking / cycling mode
    $("#mode").change(function(){
        
        var Selected_Mode = $("#mode").val();
        
        if(_ZNRPL.fuel_calculator == true && (Selected_Mode == "WALKING" || Selected_Mode == "BICYCLING"))
            {
               $("#fuel-calculator").hide(); 
                $("#trip_summary").hide();
            }
        else if(_ZNRPL.fuel_calculator == true && (Selected_Mode != "WALKING" || Selected_Mode != "BICYCLING"))
        {
            $("#fuel-calculator").show();
            $("#trip_summary").show();
            
        }
        
        if(Selected_Mode == "TRANSIT")
            {
                $("#transit-details").show();
                $("#fuel-calculator").hide(); 
            }
        else{
            $("#transit-details").hide();
        }
        
     });
    
    
});


function _ZNRPL_Get_Address(lat,lng)
{
     geocoder = new google.maps.Geocoder();
    
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
         
            document.getElementById('start').value = results[0].formatted_address;


        } else {
          
        document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.no_result_found+"</div>";
        
        }
      } else {
        
          document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+ _ZNRPL_LANG.geo_failed_error + status + "</div>";
        
      }
    });
    
}




//FUNCTION TO SWAP START AND END LOCATION
function _ZNRPL_Swap()
{
    var temp;
    temp = document.getElementById("end").value;
    document.getElementById("end").value = document.getElementById("start").value;
    document.getElementById("start").value = temp;
}

//FUNCTION TO ADD TEXT BOX ELEMENT
function _ZNRPL_Add_Element() {
intTextBox = intTextBox + 1;

var contentID = document.getElementById('multiple-destination');
var newTBDiv = document.createElement('div');
newTBDiv.setAttribute(
'id', 'strText' + intTextBox);

newTBDiv.innerHTML =
"<div style='margin-top:5px;margin-bottom:5px;'><div class='form-group'><button type='button' class='btn btn-primary' onClick='_ZNRPL_Sort_Element(" + intTextBox + ",0);'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></button><button type='button' class='btn btn-primary' onClick='_ZNRPL_Sort_Element(" + intTextBox + ",1);'style='margin-right:5px;margin-left:5px;'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></button><label class='sr-only' for='start'>"+_ZNRPL_LANG.next_location+" :</label><input type='text' class='form-control' id='start" + intTextBox + "' placeholder='"+_ZNRPL_LANG.enter_via_location+"'></div><button type='button' class='btn btn-success' onClick='_ZNRPL_Add_Element();'style='margin-right:5px;margin-left:5px;'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></button><button type='button' class='btn btn-danger' onClick='_ZNRPL_Remove_Element();'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></button></div>";
contentID.appendChild(newTBDiv);
    
for (i = 0; i < intTextBox; i++) 
{ 
  j = i +1;
  var input = document.getElementById('start'+j);
  var searchBox = new google.maps.places.Autocomplete(input);
    
}
    
}

//FUNCTION TO SORT TEXTBOX ELEMENTS
function _ZNRPL_Sort_Element(Text_Element,Sort_Type){
    var textbox_id,value1,value2;
        
    if(Sort_Type == 0)
    {
      if(Text_Element == 1)
    {
       textbox_id = "end"; 
    }
    else
    {
       textbox_id = "start" + parseInt(Text_Element -1);   
    }
           
    }
    else
    {
      textbox_id = "start" + parseInt(Text_Element +1);      
    }
    
    value1 = document.getElementById(textbox_id).value;
    value2 = document.getElementById("start" + Text_Element).value;
    
    document.getElementById("start" + Text_Element).value = value1;
    document.getElementById(textbox_id).value = value2;
    
}

//FUNCTION TO REMOVE TEXTBOX ELEMENT
function _ZNRPL_Remove_Element() {
if (intTextBox != 0) {
var contentID = document.getElementById('multiple-destination');
contentID.removeChild(document.getElementById(

'strText' + intTextBox));
intTextBox = intTextBox - 1;

}

}




function _ZNRPL_Waypoints()
{

if (intTextBox != 0) {
waypoints = [];
var j =1;
for (var i = 0; i < intTextBox; i++) {
    var address = document.getElementById('start'+j).value;
    if (address !== "") {
        waypoints.push({
            location: address,
            stopover: true
        });
    }
    
    j++;
}
  
}

}


                  
var rendererOptions = {
  draggable: true
};
_ZNRPL.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);;
directionsService = new google.maps.DirectionsService();


function arp_initialize() {
  
  var centerpoint = new google.maps.LatLng(_ZNRPL.latitude,_ZNRPL.longitude);
  var mapOptions = {
    zoom:7,
    center: centerpoint
  };
  
  
  _ZNRPL.map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  _ZNRPL.directionsDisplay.setMap(_ZNRPL.map);
  _ZNRPL.directionsDisplay.setPanel(document.getElementById("directionsPanel"));
  
  //Traffic Layer Added
  var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(_ZNRPL.map);
  
  // Setting a listener that will toggle the Traffic layer
    google.maps.event.addDomListener(document.getElementById("TrafficToggle"), 'click', function() {
        if ( trafficLayer.getMap() != null ) {
            trafficLayer.setMap(null);
        }
        else {
            trafficLayer.setMap(_ZNRPL.map);
        }
    });
    
    
   	
  var input = document.getElementById('start');
  var searchBox = new google.maps.places.Autocomplete(input);
  
  var input = document.getElementById('end');
  var searchBox = new google.maps.places.Autocomplete(input);
	
	
	// Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  _ZNRPL.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
  

  // Bias the SearchBox results towards current map's viewport.
  _ZNRPL.map.addListener('bounds_changed', function() {
    searchBox.setBounds(_ZNRPL.map.getBounds());
  });
  
  //Reset the inpout box on click
    input.addEventListener('click', function(){
        input.value = "";
    });
  
  
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
	
	// Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];
	infos =[];
	
	
	places.forEach(function(place) {
	// prepare new Marker object
    var mark = new google.maps.Marker({
        position: place.geometry.location,
        map: _ZNRPL.map,
        title: place.name
    });
    markers.push(mark);

    // prepare info window
    var infowindow = new google.maps.InfoWindow({
        content:'<img src="'+ place.icon +'" /><br/><font><b>' + place.name +'</b><br /><b>'+_ZNRPL_LANG.poi_address+' </b> ' + place.formatted_address + '<br /><b>'+_ZNRPL_LANG.poi_phone+'</b>'+place.formatted_phone_number+'<br /><b>'+_ZNRPL_LANG.poi_url+'</b><a href="'+place.website+'" target="_blank">'+place.website+'</a><br /><b>'+_ZNRPL_LANG.poi_rating+'</b>'+place.rating+'</font>'
    });

    // add event handler to current marker
    google.maps.event.addListener(mark, 'click', function() {
        clearInfos();
        infowindow.open(_ZNRPL.map,mark);
    });
    infos.push(infowindow);
	
	});
	
	});
  
  google.maps.event.addListener(_ZNRPL.directionsDisplay, 'directions_changed', function() {
    computeTotalDistance(_ZNRPL.directionsDisplay.getDirections());
  });
    
  google.maps.event.addListener(_ZNRPL.map, 'idle', checkIfDataRequested);    
    
  
  if(_ZNRPL.adsense == true)
  {
  var adUnitDiv = document.createElement('div');
  var adUnitOptions = {
    format: google.maps.adsense.AdFormat[_ZNRPL.adformat],
    position: google.maps.ControlPosition[_ZNRPL.adposition],
    backgroundColor: _ZNRPL.adbackgroundColor,
    borderColor: _ZNRPL.adborderColor,
    titleColor: _ZNRPL.adtitleColor,
    textColor: _ZNRPL.adtextColor,
    urlColor: _ZNRPL.adurlColor,
    publisherId: _ZNRPL.publisherid,
    map: _ZNRPL.map,
    visible: true
  };
  var adUnit = new google.maps.adsense.AdUnit(adUnitDiv, adUnitOptions);
  }
  
  
}

function calcRoute() {
if(isEmpty(_ZNRPL.start))
{
  _ZNRPL.start = document.getElementById('start').value;
}

if(isEmpty(_ZNRPL.end))
{
  _ZNRPL.end = document.getElementById('end').value;
}
    
    $("#WeatherToggle").show();
    
if (intTextBox != 0) {
    _ZNRPL_Waypoints();
}

_ZNRPL.distance_unit = document.getElementById('distance_unit').value;
    
if (_ZNRPL.distance_unit == "Miles") {
    var unitSystem = google.maps.UnitSystem.IMPERIAL;
}
else
{
    var unitSystem = google.maps.UnitSystem.METRIC;
}

  var selectedMode = document.getElementById('mode').value;
  
  if(intTextBox>0)
      {
  var request = {
      origin:_ZNRPL.start,
      destination:_ZNRPL.end,
      waypoints: waypoints,
      optimizeWaypoints: true,
      travelMode: google.maps.TravelMode[selectedMode],
      unitSystem:unitSystem
  };
      }
    else
        {
          var request = {
      origin:_ZNRPL.start,
      destination:_ZNRPL.end,
      travelMode: google.maps.TravelMode[selectedMode],
      unitSystem:unitSystem
  };  
        }
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      _ZNRPL.directionsDisplay.setDirections(response);
    }
  });
  
    
    
  GenrateShareLink();
}

google.maps.event.addDomListener(window, 'load', arp_initialize);

google.maps.event.addDomListener(window, "resize", function() {
 var center = _ZNRPL.map.getCenter();
 google.maps.event.trigger(_ZNRPL.map, "resize");
 _ZNRPL.map.setCenter(center); 
});

function computeTotalDistance(result) {
  var total = 0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  if (_ZNRPL.distance_unit == "Miles") {
    total = total / 1000.0;
    total = Math.round(total * 0.621371);
  }
  else
  {
  total = Math.round(total / 1000.0);
  }
  
  document.getElementById('total').innerHTML = total + ' ' + _ZNRPL.distance_unit;
  
    _ZNRPL.trip_distance = total;
    
  if (_ZNRPL.enable_pricing == true) {
      
      var total_price = 0;
      
      total_price = total * _ZNRPL.unit_price;
      
      document.getElementById('trip_cost').innerHTML = '<strong>Total Distance : </strong>' + total + ' ' + _ZNRPL.distance_unit + ' <br/>  ' + ' <strong>Total Trip Cost :</strong> ' + _ZNRPL.currency_symbole + ' ' + round_cost(total_price,2) + '<br/>' + '<strong>Per ' + _ZNRPL.distance_unit + ' Cost : </strong>'+ _ZNRPL.currency_symbole + ' ' + _ZNRPL.unit_price;
  }
    
}


function round_cost(num, decimals) {
var t=Math.pow(10, decimals);   
 return (Math.round((num * t) + (decimals>0?1:0)*(Math.sign(num) * (10 / Math.pow(100, decimals)))) / t).toFixed(decimals);
    }

function GenrateShareLink(){
	
  _ZNRPL.start = document.getElementById('start').value;
  _ZNRPL.end = document.getElementById('end').value;
  
  var safestart = _ZNRPL.start.replace(" ", "+");
  
  var safeend = _ZNRPL.end.replace(" ", "+");
  
  var sharelink = "https://maps.google.com?saddr=" + safestart + "&daddr=" + safeend;
  
  document.getElementById('share').innerHTML = "<a href='" + sharelink + "' target='_blank' class='btn btn-success text-right'><i class='glyphicon glyphicon-link'></i></a>";

	
}

function isEmpty(str) {
    return (!str || 0 === str.length);
}


// Places code here
var markers = Array();
var infos = Array();

function findPlaces() {

    var type = document.getElementById('pac-input').value;
	var radius = distance( _ZNRPL.map.getBounds().getNorthEast().lat(), _ZNRPL.map.getBounds().getNorthEast().lng(), _ZNRPL.map.getBounds().getSouthWest().lat(), _ZNRPL.map.getBounds().getSouthWest().lng());;
	var keyword = '';
	var ctr = _ZNRPL.map.getCenter();
    var cur_location = new google.maps.LatLng(ctr.lat(),ctr.lng());

    // prepare request to Places
    var request = {
        location: cur_location,
        radius: radius,
        types: [type]
    };
    if (keyword) {
        request.keyword = [keyword];
    }

    // send request
    service = new google.maps.places.PlacesService(_ZNRPL.map);
    service.nearbySearch(request, createMarkers);
}	



// create markers (from 'findPlaces' function)
function createMarkers(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {

        // if we have found something - clear map (overlays)
        clearOverlays();

        // and create new markers by search result
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    } else if (status == google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
        document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.poi_nothing_found+"</div>";
        
    }
}


// clear overlays function
function clearOverlays() {
    if (markers) {
        for (i in markers) {
            markers[i].setMap(null);
        }
        markers = [];
        infos = [];
    }
}

// clear infos function
function clearInfos() {
    if (infos) {
        for (i in infos) {
            if (infos[i].getMap()) {
                infos[i].close();
            }
        }
    }
}


// creare single marker function
function createMarker(obj) {
    
    // prepare new Marker object
    var mark = new google.maps.Marker({
        position: obj.geometry.location,
        map: _ZNRPL.map,
        title: obj.name
    });
    markers.push(mark);

    // prepare info window
    var infowindow = new google.maps.InfoWindow({
        content: '<img src="'+ obj.icon +'" /><br/><font><b>' + obj.name +'</b><br /><b>'+_ZNRPL_LANG.poi_address+':</b> ' + obj.formatted_address + '<br /><b>'+_ZNRPL_LANG.poi_phone+'</b>'+obj.formatted_phone_number+'<br /><b>'+_ZNRPL_LANG.poi_url+'</b>'+obj.website+'</font>'
        
    });

    // add event handler to current marker
    google.maps.event.addListener(mark, 'click', function() {
        clearInfos();
        infowindow.open(_ZNRPL.map,mark);
    });
    infos.push(infowindow);
}

// Calcuate Distance
function distance(lat1,lon1,lat2,lon2) {
    var R = 6371; // km (change this constant to get miles)
    var dLat = (lat2-lat1) * Math.PI / 180;
    var dLon = (lon2-lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    if (d>1) {return Math.round(d) * 1000;
     } else if (d<=1){ return Math.round(d*1000);
	} else { 
    return d;
	}
}

// Numeric Value Check
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

// Fuel Calculator
function _ZNRPL_Fuel_Calculator()
{
    var fuel_type = document.getElementById("fuel_type").value;
    var fuel_rate = document.getElementById("fuel_rate").value;
    var mileage = document.getElementById("mileage").value;
    var fuel_cost =0;
    var total_fuel=0;
    var error=0;
    
    if(fuel_type.length === 0 || !fuel_type.trim())
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_type_validation+"</div>";
            error=1;
        }
    else if(fuel_rate.length === 0 || !fuel_rate.trim())
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_per_liter_price_validation+"</div>";
            error=1;
        }
    
    else if(mileage.length === 0 || !mileage.trim())
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_mileage_validation+"</div>";
            error=1;
        }
    
    else if(!isNumeric(fuel_rate))
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_numeric_price_validation+"</div>";
            error=1;
        }
    
    else if(!isNumeric(mileage))
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_numeric_mileage_validation+"</div>";
            error=1;
        }
    else if(_ZNRPL.trip_distance === 0)
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.fuel_get_route_first_validation+"</div>";
            error=1;
        }
    
    if(error===0)
    {
    
    $("#trip_cost").hide();
        
    fuel_cost = (_ZNRPL.trip_distance / mileage) * fuel_rate;
    
    total_fuel = _ZNRPL.trip_distance / mileage;
    
    document.getElementById("fuel_cost").innerHTML ='<strong>'+_ZNRPL_LANG.fuel_total_distance+' : </strong>' + _ZNRPL.trip_distance + ' ' + _ZNRPL.distance_unit + ' <br/>  ' +  '<strong>'+_ZNRPL_LANG.fuel_total_fuel_cost+' : </strong>' + _ZNRPL.currency_symbole + " "  + round_cost(fuel_cost,2) + ' <br/>  ' + ' <strong>'+_ZNRPL_LANG.fuel_total_needed+' :</strong> ' + round_cost(total_fuel,2) + " " + fuel_type + '<br/>' + '<strong>Per ' + _ZNRPL.distance_unit + _ZNRPL_LANG.fuel_total_cost+' : </strong>'+ _ZNRPL.currency_symbole + ' ' + round_cost((_ZNRPL.trip_distance / fuel_cost),2);
    }
    
}


// Function to show default transit values
function _ZNRPL_Transit_Prices()
{
   document.getElementById("adult_transit").innerHTML = '<strong>'+_ZNRPL_LANG.transit_basic_rate+' : ' + _ZNRPL.currency_symbole +''+ _ZNRPL.default_adult_cost + ' + ' + _ZNRPL.currency_symbole +'' + _ZNRPL.per_km_adult_cost + ' Per ' + _ZNRPL.distance_unit + '</strong>'; 
    
   document.getElementById("childrens_transit").innerHTML = '<strong>'+_ZNRPL_LANG.transit_basic_rate+' : ' + _ZNRPL.currency_symbole +''+ _ZNRPL.default_children_cost + ' + ' + _ZNRPL.currency_symbole +'' + _ZNRPL.per_km_children_cost + ' Per ' + _ZNRPL.distance_unit + '</strong>'; 
    
    
    document.getElementById("senior_citizens_transit").innerHTML = '<strong>'+_ZNRPL_LANG.transit_basic_rate+' : ' + _ZNRPL.currency_symbole +''+ _ZNRPL.default_senior_citizen + ' + ' + _ZNRPL.currency_symbole +'' + _ZNRPL.per_km_senior_citizen + ' Per ' + _ZNRPL.distance_unit + '</strong>';
}

// Function to calculate Transition Cost
function _ZNRPL_Transit_Calculator()
{
    
    var adults = document.getElementById("adults").value;
    var childrens = document.getElementById("childrens").value;
    var senior_citizens = document.getElementById("senior_citizens").value;
    var adults_cost =0;
    var childrens_cost=0;
    var senior_citizens_cost=0;
    var error=0;
    
    if(!isNumeric(adults) && !isEmpty(adults))
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.transit_adult_numeric_validation+"</div>";
            error=1;
        }
    
    else if(!isNumeric(childrens) && !isEmpty(childrens))
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.transit_child_numeric_validation+"</div>";
            error=1;
        }
    else if(!isNumeric(senior_citizens) && !isEmpty(senior_citizens))
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.transit_senior_numeric_validation+"</div>";
            error=1;
        }
    
    else if(_ZNRPL.trip_distance === 0)
        {
            document.getElementById("message").innerHTML = "<div class='alert alert-danger' role='alert'>"+_ZNRPL_LANG.transit_get_route_first_validation+"</div>";
            error=1;
        }
    
    if(error===0)
    {
        
    if(isEmpty(adults)) 
        {
          adults = 0;  
        }
        
        if(isEmpty(childrens)) 
        {
          childrens = 0;  
        }
        
        if(isEmpty(senior_citizens)) 
        {
          senior_citizens = 0;  
        }
    
    $("#trip_cost").hide();
        
    adults_cost = ((_ZNRPL.trip_distance * _ZNRPL.per_km_adult_cost) + _ZNRPL.default_adult_cost)* adults;
        
    childrens_cost = ((_ZNRPL.trip_distance * _ZNRPL.per_km_children_cost) + _ZNRPL.default_children_cost)* childrens;
        
    senior_citizens_cost = ((_ZNRPL.trip_distance * _ZNRPL.per_km_senior_citizen) + _ZNRPL.default_senior_citizen)* senior_citizens;
    
    var total_transit_cost = adults_cost + childrens_cost + senior_citizens_cost;
    
    document.getElementById("fuel_cost").innerHTML ='<strong>'+_ZNRPL_LANG.transit_total_distance+' : </strong>' + _ZNRPL.trip_distance + ' ' + _ZNRPL.distance_unit + ' <br/>  ' +  '<strong>'+_ZNRPL_LANG.transit_total_cost+' : </strong>' + _ZNRPL.currency_symbole + " "  + round_cost(total_transit_cost,2) + ' <br/>  ' + ' <strong>'+_ZNRPL_LANG.transit_total_adult_cost+' :</strong> ' + _ZNRPL.currency_symbole + " " + round_cost(adults_cost,2) + '<br/>'+ ' <strong>'+_ZNRPL_LANG.transit_total_children_cost+' :</strong> ' + _ZNRPL.currency_symbole + " " + round_cost(childrens_cost,2) + '<br/>'+ ' <strong>'+_ZNRPL_LANG.transit_total_senior_cost+' :</strong> ' + _ZNRPL.currency_symbole + " " + round_cost(senior_citizens_cost,2) + '<br/>' ;
    }
    
}

// Print Div Contents Only
function printContent(el){
	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
}

// Weather Widget
function WeatherWidget(){
    
    _ZNRPL.enable_weather = _ZNRPL.enable_weather +1;
    
    if(_ZNRPL.enable_weather % 2 == 0)
        
    {
     
    _ZNRPL.map.data.addListener('click', function(event) {
      infowindow.setContent(
       "<img src=" + event.feature.getProperty("weathericon") + ">"
       + "<br /><strong>" + event.feature.getProperty("city") + "</strong>"
       + "<br />" + event.feature.getProperty("temperature") + "&deg;"+_ZNRPL.measurement
       + "<br />" + event.feature.getProperty("weather")
       + "<br />"+_ZNRPL_LANG.humidity+" : " + event.feature.getProperty("humidity") + "%"      
       );
      infowindow.setOptions({
          position:{
            lat: event.latLng.lat(),
            lng: event.latLng.lng()
          },
          pixelOffset: {
            width: 0,
            height: -15
          }
        });
      infowindow.open(_ZNRPL.map);
    });
    
    getCoords();
    }
    else
    {
      resetData();  
    }
    
}


 var checkIfDataRequested = function() {
    // Stop extra requests being sent
    while (gettingData === true) {
      request.abort();
      gettingData = false;
    }
    
    if(_ZNRPL.enable_weather % 2 == 0)
    {
    getCoords();
    }
    else
    {
     resetData();        
    }
  };


// Get the coordinates from the Map bounds
  var getCoords = function() {
    var bounds = _ZNRPL.map.getBounds();
    var NE = bounds.getNorthEast();
    var SW = bounds.getSouthWest();
    getWeather(NE.lat(), NE.lng(), SW.lat(), SW.lng());
  };


// Make the weather request
  var getWeather = function(northLat, eastLng, southLat, westLng) {
      
  switch(_ZNRPL.measurement)
      {
          case "C" : 
              var measureunit = "&units=metric"; 
              break;
          case "F" : 
              var measureunit = "&units=imperial"; 
              break;
          default : 
              var measureunit = ""; 
              break;   
              
      }
      
      
    gettingData = true;
    var requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox="
                        + westLng + "," + northLat + "," //left top
                        + eastLng + "," + southLat + "," //right bottom
                        + _ZNRPL.map.getZoom()
                        + "&cluster=yes&format=json"
                        + "&APPID=beb31fa4e6b868e3d9915e8a645fdc65"
                        + measureunit;
    request = new XMLHttpRequest();
    request.onload = proccessResults;
    request.open("get", requestString, true);
    request.send();
  };


// Take the JSON results and proccess them
  var proccessResults = function() {
    //console.log(this);
    var results = JSON.parse(this.responseText);
    if (results.list.length > 0) {
        resetData();
        for (var i = 0; i < results.list.length; i++) {
          geoJSON.features.push(jsonToGeoJson(results.list[i]));
        }
        drawIcons(geoJSON);
    }
  };
  var infowindow = new google.maps.InfoWindow();
  // For each result that comes back, convert the data to geoJSON
  var jsonToGeoJson = function (weatherItem) {
      // SVG ICON Genrator
      
      switch (weatherItem.weather[0].icon)
          {
              case "01d":
                  var iconpath = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="day"> <g transform="translate(32,32)"> <g class="am-weather-sun am-weather-sun-shiny am-weather-easing-ease-in-out"> <g> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(45)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(90)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(135)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(180)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(225)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(270)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> <g transform="rotate(315)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3" /> </g> </g> <circle cx="0" cy="0" fill="orange" r="5" stroke="orange" stroke-width="2"/> </g> </g><text y="60" x="10" >';
                  break;
                  
              case "01n":
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="night"> <g transform="translate(20,20)"> <g class="am-weather-moon-star-1"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10"/> </g> <g class="am-weather-moon-star-2"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10" transform="translate(20,10)"/> </g> <g class="am-weather-moon"> <path d="M14.5,13.2c0-3.7,2-6.9,5-8.7 c-1.5-0.9-3.2-1.3-5-1.3c-5.5,0-10,4.5-10,10s4.5,10,10,10c1.8,0,3.5-0.5,5-1.3C16.5,20.2,14.5,16.9,14.5,13.2z" fill="orange" stroke="orange" stroke-linejoin="round" stroke-width="2"/> </g> </g> </g><text y="60" x="10" >';
                  break;
                  
             case "02d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-day-1"> <g transform="translate(20,10)"> <g transform="translate(0,16)"> <g class="am-weather-sun"> <g> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(45)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(90)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(135)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(180)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(225)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(270)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(315)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> </g> <circle cx="0" cy="0" fill="orange" r="5" stroke="orange" stroke-width="2"/> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#C6DEFF" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10" >';
                  break;
                  
            case "02n":
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-night-3"> <g transform="translate(20,10)"> <g transform="translate(16,4), scale(0.8)"> <g class="am-weather-moon-star-1"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10"/> </g> <g class="am-weather-moon-star-2"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10" transform="translate(20,10)"/> </g> <g class="am-weather-moon"> <path d="M14.5,13.2c0-3.7,2-6.9,5-8.7 c-1.5-0.9-3.2-1.3-5-1.3c-5.5,0-10,4.5-10,10s4.5,10,10,10c1.8,0,3.5-0.5,5-1.3C16.5,20.2,14.5,16.9,14.5,13.2z" fill="orange" stroke="orange" stroke-linejoin="round" stroke-width="2"/> </g> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;
                  
            case "03d":
                  var iconpath = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-day-1"> <g transform="translate(20,10)"> <g transform="translate(0,16)"> <g class="am-weather-sun"> <g> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(45)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(90)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(135)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(180)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(225)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(270)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(315)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> </g> <circle cx="0" cy="0" fill="orange" r="5" stroke="orange" stroke-width="2"/> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#C6DEFF" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "03n":      
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-night-1"> <g transform="translate(20,10)"> <g transform="translate(16,4), scale(0.8)"> <g class="am-weather-moon-star-1"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10"/> </g> <g class="am-weather-moon-star-2"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10" transform="translate(20,10)"/> </g> <g class="am-weather-moon"> <path d="M14.5,13.2c0-3.7,2-6.9,5-8.7 c-1.5-0.9-3.2-1.3-5-1.3c-5.5,0-10,4.5-10,10s4.5,10,10,10c1.8,0,3.5-0.5,5-1.3C16.5,20.2,14.5,16.9,14.5,13.2z" fill="orange" stroke="orange" stroke-linejoin="round" stroke-width="2"/> </g> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#C6DEFF" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "04d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-day-3"> <g transform="translate(20,10)"> <g transform="translate(0,16)"> <g class="am-weather-sun"> <g> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(45)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(90)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(135)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(180)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(225)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(270)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(315)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> </g> <circle cx="0" cy="0" fill="orange" r="5" stroke="orange" stroke-width="2"/> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "04n":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy-night-3"> <g transform="translate(20,10)"> <g transform="translate(16,4), scale(0.8)"> <g class="am-weather-moon-star-1"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10"/> </g> <g class="am-weather-moon-star-2"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10" transform="translate(20,10)"/> </g> <g class="am-weather-moon"> <path d="M14.5,13.2c0-3.7,2-6.9,5-8.7 c-1.5-0.9-3.2-1.3-5-1.3c-5.5,0-10,4.5-10,10s4.5,10,10,10c1.8,0,3.5-0.5,5-1.3C16.5,20.2,14.5,16.9,14.5,13.2z" fill="orange" stroke="orange" stroke-linejoin="round" stroke-width="2"/> </g> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;      
             
             case "09d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="rainy-1"> <g transform="translate(20,10)"> <g transform="translate(0,16), scale(1.2)"> <g class="am-weather-sun"> <g> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(45)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(90)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(135)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(180)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(225)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(270)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> <g transform="rotate(315)"> <line fill="none" stroke="orange" stroke-linecap="round" stroke-width="2" transform="translate(0,9)" x1="0" x2="0" y1="0" y2="3"/> </g> </g> <circle cx="0" cy="0" fill="orange" r="5" stroke="orange" stroke-width="2"/> </g> <g> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.5" transform="translate(-15,-5), scale(0.85)"/> </g> </g> <g transform="translate(34,46), rotate(10)"> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="4,7" stroke-linecap="round" stroke-width="2" transform="translate(-6,1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-2" fill="none" stroke="#91C0F8" stroke-dasharray="4,7" stroke-linecap="round" stroke-width="2" transform="translate(0,-1)" x1="0" x2="0" y1="0" y2="8" /> </g> </g><text y="60" x="10">';
                  break; 
             
             case "09n":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="rainy-5"> <g transform="translate(20,10)"> <g> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> <g transform="translate(34,46), rotate(10)"> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="4,7" stroke-linecap="round" stroke-width="2" transform="translate(-6,1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-2" fill="none" stroke="#91C0F8" stroke-dasharray="4,7" stroke-linecap="round" stroke-width="2" transform="translate(0,-1)" x1="0" x2="0" y1="0" y2="8" /> </g> </g><text y="60" x="10">';
                  break;
                  
             case "10d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="rainy-7"> <g transform="translate(20,10)"> <g> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> <g transform="translate(31,46), rotate(10)"> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(-5,1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-2" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(0,-1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(5,0)" x1="0" x2="0" y1="0" y2="8" /> </g> </g><text y="60" x="10">';
                  break;
             
             case "10n":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="rainy-7"> <g transform="translate(20,10)"> <g> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> <g transform="translate(31,46), rotate(10)"> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(-5,1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-2" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(0,-1)" x1="0" x2="0" y1="0" y2="8" /> <line class="am-weather-rain-1" fill="none" stroke="#91C0F8" stroke-dasharray="0.1,7" stroke-linecap="round" stroke-width="3" transform="translate(5,0)" x1="0" x2="0" y1="0" y2="8" /> </g> </g><text y="60" x="10">';
                  break;
                  
             case "11d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="thunder"> <g transform="translate(20,10)"> <g class="am-weather-cloud-1"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#91C0F8" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-10,-6), scale(0.6)" /> </g> <g> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)" /> </g> <g transform="translate(-9,28), scale(1.2)"> <polygon class="am-weather-stroke" fill="orange" stroke="white" stroke-width="1" points="14.3,-2.9 20.5,-2.9 16.4,4.3 20.3,4.3 11.5,14.6 14.9,6.9 11.1,6.9" /> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "11n":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="thunder"> <g transform="translate(20,10)"> <g class="am-weather-cloud-1"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#91C0F8" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-10,-6), scale(0.6)" /> </g> <g> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)" /> </g> <g transform="translate(-9,28), scale(1.2)"> <polygon class="am-weather-stroke" fill="orange" stroke="white" stroke-width="1" points="14.3,-2.9 20.5,-2.9 16.4,4.3 20.3,4.3 11.5,14.6 14.9,6.9 11.1,6.9" /> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "13d":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="snowy-6"> <g transform="translate(20,10)"> <g class="am-weather-cloud-2"> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> <g class="am-weather-snow-1"> <g transform="translate(3,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> <g class="am-weather-snow-2"> <g transform="translate(11,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> <g class="am-weather-snow-3"> <g transform="translate(20,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> </g> </g><text y="60" x="10">';
                  break;
                  
             case "13n":
                  var iconpath='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="snowy-6"> <g transform="translate(20,10)"> <g class="am-weather-cloud-2"> <path d="M47.7,35.4c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> <g class="am-weather-snow-1"> <g transform="translate(3,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> <g class="am-weather-snow-2"> <g transform="translate(11,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> <g class="am-weather-snow-3"> <g transform="translate(20,28)"> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1.2" transform="translate(0,9), rotate(0)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(45)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(90)" x1="0" x2="0" y1="-2.5" y2="2.5" /> <line fill="none" stroke="#57A0EE" stroke-linecap="round" stroke-width="1" transform="translate(0,9), rotate(135)" x1="0" x2="0" y1="-2.5" y2="2.5" /> </g> </g> </g> </g><text y="60" x="10">';
                  break;
             
             case "50d":
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy"> <g transform="translate(20,10)"> <g class="am-weather-cloud-1"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#91C0F8" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-10,-8), scale(0.6)"/> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;
                  
                  
             case "50n":
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="cloudy"> <g transform="translate(20,10)"> <g class="am-weather-cloud-1"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#91C0F8" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-10,-8), scale(0.6)"/> </g> <g class="am-weather-cloud-2"> <path d="M47.7,35.4 c0-4.6-3.7-8.2-8.2-8.2c-1,0-1.9,0.2-2.8,0.5c-0.3-3.4-3.1-6.2-6.6-6.2c-3.7,0-6.7,3-6.7,6.7c0,0.8,0.2,1.6,0.4,2.3 c-0.3-0.1-0.7-0.1-1-0.1c-3.7,0-6.7,3-6.7,6.7c0,3.6,2.9,6.6,6.5,6.7l17.2,0C44.2,43.3,47.7,39.8,47.7,35.4z" fill="#57A0EE" stroke="#FFFFFF" stroke-linejoin="round" stroke-width="1.2" transform="translate(-20,-11)"/> </g> </g> </g><text y="60" x="10">';
                  break;      
                  
             default :
                  var iconpath ='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewbox="0 0 64 64"> <defs> <filter id="blur" width="200%" height="200%"> <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <feOffset dx="0" dy="4" result="offsetblur"/> <feComponentTransfer> <feFuncA type="linear" slope="0.05"/> </feComponentTransfer> <feMerge> <feMergeNode/> <feMergeNode in="SourceGraphic"/> </feMerge> </filter> </defs> <g filter="url(#blur)" id="night"> <g transform="translate(20,20)"> <g class="am-weather-moon-star-1"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10"/> </g> <g class="am-weather-moon-star-2"> <polygon fill="orange" points="3.3,1.5 4,2.7 5.2,3.3 4,4 3.3,5.2 2.7,4 1.5,3.3 2.7,2.7" stroke="none" stroke-miterlimit="10" transform="translate(20,10)"/> </g> <g class="am-weather-moon"> <path d="M14.5,13.2c0-3.7,2-6.9,5-8.7 c-1.5-0.9-3.2-1.3-5-1.3c-5.5,0-10,4.5-10,10s4.5,10,10,10c1.8,0,3.5-0.5,5-1.3C16.5,20.2,14.5,16.9,14.5,13.2z" fill="orange" stroke="orange" stroke-linejoin="round" stroke-width="2"/> </g> </g> </g><text y="60" x="10">';
                  break;
          }
      
      
      
    var feature = {
      type: "Feature",
      properties: {
        city: weatherItem.name,
        weather: weatherItem.weather[0].main,
        temperature: weatherItem.main.temp,
        min: weatherItem.main.temp_min,
        max: weatherItem.main.temp_max,
        humidity: weatherItem.main.humidity,
        pressure: weatherItem.main.pressure,
        windSpeed: weatherItem.wind.speed,
        windDegrees: weatherItem.wind.deg,
        windGust: weatherItem.wind.gust,
        weathericon: "http://openweathermap.org/img/w/"
              + weatherItem.weather[0].icon  + ".png",
        //icon: 'data:image/svg+xml,<svg width="80" height="80" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg"><path fill="#91C0F8" stroke="white" stroke-linejoin="round" stroke-width="1.2" transform="translate(-10,-8), scale(0.6)" d="'+iconpath+'" ></path><text transform="translate(19 18.5)" fill="#000" style="font-family: Arial, sans-serif;font-weight:bold;text-align:right;" font-size="12" text-anchor="middle" x="20">'+weatherItem.main.temp+'¡Æ'+_ZNRPL.measurement+'</text></svg>',
          
        icon : 'data:image/svg+xml,'+iconpath+weatherItem.main.temp+'¡Æ'+_ZNRPL.measurement+'</text></svg>',  
         
        coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]        
      },
      geometry: {
        type: "Point",
        coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]
      }  
    };
    // Set the custom marker icon
    _ZNRPL.map.data.setStyle(function(feature) {
      return {
        icon: {
          url: feature.getProperty('icon'),
          anchor: new google.maps.Point(25, 25)
        }
      };
    });
      
   // returns object
    return feature;
  };
  // Add the markers to the map
  var drawIcons = function (weather) {
     _ZNRPL.map.data.addGeoJson(geoJSON);
     // Set the flag to finished
     gettingData = false;
  };
  // Clear data layer and geoJSON
  var resetData = function () {
    geoJSON = {
      type: "FeatureCollection",
      features: []
    };
    _ZNRPL.map.data.forEach(function(feature) {
      _ZNRPL.map.data.remove(feature);
    });
  };
  