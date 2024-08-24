@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Invoices') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Invoices') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Invoices') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Invoices') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('vendors.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('vendor.invoice.create') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Invoice') }}</a>

              <!-- <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.driver_management.bulk_delete_driver') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button> -->
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($invoices) == 0)
                <h3 class="text-center mt-2">{{ __('NO Invoice FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Invoice Number') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Recurring cycle') }}</th>
                        
                        <th scope="col">{{ __('Client') }}</th>
						<th scope="col">{{ __('Issue date') }}</th>
						<th scope="col">{{ __('Due date') }}</th>
						<th scope="col">{{ __('Total') }}</th>
						<th scope="col">{{ __('Paid') }}</th>
						<th scope="col">{{ __('Amount due') }}</th>
						<th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($invoices as $invoice)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $invoice['id'] }}">
                          </td>
                          <td scope="col">{{ __('Invoice Number') }}</td>
							<td scope="col">{{ __('Status') }}</td>
							<td scope="col">{{ __('Recurring cycle') }}</td>

							<td scope="col">{{ __('Client') }}</td>
							<td scope="col">{{ __('Issue date') }}</td>
							<td scope="col">{{ __('Due date') }}</td>
							<td scope="col">{{ __('Total') }}</td>
							<td scope="col">{{ __('Paid') }}</td>
							<td scope="col">{{ __('Amount due') }}</td>
							<td scope="col">{{ __('Actions') }}</td>
                          <td>
                            
                            
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Resend') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Download') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('View') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Stop Recurring') }}
                                </a>
								
                                <a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <form class="deleteForm d-inline-block"
                                  action="{{ route('vendor.invoice') }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>


                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
					  
					  <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="">
                          </td>
                          <td scope="col">{{ __('Invoice Number') }}</td>
							<td scope="col">{{ __('Status') }}</td>
							<td scope="col">{{ __('Recurring cycle') }}</td>

							<td scope="col">{{ __('Client') }}</td>
							<td scope="col">{{ __('Issue date') }}</td>
							<td scope="col">{{ __('Due date') }}</td>
							<td scope="col">{{ __('Total') }}</td>
							<td scope="col">{{ __('Paid') }}</td>
							<td scope="col">{{ __('Amount due') }}</td>
							<td scope="col">{{ __('Actions') }}</td>
                          <td>
                            
                            
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Resend') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Download') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('View') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Stop Recurring') }}
                                </a>
								
                                <a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <form class="deleteForm d-inline-block"
                                  action="{{ route('vendor.invoice') }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>


                              </div>
                            </div>
                          </td>
                        </tr>
						
						<tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="">
                          </td>
                          <td scope="col">{{ __('Invoice Number') }}</td>
							<td scope="col">{{ __('Status') }}</td>
							<td scope="col">{{ __('Recurring cycle') }}</td>

							<td scope="col">{{ __('Client') }}</td>
							<td scope="col">{{ __('Issue date') }}</td>
							<td scope="col">{{ __('Due date') }}</td>
							<td scope="col">{{ __('Total') }}</td>
							<td scope="col">{{ __('Paid') }}</td>
							<td scope="col">{{ __('Amount due') }}</td>
							<td scope="col">{{ __('Actions') }}</td>
                          <td>
                            
                            
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Resend') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Download') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('View') }}
                                </a>
								<a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Stop Recurring') }}
                                </a>
								
                                <a href="{{ route('vendor.invoice') }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <form class="deleteForm d-inline-block"
                                  action="{{ route('vendor.invoice') }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>


                              </div>
                            </div>
                          </td>
                        </tr>
						
						
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
@endsection
