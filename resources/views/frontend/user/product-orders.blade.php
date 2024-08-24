@extends('frontend.layout')

@section('pageHeading')
  {{ __('Product Orders') }}
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
                      <h4>{{ __('Order List') }}</h4>
                    </div>
  
                    <div class="main-info">
                      @if (count($orders) == 0)
                        <div class="row text-center mt-2">
                          <div class="col">
                            <h4>{{ __('No Order Found') . '!' }}</h4>
                          </div>
                        </div>
                      @else
                        <div class="main-table">
                          <div class="table-responsive">
                            <table id="user-datatable" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                              <thead>
                                <tr>
                                  <th>{{ __('Order Number') }}</th>
                                  <th>{{ __('Date') }}</th>
                                  <th>{{ __('Payment Status') }}</th>
                                  <th>{{ __('Action') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($orders as $order)
                                  <tr>
                                    <td>{{ '#' . $order->order_number }}</td>
                                    <td>{{ date_format($order->created_at, 'M d, Y') }}</td>
                                    <td>
                                      @if ($order->payment_status == 'completed')
                                        <span class="completed {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Completed') }}</span>
                                      @elseif ($order->payment_status == 'pending')
                                        <span class="pending {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Pending') }}</span>
                                      @else
                                        <span class="rejected {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Rejected') }}</span>
                                      @endif
                                    </td>
                                    <td>
                                      <a href="{{ route('user.product_order.details', ['id' => $order->id]) }}" class="btn">{{ __('Details') }}</a>
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
