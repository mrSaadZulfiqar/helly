@extends('frontend.layout')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Dashboard')])
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
          position: absolute;
          top: 2px;
          right: 9px;

      }
      .manager_card{
          border-radius:20px;
          padding:15px;
          display:flex;
          justify-content:center;
          align-items:center;
          flex-direction:column;
          position:relative;
          background-color:#f3f3f3;
          box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
      }
      .manager_card_info{
          border-radius:20px;
          padding:10px;
          display:flex;
          justify-content:center;
          align-items:center;
          flex-direction:column;
          position:relative;
      }
      .delete_manager
      {
          position:absolute;
          top:0px;
          right:0px;
      }
      .delete_manager button i
      {
          color:#000;
      }
  </style>

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
                    <h4>{{ __('Branches') }}</h4>
                  </div>    
                  <div class="main-info">
                      <div class="title d-flex justify-content-center mb-3">
                          <a class="btn btn-primary text-white" href="{{ route('user.branch.create') }}">Add Branch</a>
                      </div>
                      
                      <div class="table-responsive">
                          <table class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100"  id="branch-datatable">
                              <thead>
                              <tr>
                                  <th><input type="checkbox"></th>
                                  <th>Branch</th>
                                  <th>Company</th>
                                  <th>Managers</th>
                                  <th>Location</th>
                                  <th>Action</th>
                              </tr>
                              </thead>
                            <tbody>
                              @foreach($branches as $branch)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>{{ $branch->name }}</td>
                                    @php
                                        $company = \App\Models\Company::find($branch->company_id);
                                        $branch_ids = \App\Models\BranchUser::where('branch_id',$branch->id)->get();
                                        $branch_manager_ids = \App\Models\BranchUser::where('branch_id',$branch->id)->get()->pluck('user_id');
                                        $branch_managers = \App\Models\User::whereIn('id',$branch_manager_ids)->get();
                                    @endphp
                                    <td>{{ $company->name }}</td>
                                    <td>
                                        <button class="btn my-1 btn-primary d-block btn-sm ManagerModal" data-branch-id="{{ $branch->id }}" ><i class="fa fa-edit"></i> Manage</button>
                                    </td>
                                    <td>{{ $branch->location }}</td>
                                    <td class="d-flex">
                                        <a class="btn btn-primary text-white mr-2" href="{{  route('user.branch.edit',[ 'id' => $branch->id ])}}"><i class="fa fa-edit"></i></a>
                                        <form action="{{  route('user.branch.delete',['id' => $branch->id ])}}" method="POST">
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
  
<!-- Modal -->
<div class="modal fade" id="addManager" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('user.branch.manager.assign') }}">
            @csrf
            <input id="branch_id" name="branch_id" hidden>
            <label>Managers</label>
            <select name="user_id" required class="form-control">
                <option selected disabled>Select Manager</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                @endforeach
            </select>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>
  
<!-- Modal -->
<div class="modal fade" id="Managers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">
              <div class="d-flex justify-content-end w-100">
                    <button class="btn my-1 btn-primary d-block btn-sm addManagerModal" id="addManagerModal"  ><i class="fa fa-plus mr-2"></i>Manager</button>                                      
              </div>
              <div class="row" id="manager_list">
            
                </div>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>
  
  <!--====== End Dashboard Section ======-->
@section('script')
    <script>
        $('#branch-datatable').DataTable({
            ordering: false,
            responsive: true
          });
          $('.addManagerModal').on('click',function(){
            //   alert('okay');
            const branch_id = $(this).attr('data-branch-id');
            $('#branch_id').val(branch_id);
            $("#Managers").modal('hide');
              $("#addManager").modal('show');
          });
          
          $(document).on('click','.ManagerModal',function(){
                const branch_id = $(this).data('branch-id');
                $.ajax({
                    type: "GET",
                    url: "{{ route('user.branch.manager.get_manager') }}",
                    data: { branch_id: branch_id },
                    success: function(response) {
                        console.log(response);
                        var html = "";
                        response.manager.forEach((item, index) => {
                            var image="";
                            var string = item.username;
                            var length = 8;
                            var trimmedString = string.substring(0, length)+'...';
                            if(item.image == null || item.image == "")
                            {
                                image= "https://static.thenounproject.com/png/363640-200.png";
                            }else{
                                image=item.image;
                            }
                           
                            
                            html += '<div class="col-md-2 col-sm-3 col-4 p-2">';
                            html += '<div class="manager_card">';
                            html += '<form method="POST" action="{{ route("user.branch.manager.unassign") }}" class="delete_manager">';
                            html += '@csrf';
                            html += '<input type="hidden" name="id" value="'+ response.manager_ids[index] +'">';
                            html += '<button type="submit"><i class="fa fa-times"></i></button>';
                            html += '</form>';
                            html += '<div class="manager_card_info">';
                            html += '<img width="auto" height="40px" src="'+ image +'">';
                            html += '<p>'+ trimmedString +'</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                        });
                        $("#manager_list").html(html);
                        $("#addManagerModal").attr('data-branch-id',branch_id);
                        $("#Managers").modal('show');
                    }
                });
            });

    </script>
@endsection
  

@endsection