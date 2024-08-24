@extends('frontend.layout')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Dashboard')])

  <!--====== Start Dashboard Section ======-->
  <section class="user-dashboard pt-130 pb-120">
    <div class="container">
        <div class="row">
        @includeIf('frontend.user.side-navbar')
        </div>

       <div class="row">

        <div class="col-lg-12">
          <form  action="{{ route('user.branch.store') }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                   
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Branch Name *') }}</label>
                      <input type="text" value="" class="form-control" name="name"
                        placeholder="{{ __('Branch Name') }} ">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Branch Location *') }}</label>
                      <input type="text" value="" class="form-control" name="location"
                        placeholder="{{ __('Branch Location') }} " id="b_location">
                        <input id="b_location_lat" name="lat" hidden>
                        <input id="b_location_long" name="lng" hidden>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Company*') }}</label>
                      @if($company)
                        <input value="{{ $company->name }}" readonly  class="form-control">
                      @else
                        <input value="" readonly  class="form-control">
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Purpose *') }}</label>
                      <input type="text" value="" class="form-control" name="purpose"
                        placeholder="{{ __('Purpose') }} ">
                    </div>
                  </div>
                   <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Significance *') }}</label>
                      <input type="text" value="" class="form-control" name="significance"
                        placeholder="{{ __('Significance') }} ">
                    </div>
                  </div>
                   <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Name of the team member responsible for branch management *') }}</label>
                      <input type="text" value="" class="form-control" name="name_of_memeber"
                        placeholder="{{ __('Name of the team member responsible for branch management') }} ">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Responsibilities *') }}</label>
                      <input type="text" value="" class="form-control" name="responsibilities"
                        placeholder="{{ __('Responsibilities') }} ">
                    </div>
                  </div>

                </div>
                <button type="submit" class="btn btn-primary">Create</button>
              </form>

        </div>
      </div>
    </div>
  </section>
  @section('script')
  <script>
   var searchInput = 'b_location';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('b_location_lat').value = near_place.geometry.location.lat();
            document.getElementById('b_location_long').value = near_place.geometry.location.lng();
        });
    });

    $(document).on('change', '#'+searchInput, function () {
        document.getElementById('b_location_lat').value = '';
        document.getElementById('b_location_long').value = '';
    });
    </script>
  @endsection
  @endsection
