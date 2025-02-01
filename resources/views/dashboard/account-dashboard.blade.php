@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Account')}}</li>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-lg-4 col-md-6">
                            



                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center ">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <div class="ms-3 ">
                                            <h6 class="ml-4">{{__('Total Customers')}}</h6>
                                        </div>
                                    </div>

                                    <div class="number-icon ms-3 "><h6>{{\Auth::user()->countCustomers()}}</h6></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center ">
                                            <div class="theme-avtar bg-primary">
                                            <i class="ti ti-report-money"></i>
                                            </div>
                                            <div class="ms-3 ">
                                                <h6 class="ml-4">{{__('Total Invoices')}}</h6>
                                            </div>
                                        </div>

                                        <div class="number-icon ms-3 "><h6>{{\Auth::user()->countInvoices()}}</h6></div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="scrollbar-inner ChartDataDashboard" >
                            <div id="chart-sales" data-color="primary" data-height="300"></div>
                        </div>
                    </div>
                </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{__('Recent Invoices')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Invoice Id</th>
                                        <th>{{__('Customer')}}</th>
                                        <th>{{__('Issue Date')}}</th>
                                        <th>{{__('Due Date')}}</th>
                                        <th>{{__('Amount')}}</th>
                                        <th>{{__('Status')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($recentInvoice as $invoice)
                                        <tr>
                                            <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                            <td>{{!empty($invoice->customer_name)? $invoice->customer_name:'' }} </td>
                                            <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                            <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                            <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                            <td>
                                                @if($invoice->status == 0)
                                                    <span class="p-2 px-3 rounded badge status_badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span class="p-2 px-3 rounded badge status_badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span class="p-2 px-3 rounded badge status_badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span class="p-2 px-3 rounded badge status_badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span class="p-2 px-3 rounded badge status_badge bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="text-center">
                                                    <h6>{{__('There is no recent invoice')}}</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#invoice_weekly_statistics" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Invoices Weekly Statistics')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#invoice_monthly_statistics" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Invoices Monthly Statistics')}}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="invoice_weekly_statistics" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceTotal'])}}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoicePaid'])}}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceDue'])}}</h4>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoice_monthly_statistics" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceTotal'])}}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoicePaid'])}}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 class="mb-0">{{__('Total')}}</h5>
                                                    <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                </td>
                                                <td>
                                                    <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceDue'])}}</h4>
                                                </td>
                                            </tr>
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
       
@endsection

@push('script-page')
    <script>
        if(window.innerWidth <= 500)
        {
            $('p').removeClass('text-sm');
        }


        (function() {
            var chartBarOptions = {
                series: [{
                    name: '{{ __('Profit') }}',
                    data: {!! json_encode($chartArr) !!},

                }, ],

                chart: {
                    height: 300,
                    type: 'area',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($sumarry['monthList']) !!},
                    title: {
                        text: '{{ __('Months') }}'
                    }
                },
                colors: ['#ffa21d', '#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                // markers: {
                //     size: 4,
                //     colors: ['#6fd944', '#6fd944'],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // },
                noData: {
                    text: "No record exist",
                    align: "center",
                    verticalAlign: "middle",
                },
                yaxis: {
                    title: {
                        text: '{{ __('Profit') }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
    </script>
@endpush
