@extends('frontend.layout')

@section('pageHeading')
  {{ __('Reset Password') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Reset Password')])

  <!--====== Reset Password Part Start ======-->
  <div class="user-area-section pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="user-form">
            <form action="{{ route('user.reset_password_submit') }}" method="POST">
              @csrf
              <div class="form_group mb-4">
                <!--<label>{{ __('New Password') . '*' }}</label>-->
                <input type="password" class="form_control" placeholder="New Password" name="new_password">
                @error('new_password')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group mb-4">
                <!--<label>{{ __('Confirm New Password') . '*' }}</label>-->
                <input type="password" class="form_control" placeholder="Confirm New Password" name="new_password_confirmation">
                @error('new_password_confirmation')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group">
                <button type="submit" class="main-btn">{{ __('Submit') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== Reset Password Part End ======-->
@endsection
