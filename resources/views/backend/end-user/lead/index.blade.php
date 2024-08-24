@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Leads') }}</h4>
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
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Leads') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Leads') }}</div>
              <a href="{{ route('admin.vendor_management.add_lead') }}" class="btn btn-secondary btn-sm">Add Lead</a>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.vendor_management.bulk_delete_lead') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.vendor_management.leads') }}" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="{{ __('Search By Username or Email ID') }}"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($leads) == 0)
                <h3 class="text-center">{{ __('NO LEADS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('SMS') }}</th>
                        <th scope="col">{{ __('Call') }}</th>
                        <th scope="col">{{ __('Convert To Vendor') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($leads as $lead)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $lead->id }}">
                          </td>
                          <td>{{ $lead->username }}</td>
                          <td>{{ $lead->email }}</td>
                          <td>{{ empty($lead->phone) ? '-' : $lead->phone }}</td>
                          <td>
                            <form id="accountStatusForm-{{ $lead->id }}" class="d-inline-block"
                              action="{{ route('admin.vendor_management.vendor.update_account_status', ['id' => $lead->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $lead->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $lead->id }}').submit()">
                                <option value="1" {{ $lead->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $lead->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <form id="emailStatusForm-{{ $lead->id }}" class="d-inline-block"
                              action="{{ route('admin.vendor_management.vendor.update_email_status', ['id' => $lead->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $lead->email_verified_at != null ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $lead->id }}').submit()">
                                <option value="1" {{ $lead->email_verified_at != null ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="0" {{ $lead->email_verified_at == null ? 'selected' : '' }}>
                                  {{ __('Unverified') }}
                                </option>
                              </select>
                            </form>
                          </td>

                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownChatButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-comment"></i>
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownChatButton">
                                <a class="dropdown-item ag-send-sms-btn" href="{{route('admin.vendor_management.lead.chat', $lead->id)}}?phone_={{$lead->phone}}" data-phone_="{{$lead->phone}}">{{$lead->phone}}</a>
                                <?php 
                                    $additional_contact = json_decode($lead->additional_contact, true);
                                ?>
                                <?php if( !empty( $additional_contact ) ){ ?>
                                    <?php foreach($additional_contact as $contact){ ?>
                                        <?php if($contact['phone_full'] != ''){ ?>
                                            <a class="dropdown-item ag-send-sms-btn" href="{{route('admin.vendor_management.lead.chat', $lead->id)}}?phone_={{$contact['phone_full']}}" data-phone_="{{$contact['phone_full']}}">{{$contact['phone_full']}}</a>
                                        <?php } ?>
                                         
                                    <?php } ?>
                                <?php } ?>
                              </div>
                            </div>
                          </td>

                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownCallButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-phone"></i>
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownCallButton">
                                <a class="dropdown-item ag-send-sms-btn" href="{{route('admin.vendor_management.lead.calling', $lead->id)}}?phone_={{$lead->phone}}" data-phone_="{{$lead->phone}}">{{$lead->phone}}</a>
                                <?php 
                                    $additional_contact = json_decode($lead->additional_contact, true);
                                ?>
                                <?php if( !empty( $additional_contact ) ){ ?>
                                    <?php foreach($additional_contact as $contact){ ?>
                                        <?php if($contact['phone_full'] != ''){ ?>
                                            <a class="dropdown-item ag-send-sms-btn" href="{{route('admin.vendor_management.lead.calling', $lead->id)}}?phone_={{$contact['phone_full']}}" data-phone_="{{$contact['phone_full']}}">{{$contact['phone_full']}}</a>
                                        <?php } ?>
                                         
                                    <?php } ?>
                                <?php } ?>
                              </div>
                            </div>
                          </td>
                        
                          <td>
                            @if($lead->converted_to_vendor)
                            <a class="badge badge-success" href="#">
                            Converted
                            </a>
                            @else
                            <a class="badge badge-secondary" href="{{ route('admin.vendor_management.lead_convert_to_vendor', ['id' => $lead->id]) }}">
                            Convert
                            </a>
                            @endif
                          </td>
                          <td>
                             <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php 
                                    $lead_remarks = App\Models\LeadRemark::join('admins', 'lead_remarks.remark_by', '=', 'admins.id')->select('lead_remarks.*', 'admins.username')->where('lead_id',$lead->id)->get()->toArray();
                                    
                                ?>
                                <a class="dropdown-item leadRemarkBtn" data-remarks="{{ json_encode($lead_remarks) }}" href="#" data-toggle="modal" data-target="#leadRemarkModal" data-id="{{ $lead->id }}" data-remarks="">
                                    {{ __('Remarks') }}
                                </a>

                                <a href="{{ route('admin.vendor_management.lead_details', ['id' => $lead->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                <a href="{{ route('admin.edit_management.lead_edit', ['id' => $lead->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <!-- <a href="{{ route('admin.vendor_management.vendor.change_password', ['id' => $lead->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a> -->

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.vendor_management.lead.delete', ['id' => $lead->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>

                                <!-- <a target="_blank"
                                  href="{{ route('admin.vendor_management.vendor.secret_login', ['id' => $lead->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Secret Login') }}
                                </a> -->
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
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $leads->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.end-user.lead.remarks')
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click', '.leadRemarkBtn', function(){
            $('.agcd-lead-remarks-list').html('');
            $('#leadRemarkModal #in_id').val($(this).attr('data-id'));
            var remarks = jQuery.parseJSON( $(this).attr('data-remarks') );
            
            $.each(remarks, function( index, value ) {
                $('.agcd-lead-remarks-list').append('<a href="#" class="list-group-item list-group-item-action"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1">'+value.remark+'</h5><small class="badge badge-secondary">'+value.created_at+'</small></div><p class="mb-1"></p><small>Remark By: <small class="badge badge-primary">'+value.username+'</small></small></a>');
            });
        });
    });
</script>
@endsection

