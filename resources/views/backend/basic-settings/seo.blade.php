@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('SEO Informations') }}</h4>
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
        <a href="#">{{ __('SEO Informations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_seo', ['language' => request()->input('language')]) }}"
          method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update SEO Informations') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Home Page') }}</label>
                  <input class="form-control" name="meta_keyword_home"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_home }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Home Page') }}</label>
                  <textarea class="form-control" name="meta_description_home" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_home }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Equipment Page') }}</label>
                  <input class="form-control" name="meta_keyword_equipment"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_equipment }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Equipment Page') }}</label>
                  <textarea class="form-control" name="meta_description_equipment" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_equipment }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Products Page') }}</label>
                  <input class="form-control" name="meta_keyword_products"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_products }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Products Page') }}</label>
                  <textarea class="form-control" name="meta_description_products" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_products }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Cart Page') }}</label>
                  <input class="form-control" name="meta_keyword_cart"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_cart }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Cart Page') }}</label>
                  <textarea class="form-control" name="meta_description_cart" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_cart }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Blog Page') }}</label>
                  <input class="form-control" name="meta_keyword_blog"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_blog }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Blog Page') }}</label>
                  <textarea class="form-control" name="meta_description_blog" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_blog }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For FAQ Page') }}</label>
                  <input class="form-control" name="meta_keyword_faq"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_faq }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For FAQ Page') }}</label>
                  <textarea class="form-control" name="meta_description_faq" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_faq }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Contact Page') }}</label>
                  <input class="form-control" name="meta_keyword_contact"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_contact }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Contact Page') }}</label>
                  <textarea class="form-control" name="meta_description_contact" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_contact }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Login Page') }}</label>
                  <input class="form-control" name="meta_keyword_login"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_login }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_login" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Signup Page') }}</label>
                  <input class="form-control" name="meta_keyword_signup"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_signup }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Signup Page') }}</label>
                  <textarea class="form-control" name="meta_description_signup" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_signup }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keyword_forget_password"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_forget_password }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_description_forget_password" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_forget_password }}</textarea>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keywords_vendor_forget_password"
                    value="{{ is_null($data) ? '' : $data->meta_keywords_vendor_forget_password }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_descriptions_vendor_forget_password" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_descriptions_vendor_forget_password }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Signup Page') }}</label>
                  <input class="form-control" name="meta_keywords_vendor_signup"
                    value="{{ is_null($data) ? '' : $data->meta_keywords_vendor_signup }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Signup Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_signup" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_vendor_signup }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Login Page') }}</label>
                  <input class="form-control" name="meta_keywords_vendor_login"
                    value="{{ is_null($data) ? '' : $data->meta_keywords_vendor_login }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_login" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_vendor_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Page') }}</label>
                  <input class="form-control" name="meta_keywords_vendor_page"
                    value="{{ is_null($data) ? '' : $data->meta_keywords_vendor_page }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_page" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($data) ? '' : $data->meta_description_vendor_page }}</textarea>
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
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
