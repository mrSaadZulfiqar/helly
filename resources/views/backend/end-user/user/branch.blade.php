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
      .delete_manager{
          display:contents;
      }
      .delete_manager button{
          background-color:transparent;
          color:#fff;
          border:none;
      }
  </style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Branches') }}</h4>
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
        <a href="#">{{ __('Branches') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Branches') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.user_management.bulk_delete_user') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.user_management.branches') }}" method="GET">
                <input name="info" type="text" class="form-control minw-230"
                  placeholder="Search By Branch Name"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($branches) == 0)
                <h3 class="text-center mt-2">{{ __('NO Branch FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Location') }}</th>
                        <th scope="col">{{ __('Company') }}</th>
                        <th scope="col">{{ __('Managers') }}</th>
                        <!--<th scope="col">{{ __('Actions') }}</th>-->
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($branches as $branch)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $branch->id }}">
                          </td>
                          <td>{{ $branch->name }}</td>
                          <td>{{ $branch->location }}</td>
                          @php
                            $company = \App\Models\Company::find($branch->company_id);
                            $branch_manager_ids = \App\Models\BranchUser::where('branch_id',$branch->id)->get()->pluck('user_id');
                            $managers = \App\Models\User::whereIn('id',$branch_manager_ids)->get();
                            $branch_ids = \App\Models\BranchUser::where('branch_id',$branch->id)->get();
                          @endphp
                          <td>{{ $company->name }}</td>
                          <td>
                              @foreach($managers as $key => $manager)
                              <span class="manager_tag">{{ $manager->username }} </span>
                              @endforeach
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
              {{ $branches->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

@endsection
