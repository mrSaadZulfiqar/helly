@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Methods') }}
@endsection

@section('content')
  

  <!--====== Start Product Orders Section ======-->
  <section class="user-dashboard">
    <div class="container-fluid">
      <div style="min-height: 100vh" class="row">
        <div class="col-lg-3">
          @includeIf('frontend.user.side-navbar')
        </div>
        <div class="col-lg-9">
          <div style="padding-block: 20px">
            <div class="row">
              <div class="col-lg-12">
                <div class="user-profile-details">
                  <div class="account-info">
                    <div class="title">
                      <h4>{{ __('Payment Methods') }}</h4>
                    </div>
                    <div class="title">
                      <a href="{{ route('user.add_payment_methods') }}" class="btn btn-primary">Add Payment Method</a>
                    </div>
  
                    <div class="main-info">
                      @if (count($cards) == 0)
                        <div class="row text-center mt-2">
                          <div class="col">
                            <h4>{{ __('No Payment Methods Found') . '!' }}</h4>
                          </div>
                        </div>
                      @else
                        <div class="main-table">
                          <div class="table-responsive">
                            <table id="user-datatable" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                              <thead>
                                <tr>
                                  <th>{{ __('First Name') }}</th>
                                  <th>{{ __('Last Name') }}</th>
                                  <th>{{ __('Card') }}</th>
                                  <th>{{ __('CVV') }}</th>
                                  <th>{{ __('Expiry Month') }}</th>
                                  <th>{{ __('Expiry Year') }}</th>
                                  @if(auth()->user()->account_type == 'corperate_account' && auth()->user()->owner_id == null)
                                  <th>{{ __('Assigned Branch') }}</th>
                                  @endif
                                  <th>{{ __('Status') }}</th>
                                      <th>{{ __('Action') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($cards as $card)
                                  <tr>
                                    <td>{{ $card->first_name }}</td>
                                    <td>{{ $card->last_name }}</td>
                                    <td>{{ $card->card_number }}</td>
                                    <td>{{ $card->cvv }}</td>
                                    <td>{{ $card->exp_month }}</td>
                                    <td>{{ $card->exp_year }}</td>
                                      @if(auth()->user()->account_type == 'corperate_account' && auth()->user()->owner_id == null)
                                      @php
                                          $branch = \App\Models\CompanyBranch::find($card->branch_id);
                                      @endphp
                                          <th><span class="badge badge-success"> {{ $branch->name ?? "" }} </span></th>
                                      @endif
                                    <td>{!! $card->is_default == 1 ? "<span class=\"badge badge-success\"> Default </span>" : '' !!}</td>
                                    <td>
                                      <a class="text-primary"
                                        href="{{ route('user.edit_payment_method', ['id' => $card->id]) }}">
                                        <span class="btn-label">
                                          <i class="fas fa-edit"></i>
                                        </span>
                                      </a>
          
                                      <form class="deleteForm d-inline-block"
                                        action="{{ route('user.delete_payment_method', ['id' => $card->id]) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="text-danger deleteBtn">
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
                        </div>
                      @endif
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
  <!--====== End Product Orders Section ======-->
@endsection
