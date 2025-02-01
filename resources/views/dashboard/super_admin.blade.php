@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection

@push('theme-script')
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
@endpush
@push('script-page')
    <script>
        (function () {
            var options = {
                chart: {
                    height: 400,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "{{__('Invoice total')}}",
                    data: {!! json_encode($monthlyInvoice['invoiceTotal']) !!}
                }, {
                    name: "{{__('invoice paid')}}",
                    data: {!! json_encode($monthlyInvoice['invoicePaid']) !!}
                }, {
                    name: "{{__('invoice due')}}",
                    data: {!! json_encode($monthlyInvoice['invoiceDue']) !!}
                }],
                xaxis: {
                    title: {
                        text: '{{ __("Months") }}'
                    },
                    categories: {!! json_encode($monthList) !!},
                },
                colors: ['#3ec9d6', '#2E7B7F','#FF3A6E'],
                fill: {
                    type: 'solid',
                },
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                // markers: {
                //     size: 4,
                //     colors:  ['#3ec9d6', '#FF3A6E',],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // }
                noData: {
                    text: "No record exist",
                    align: "center",
                    verticalAlign: "middle",
                },
                yaxis: {
                    title: {
                        text: '{{ __("Amount") }}'
                    }

                }
            };
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();
    </script>
@endpush
@php
$admin_payment_setting = Utility::getAdminPaymentSetting();
@endphp

@section('content')
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
                                <h6 class="ml-4">{{__('Total Companies')}}</h6>
                            </div>
                        </div>

                        <div class="number-icon ms-3 "><h6>{{$user->total_user}}</h6></div>
                            <div class="ms-3 ">
                                <h6>{{__('Paid Users')}} : {{$user['total_paid_user']}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center ">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-shopping-cart"></i>
                            </div>
                            <div class="ms-3 ">
                                <h6 class="ml-4">{{__('Total Orders')}}</h6>
                            </div>
                        </div>

                        <div class="number-icon ms-3 "><h6>{{$user->total_orders}}</h6></div>
                            <div class="ms-3 ">
                                <h6>{{__('Total Order Amount')}} : <span class="text-dark">{{isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '$'}}{{$user['total_orders_price']}}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center ">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-trophy"></i>
                            </div>
                            <div class="ms-3 ">
                                <h6 class="ml-4">{{__('Total Plans')}}</h6>
                            </div>
                        </div>

                        <div class="number-icon ms-3 "><h6>{{$user->total_plan}}</h6></div>
                            <div class="ms-3 ">
                                <h6>{{__('Most Purchase Plan')}} : <span class="text-dark">{{$user['most_purchese_plan']}}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12">
            <h4 class="h4 font-weight-400">{{__('Invoice Summary')}}</h4>
            <div class="card">
                <div class="chart">
                    <div id="incExpBarChart" data-color="primary" data-height="280" class="p-3"></div>
                </div>
            </div>
        </div>
    </div>


@endsection
