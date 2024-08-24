@extends('frontend.layout')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Dashboard')])

  <!--====== Start Dashboard Section ======-->
  <section class="user-dashboard pt-130 pb-120">
    <div class="container">
        <div class="row">
        @includeIf('frontend.user.side-navbar')
        </div>

       <div class="row">

        <div class="col-lg-12">
          <div class="row mb-5">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Managers') }}</h4>
                  </div>    
                  <div class="main-info">
                      <div class="title d-flex justify-content-center mb-3">
                          <a class="btn btn-primary text-white" href="{{ route('user.manager.create') }}">Add Manager</a>
                      </div>
                      
                      <div class="table-responsive">
                          <table class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100"  id="manager-datatable" >
                              <thead>
                                  <tr>
                                      <th><input type="checkbox"></th>
                                      <th>Username</th>
                                      <th>Email</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                            <tbody>
                              @foreach($managers as $manager)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>{{ $manager->username }}</td>
                                    <td>{{ $manager->email }}</td>
                                    <td class="d-flex">
                                        <a class="btn btn-primary text-white mr-2" href="{{  route('user.manager.edit',[ 'id' => $manager->id ])}}"><i class="fa fa-edit"></i></a>
                                        <form action="{{  route('user.manager.delete',['id' => $manager->id ])}}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                      </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
  
  <!--====== End Dashboard Section ======-->
@section('script')
    <script>
        $('#manager-datatable').DataTable({
    ordering: false,
    responsive: true
  });
    </script>
@endsection
@endsection