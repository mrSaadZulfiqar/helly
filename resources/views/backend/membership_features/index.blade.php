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
    <h4 class="page-title">{{ __('Features') }}</h4>
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
        <a href="#">{{ __('Features') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Features') }}</div>
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
              @if (count($features) == 0)
                <h3 class="text-center mt-2">{{ __('NO Feature FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Url') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($features as $feature)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $feature->id }}">
                          </td>
                          <td>{{ $feature->name }}</td>
                          <td><a href="{{ $feature->url }}">{{ $feature->url }}</a></td>
                          <td>{{ $feature->status == '1' ? 'Enable' : 'Disable' }}</td>
                          <td>
                               <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.features.edit', $id = $feature->id  ) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <!--<form class="deleteForm d-block"-->
                                <!--  action="{{ route('admin.features.destroy', $id = $feature->id  ) }}"-->
                                <!--  method="post">-->
                                <!--  @csrf-->
                                <!--  @method('DELETE')-->
                                <!--  <button type="submit" class="deleteBtn">-->
                                <!--    {{ __('Delete') }}-->
                                <!--  </button>-->
                                <!--</form>-->
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
              {{ $features->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

@endsection