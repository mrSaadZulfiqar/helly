@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Admins') }}</h4>
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
        <a href="#">{{ __('Admin Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Admins') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Admins') }}</div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i> {{ __('Add Admin') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($admins) == 0)
                <h3 class="text-center mt-2">{{ __('NO ADMIN FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Profile Picture') }}</th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Role') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($admins as $admin)

                        <?php 
                        // code by AG start
                        $admin_info = App\Models\AdminInfo::where('user_id',$admin->id)->first(); 
                        // code by AG end
                        ?>
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            <img src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="admin image" width="45">
                          </td>
                          <td>{{ $admin->username }}</td>
                          <td>{{ $admin->email }}</td>
                          <td>{{ $admin->roleName }}</td>
                          <td>
                            <form id="statusForm-{{ $admin->id }}" class="d-inline-block" action="{{ route('admin.admin_management.update_status', ['id' => $admin->id]) }}" method="post">
                              @csrf
                              <select class="form-control form-control-sm {{ $admin->status == 1 ? 'bg-success' : 'bg-danger' }}" name="status" onchange="document.getElementById('statusForm-{{ $admin->id }}').submit()">
                                <option value="1" {{ $admin->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $admin->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#" data-toggle="modal" data-target="#editModal" data-id="{{ $admin->id }}" data-role_id="{{ $admin->role_id }}" data-first_name="{{ $admin->first_name }}" data-last_name="{{ $admin->last_name }}" data-image="{{ asset('assets/img/admins/' . $admin->image) }}" data-username="{{ $admin->username }}" data-email="{{ $admin->email }}" data-phone="{{ $admin_info->phone??'' }}" data-message_bird_phone="{{ $admin_info->message_bird_phone??'' }}" data-voximplant_phone="{{ $admin_info->voximplant_phone??'' }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.admin_management.delete_admin', ['id' => $admin->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.administrator.site-admin.create')

  {{-- edit modal --}}
  @include('backend.administrator.site-admin.edit')
@endsection

@section('script')
<script>
    // code by AG start
     $(document).ready(function(){
        
        $('#createModal .agcd-phone-intl').each(function(){
            var name_key = $(this).attr('name');

            $(this).attr('id','phone_helper_'+name_key);
            var input__ = document.querySelector("#phone_helper_"+name_key);
            $("#phone_helper_"+name_key).after('<input type="hidden" name="'+name_key+'" id="'+name_key+'">');
            $("#phone_helper_"+name_key).attr('name','');
            var iti = window.intlTelInput(input__, {
                separateDialCode: true,
            });
            var fullNumber = iti.getNumber();
            $("#"+name_key).val(fullNumber);
            $("#phone_helper_"+name_key).on('change countrychange', function() {
                var fullNumber = iti.getNumber();
                $("#"+name_key).val(fullNumber);
            });
        });
        setup_edit_phone_fields();
        
        $(document).on('click','.editBtn', function(){
            setup_edit_phone_fields();
        });

        function setup_edit_phone_fields(){
            $('#editModal .agcd-phone-intl').each(function(){
                var name_key = $(this).attr('name');
                if(name_key == ''){
                    name_key = $(this).attr('data-namef');
                }else{
                    $(this).attr('data-namef', name_key);
                }

                $("#edit_"+name_key).remove();
                $(this).attr('id','in_'+name_key);
                
                var input__ = document.querySelector("#in_"+name_key);
                $("#in_"+name_key).after('<input type="hidden" name="'+name_key+'" id="edit_'+name_key+'">');
                $("#in_"+name_key).attr('name','');
                var iti = window.intlTelInput(input__, {
                    separateDialCode: true,
                });
                var fullNumber = iti.getNumber();
                $("#edit_"+name_key).val(fullNumber);
                $("#in_"+name_key).on('change countrychange', function() {
                    var fullNumber = iti.getNumber();
                    $("#edit_"+name_key).val(fullNumber);
                });
            });
        }
        
    });
    // code by AG start
</script>
@endsection