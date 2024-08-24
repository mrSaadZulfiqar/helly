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
    <h4 class="page-title">{{ __('Membership Plans') }}</h4>
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
        <a href="#">{{ __('Membership') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Membership Plans') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Plans') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <!--<button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"-->
              <!--  data-href="{{ route('admin.user_management.bulk_delete_user') }}">-->
              <!--  <i class="flaticon-interface-5"></i> {{ __('Delete') }}-->
              <!--</button>-->

              <form class="float-right" action="{{ route('admin.user_management.branches') }}" method="GET">
                <input name="info" type="text" class="form-control minw-230"
                  placeholder="Search By Name"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($plans) == 0)
                <h3 class="text-center mt-2">{{ __('NO Plan FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Features') }}</th>
                        <th scope="col">{{ __('Validity') }}</th>
                        <th scope="col">{{ __('Trial Days') }}</th>
                        <th scope="col">{{ __('Level') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($plans as $plan)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $plan->id }}">
                          </td>
                          <td>{{ $plan->name }}</td>
                          <td><a href="{{ route('admin.plan.feature' , $id = $plan->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Manage</a></td>
                          <td>{{ $plan->validity }} Days</td>
                          <td>{{ $plan->trial_days }} Days</td>
                          <td>{{ $plan->level }}</td>
                          <td>{{ $plan->price }}</td>
                          <td>{{ $plan->status == '1' ? 'Enable' : 'Disable' }}</td>
                          <td>
                               <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.plans.edit', $id = $plan->id  ) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.plans.destroy', $id = $plan->id  ) }}"
                                  method="post">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
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
              {{ $plans->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

@endsection