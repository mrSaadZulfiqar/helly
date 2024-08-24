@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Change Account Type') }}</h4>
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
        <a href="#">{{ __('Customers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Change Account Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Change Account Type') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form  action="{{ route('admin.user_management.update_account_type', ['id' => $user->id]) }}" method="post">
                @csrf
                <div class="form-group">
                  <label>{{ __('Account Type') . '*' }}</label>
                  <select name="account_type" class="form-control account_type">
                      <option value="indivisual_account" @if($user->account_type == 'indivisual_account') selected @endif>Indivisual Account</option>
                      <option value="corperate_account" @if($user->account_type == 'corperate_account') selected @endif>Corporate Account</option>
                  </select>
                  <p id="editErr_new_password" class="mt-2 mb-0 text-danger em"></p>
                </div>

                <div class="form-group mb-4 @if($user->account_type == 'indivisual_account' || $user->account_type == '') d-none @endif " id="company_name_1">
                    <!--<label>{{ __('Username') . '*' }}</label>-->
                    <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ $company->name ?? "" }}">
                    @error('company_name')
                      <p class="text-danger mt-2">{{ $message }}</p>
                    @enderror
                </div>
              
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success">
                {{ __('Update') }}
              </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @section('script')
  <script>
     $(document).on('change','.account_type',function(){
        let value = $(this).val();
        $('.cus_label').removeClass('selected-label'); // Remove the class from all labels
        if ($(this).is(':checked')) {
            $(this).closest('.form_group').find('.cus_label').addClass('selected-label'); // Add the class to the label associated with the checked radio button
        }
        if(value == 'corperate_account')
        {
            $('#company_name_1').removeClass('d-none')
        }else{
            $('#company_name_1').addClass('d-none')
        }
    });
    </script>
  @endsection
@endsection