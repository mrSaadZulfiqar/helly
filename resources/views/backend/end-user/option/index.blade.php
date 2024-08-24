@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Communication Settings') }}</h4>
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
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Communication Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Communication Setup') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm" action="{{ route('admin.basic_settings.communication_settings_update') }}" method="post">
                @csrf
                <div class="row">
                  <!-- <div class="col-lg-12">
                    <h2 class="mt-3 text-warning">Vonage API Settings</h2>
                    
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('VONAGE KEY') }}</label>
                      <input type="text" value="" class="form-control" name="vonage_key"
                        placeholder="{{ __('Enter Vonage Key') }}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('VONAGE SECRET') }}</label>
                      <input type="text" value="" class="form-control" name="vonage_secret"
                        placeholder="{{ __('Enter Vonage Secret') }}">
                    </div>
                  </div> -->

                  <div class="col-lg-12">
                    <h2 class="mt-3 text-warning">Select SMS Provider</h2>
                    
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                        <div class="pl-3">
                            <input type="radio" class="form-check-input" name="system_sms_service_provider" value="messagebird" <?php echo (isset($options['system_sms_service_provider']) && $options['system_sms_service_provider'] == 'messagebird')?'checked':''; ?>> Message Bird
                        </div>
                        <div class="pl-3">
                            <input type="radio" class="form-check-input" name="system_sms_service_provider" value="voximplant" <?php echo (isset($options['system_sms_service_provider']) && $options['system_sms_service_provider'] == 'voximplant')?'checked':''; ?>> Voximplant
                        </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <h2 class="mt-3 text-warning">Message Bird API Settings</h2>
                    
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('MESSAGE BIRD LIVE API KEY') }}</label>
                      <input type="text" value="{{ $options['message_bird_api_key']??'' }}" class="form-control" name="message_bird_api_key"
                        placeholder="{{ __('Enter Message Bird API Key') }}">
                    </div>
                  </div>
                  
                  <div class="col-lg-12">
                    <h2 class="mt-3 text-warning">Voximplant API Settings</h2>
                    
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('USERNAME') }}</label>
                      <br>
                      <small>( Fully-qualified username that includes Voximplant user, application and account names. The format is: "username@appname.accname.voximplant.com". )</small>
                      <input type="text" value="{{ $options['voximplant_username']??'' }}" class="form-control" name="voximplant_username"
                        placeholder="{{ __('Enter Username') }}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('PASSWORD') }}</label>
                      <input type="text" value="{{ $options['voximplant_password']??'' }}" class="form-control" name="voximplant_password"
                        placeholder="{{ __('Enter PASSWORD') }}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('ACCOUNT ID') }}</label>
                      <input type="text" value="{{ $options['voximplant_account_id']??'' }}" class="form-control" name="voximplant_account_id"
                        placeholder="{{ __('Enter ACCOUNT ID') }}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('API KEY') }}</label>
                      <input type="text" value="{{ $options['voximplant_api_key']??'' }}" class="form-control" name="voximplant_api_key"
                        placeholder="{{ __('Enter API KEY') }}">
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <h2 class="mt-3 text-warning">Voximplant Kit Settings</h2>
                    
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('DOMAIN') }}</label>
                      <input type="text" value="{{ $options['voximplant_kit_domain']??'' }}" class="form-control" name="voximplant_kit_domain"
                        placeholder="{{ __('Enter DOMAIN') }}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('ACCESS TOKEN') }}</label>
                      <input type="text" value="{{ $options['voximplant_kit_access_token']??'' }}" class="form-control" name="voximplant_kit_access_token"
                        placeholder="{{ __('Enter ACCESS TOKEN') }}">
                    </div>
                  </div>
                </div>
                
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
