@extends('frontend.layout')

@section('pageHeading')
  {{ __('Edit Payment Methods') }}
@endsection

@section('content')

  <!--====== Start Product Orders Section ======-->
  <section class="user-dashboard">
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
                      <h4>{{ __('Edit Payment Methods') }}</h4>
                    </div>
                    
                    <div class="main-info edit-info-area">
                      <form action="{{ route('user.update_payment_method', $card->id) }}" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('First Name') }}" name="first_name" value="{{ $card->first_name }}">
                            @error('first_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
  
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Last Name') }}" name="last_name" value="{{ $card->last_name }}">
                            @error('last_name')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
  
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Card Number') }}" name="card_number" value="{{ $card->card_number }}">
                            @error('card_number')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('CVV') }}" name="cvv" value="{{ $card->cvv }}">
                            @error('cvv')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="number" step="1" min="1" max="12" class="form_control" placeholder="{{ __('Expiry Month') }}" name="exp_month" value="{{ $card->exp_month }}">
                            @error('exp_month')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Expiry Year') }}" name="exp_year" value="{{ $card->exp_year }}">
                            @error('exp_year')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                              <input class="form_control" placeholder="{{ __('Location') }}" name="location" value="{{ $card->location }}"  id="u_pay_meth_location"/>
                              <input id="u_pay_meth_location_lat" name="lat" hidden value="{{ $card->lat }}" >
                              <input id="u_pay_meth_location_long" name="lng" hidden value="{{ $card->lng }}" >
                            
                            @error('location')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                              <textarea class="form_control" placeholder="{{ __('Address1') }}" name="address1">{{ $card->address1 }}</textarea>
                            
                            @error('address1')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <textarea class="form_control" placeholder="{{ __('Address2') }}" name="address2">{{ $card->address2 }}</textarea>
                            
                            @error('address2')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('City') }}" name="city" value="{{ $card->city }}">
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
                                  <option value="{{ $branch->id }}" @if($branch->id == $card->branch_id) selected @endif>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        @endif
                        <div class="row">
                          <div class="col-lg-12 d-flex">
                            <input type="checkbox" name="is_default" id="is_default" value="1" @if($card->is_default == 1) checked @endif>
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
  
  <!--====== End Product Orders Section ======-->
   @section('script')
  <script>
   var searchInput = 'u_pay_meth_location';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('u_pay_meth_location_lat').value = near_place.geometry.location.lat();
            document.getElementById('u_pay_meth_location_long').value = near_place.geometry.location.lng();
        });
    });

    $(document).on('change', '#'+searchInput, function () {
        document.getElementById('u_pay_meth_location_lat').value = '';
        document.getElementById('u_pay_meth_location_long').value = '';
    });
    </script>
  @endsection
@endsection
