@extends('frontend.layout')

@section('pageHeading')
  {{ __('Add Payment Methods') }}
@endsection

@section('content')
  <style>
      .selection{
          width:100%;
      }
      .select2-container .select2-selection--single{
          height:50px;
          display:flex;
          align-items:center;
      }
      .select2-container--default .select2-selection--single .select2-selection__arrow{
          top:12px;
      }
  </style>

  <!--====== Start Product Orders Section ======-->
  <section class="user-dashboard">
    <div class="container-fluid">
        
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
                      <h4>{{ __('Add Payment Methods') }}</h4>
                    </div>
                    
                    <div class="main-info edit-info-area">
                      <form action="{{ route('user.store_payment_methods') }}" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('First Name') }}" name="first_name">
                            @error('first_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
  
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Last Name') }}" name="last_name">
                            @error('last_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
  
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Card Number') }}" name="card_number">
                            @error('card_number')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('CVV') }}" name="cvv">
                            @error('cvv')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="number" step="1" min="1" max="12" class="form_control" placeholder="{{ __('Expiry Month') }}" name="exp_month">
                            @error('exp_month')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Expiry Year') }}" name="exp_year">
                            @error('exp_year')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                              <input class="form_control" value="Dubai" placeholder="{{ __('Location') }}" name="location"  id="pay_meth_location"/>
                              <input id="pay_meth_location_lat" name="lat"  value="20" hidden>
                              <input id="pay_meth_location_long" name="lng" value="20"  hidden>
                            
                            @error('location')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                              <textarea class="form_control" placeholder="{{ __('Address1') }}" name="address1"></textarea>
                            
                            @error('address1')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <textarea class="form_control" placeholder="{{ __('Address2') }}" name="address2"></textarea>
                            
                            @error('address2')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('City') }}" name="city">
                            @error('city')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        @if(auth()->user()->account_type == "corperate_account")
                        <div class="row">
                          <div class="col-lg-12">
                            <select class="form-control select2" name="branch_id">
                                <option selected disabled>Select Branch</option>
                                @foreach($branches as $branch)
                                  <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        @endif
                        
                        <div class="row">
                          <div class="col-lg-12 d-flex">
                            <input type="checkbox" name="is_default" id="is_default" value="1" @if(count($cards) == 0) checked readonly @endif>
                            <label class="m-0 ml-2" for="is_default">Default</label>
                          </div>
                        </div>
                          <br>
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn form-btn">{{ __('Submit') }}</button>
                            </div>
                          </div>
                        </div>
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
  <!--====== End Product Orders Section ======-->
   @section('script')
  <script>
   var searchInput = 'pay_meth_location';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('pay_meth_location_lat').value = near_place.geometry.location.lat();
            document.getElementById('pay_meth_location_long').value = near_place.geometry.location.lng();
        });
        $('.select2').select2();
    });

    $(document).on('change', '#'+searchInput, function () {
        document.getElementById('pay_meth_location_lat').value = '';
        document.getElementById('pay_meth_location_long').value = '';
    });
    </script>
  @endsection
@endsection
