@extends('frontend.layout')

@section('pageHeading')
  {{ 'Request A Quote' }}
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => 'Request A Quote',
  ])

  <!--====== Start Equipment Details Section ======-->
  <section class="equipment-details-section pt-130 pb-110">
    <div class="container">
      <div class="row">
           <div class="col-lg-12">
          <div class="equipement-sidebar-info">
            <form action="{{ route('equipment.request_quote') }}" method="POST" enctype="multipart/form-data"
              id="equipment-quote-form">
              @csrf
              <div class="booking-form">


                <div class="pricing-body">
				@if (session('success'))
					<div class="row">
					<div class="col-sm-12">
						<div class="alert  alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
					</div>
					</div>
				@endif
                  {{-- show error message for request-price-message --}}
				  
				  
					<?php if( $errors->any()){ ?>
                  <div class="row">
                      <div class="col">
                        <div class="alert alert-danger alert-block">
                        <?php 
						
							foreach ($errors->all() as $message) {
								echo '<strong>'.$message.'</strong><br>';
							} 
						
						?>
                         
                          <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                      </div>
                    </div>
					<?php } ?>
                  
                  @error('price_message')
                    <div class="row">
                      <div class="col">
                        <div class="alert alert-danger alert-block">
                          <strong>{{ $message }}</strong>
                          <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                      </div>
                    </div>
                  @enderror
                    
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <h5>{{ __('Contact Information') }}</h5>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('First Name') . '*' }}</label>
                        <input type="text" class="form-control" name="first_name" placeholder="{{ __('Enter First Name') }}">
                        <p id="err_first_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Last Name') . '*' }}</label>
                        <input type="text" class="form-control" name="last_name" placeholder="{{ __('Enter Last Name') }}">
                        <p id="err_last_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Email') . '*' }}</label>
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Enter email') }}">
                        <p id="err_email" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mobile Number') . '*' }}</label>
                        <input type="text" class="form-control" name="phone" placeholder="{{ __('Enter Mobile Number') }}">
                        <p id="err_phone" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Company Name') . '*' }}</label>
                        <input type="text" class="form-control" name="company_name" placeholder="{{ __('Enter Company Name') }}">
                        <p id="err_company_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-12">
                        <h5>{{ __('Project Information') }}</h5>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Project Country') . '*' }}</label>
                        <input type="text" class="form-control" name="project_country" placeholder="{{ __('Enter Project Country') }}">
                        <p id="err_project_country" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Project City') . '*' }}</label>
                        <input type="text" class="form-control" name="project_city" placeholder="{{ __('Enter Project City') }}">
                        <p id="err_project_city" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Project State/Province') . '*' }}</label>
                        <input type="text" class="form-control" name="project_state" placeholder="{{ __('Enter Project State/Province') }}">
                        <p id="err_project_state" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Project ZIP/Postal Code') . '*' }}</label>
                        <input type="text" class="form-control" name="project_zipcode" placeholder="{{ __('Enter Project ZIP/Postal Code') }}">
                        <p id="err_project_zipcode" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Project Start date') }}</label>
                        <input type="date" class="form-control" name="project_startdate">
                        <p id="err_project_startdate" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('Estimated Guest/Worker Count') }}</label>
                        <input type="number" step="1" class="form-control" name="worker_count" placeholder="{{ __('Enter Estimated Guest/Worker Count') }}">
                        <p id="err_worker_count" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="">{{ __('Equipment Needed'). '*' }}</label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Fresh Water Systems">
                          <label class="form-check-label">
                            Fresh Water Systems
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Highrise Elevator Units">
                          <label class="form-check-label">
                            Highrise Elevator Units
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Restroom Trailers">
                          <label class="form-check-label">
                            Restroom Trailers
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Hand Wash Stations">
                          <label class="form-check-label">
                            Hand Wash Stations
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Portable Showers">
                          <label class="form-check-label">
                            Portable Showers
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Waste Tank Systems">
                          <label class="form-check-label">
                            Waste Tank Systems
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Portable Toilets">
                          <label class="form-check-label">
                            Portable Toilets
                          </label>
                        </div>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="equipment_needed[]" value="Temporary Fencing">
                          <label class="form-check-label">
                            Temporary Fencing
                          </label>
                        </div>
                        
                        
                    </div>

                    <div class="form-group col-md-12">
                        <label for="">{{ __('Explain your project or ask our United Rentals experts a question') }}</label>
                        <textarea class="form-control" name="details" placeholder="{{ __('Briefly describe your upcoming project or ask a question for one of our experts.') }}"></textarea>
                        <p id="err_details" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                </div>

                    <div class="button text-center mt-30">
                      <button type="submit" class="main-btn">{{ __('Request A Quote') }}</button>
                    </div>

                </div>
              </div>
            </form>
          </div>
        </div>
        

       
      </div>
    </div>
  </section>
  
@endsection

