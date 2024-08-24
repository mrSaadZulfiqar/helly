<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      <main>
        <div class="calculator col-md-4 offset-md-4 card p-3 mt-3">
            <div class="mt-2">
                <label for="distance">To</label>
                <input type="text" id="to" class="form-control">
            </div>
            <div class="mt-2">
                <label for="distance">From</label>
                <input type="text" id="from" class="form-control">
            </div>
            <div class="mt-2">
                <label for="distance">Distance (km)</label>
                <input type="number" id="distance" class="form-control" readonly>
            </div>
            <div class="mt-2">
                <label for="weight">Weight (kg)</label>
                <input type="number" id="weight" class="form-control">
            </div>
            <div class="mt-2">
                <label for="weight">Height (cm)</label>
                <input type="number" id="height" class="form-control">
            </div>
            <div class="mt-2">
                <label for="weight">Width (cm)</label>
                <input type="number" id="width" class="form-control">
            </div>
            <div class="mt-2">
                <label for="weight">Lenght (cm)</label>
                <input type="number" id="lenght" class="form-control">
            </div>
            <div class="mt-2">
                <label for="size">Volume (m3)</label>
                <input type="number" id="size" class="form-control" readonly>
            </div>
            <br>
            <button id="calculate" class="btn btn-info">Calculate</button>
            <p id="rate" class="mt-3"></p>
        </div>
      </main>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATcXjxQmVMptI5sRIpCpiPlouTTa1x7kk&libraries=places"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){

            var autocompleteTo;
            var autocompleteFrom;
            
            var toInput = 'to';
            var fromInput = 'from';
        
            autocompleteTo = new google.maps.places.Autocomplete((document.getElementById(toInput)), {
                types: ['geocode'],
            });
            autocompleteFrom = new google.maps.places.Autocomplete((document.getElementById(fromInput)), {
                types: ['geocode'],
            });
        
            google.maps.event.addListener(autocompleteTo, 'place_changed', function () {
                calculateDistance();
            });
        
            google.maps.event.addListener(autocompleteFrom, 'place_changed', function () {
                calculateDistance();
            });
            function calculateDistance() {
                var fromPlace = autocompleteFrom.getPlace();
                var toPlace = autocompleteTo.getPlace();
            
                if (fromPlace.geometry && toPlace.geometry) {
                    var fromLat = fromPlace.geometry.location.lat();
                    var fromLng = fromPlace.geometry.location.lng();
                    var toLat = toPlace.geometry.location.lat();
                    var toLng = toPlace.geometry.location.lng();
            
                    var distance = haversineDistance(fromLat, fromLng, toLat, toLng);
                    $('#distance').val(distance.toFixed(2));
                    console.log('Distance between the places: ' + distance.toFixed(2) + ' km');
                }
            }
            
            function haversineDistance(lat1, lon1, lat2, lon2) {
                var R = 6371; 
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var distance = R * c;
                return distance;
            }
            
            $(document).on('keyup', ['#lenght','#width','#height'], function(){
                let lenght = Number($('#lenght').val());
                let width = Number($('#width').val());
                let height = Number($('#height').val());
                let calculate_size  = lenght * width * height ;
                let calculate_size_m3  = calculate_size  / 1000000; ;
                $('#size').val(calculate_size_m3);
            });
            
            $(document).on('click', '#calculate', function(){
                let distance = Number($('#distance').val());
                let weight = Number($('#weight').val());
                let lenght = Number($('#lenght').val());
                let width = Number($('#width').val());
                let height = Number($('#height').val());
                let calculate_size  = lenght * width * height ;
                let calculate_size_m3  = calculate_size  / 1000000; ;
                $('#size').val(calculate_size_m3);
                let size = $('#size').val();
                let rate = 0;

                if(distance == 0 && weight == 0 && size == 0){
                    alert("All Fields are Required.");
                    return;
                }

                if(distance > 0 && distance <= 60){
                    if(weight > 0 && weight <= 1050){
                        rate = 40000;
                    }else if(weight > 1050  && weight <= 2100){
                        rate = 51000;
                    }else if(weight > 2100  && weight <= 3150){
                        rate = 59000;
                    }else if(weight > 3150  && weight <= 4200 ){
                        rate = 66000;
                    }else{
                        alert("Weight exceeds 4200kg for distances under 60km.");
                        return;
                    }
                }
                else{
                    if(weight > 0 && weight <= 200 && size > 0 &&  size <= 0.6){
                        rate = 712 * distance;
                    }else if(weight > 200 && weight <= 1050 && size > 0.6  && size <= 5){
                        rate = 984 * distance;
                    }else if(weight > 1050 && weight <= 2600 && size > 5  && size <= 12){
                        rate = 1337 * distance;
                    }else if(weight > 2600 && weight <= 4200 && size > 12  && size <= 21){
                        rate = 1524 * distance;
                    } else {
                        alert("Weight or size exceeds the limits for the given distance.");
                        return;
                    }
                }
                $('#rate').html("<b>Rate : </b>"+rate+" CLP");
            });
        });
    </script>
  </body>
</html>