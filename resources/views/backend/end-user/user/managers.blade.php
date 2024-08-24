@extends('backend.layout')

@section('content')
<style>
    .manager_tag{
        font-size: 11px;
        background-color: #0e2b5c;
        color: #fff;
        padding: 3px 10px;
        border-radius: 20px;
      }
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Managers') }}</h4>
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
        <a href="#">{{ __('Managers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Managers') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.user_management.bulk_delete_user') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.user_management.registered_users') }}" method="GET">
                <input name="info" type="text" class="form-control minw-230"
                  placeholder="Search By Username or Email ID"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($managers) == 0)
                <h3 class="text-center mt-2">{{ __('NO Manager FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <!--<th scope="col">{{ __('Email ID') }}</th>-->
                        <th scope="col">{{ __('Company Name') }}</th>
                        <th scope="col">{{ __('Branches') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <!--<th scope="col">{{ __('Phone') }}</th>-->
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($managers as $manager)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $manager->id }}">
                          </td>
                          <td>{{ $manager->username }}</td>
                          @php
                            $company = \App\Models\Company::where('customer_id',$manager->owner_id)->first();
                            $branch_ids = \App\Models\BranchUser::where('user_id',$manager->id)->get()->pluck('branch_id');
                            $branches = \App\Models\CompanyBranch::whereIn('id',$branch_ids)->get();
                          @endphp
                          <td>@isset($company){{ $company->name }}@endisset</td>
                          <td>
                              @foreach($branches as $branch)
                                <span class="manager_tag">{{ $branch->name }}</span>
                              @endforeach
                          </td>
                          <td>
                            <form id="emailStatusForm-{{ $manager->id }}" class="d-inline-block"
                              action="{{ route('admin.user_management.user.update_email_status', ['id' => $manager->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ !is_null($manager->email_verified_at) ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $manager->id }}').submit()">
                                <option value="verified" {{ !is_null($manager->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="not verified" {{ is_null($manager->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Not Verified') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          
                          <td>
                            <form id="accountStatusForm-{{ $manager->id }}" class="d-inline-block"
                              action="{{ route('admin.user_management.user.update_account_status', ['id' => $manager->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $manager->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $manager->id }}').submit()">
                                <option value="1" {{ $manager->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="2" {{ $manager->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.user_management.user.details', ['id' => $manager->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                <a href="{{ route('admin.user_management.user.change_password', ['id' => $manager->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.user_management.user.delete', ['id' => $manager->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                                <a target="_blank"
                                  href="{{ route('admin.user_management.user.secret_login', ['id' => $manager->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Secret Login') }}
                                </a>
                              </div>
                            </div>
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

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              {{ $managers->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
