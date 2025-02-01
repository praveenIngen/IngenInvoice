@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection
@push('script-page')
    <script>
        function copyToClipboard(element) {

            var copyText = element.id;
            navigator.clipboard.writeText(copyText);
            // document.addEventListener('copy', function (e) {
            //     e.clipboardData.setData('text/plain', copyText);
            //     e.preventDefault();
            // }, true);
            //
            // document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
        
       
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: '{{ __("Invoice") }}',
                        data:  {!! json_encode($invoiceTotal) !!},
                        left:200,
                    },
                ],

                chart: {
                    height: 300,
                    type: 'bar',
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
                    text: 'Invoice Summary ',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($monthList) !!},
                    title: {
                        text: '{{ __("Months") }}'
                    }
                },
                colors: ['#145388', '#145388'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                // markers: {
                //     size: 4,
                //     colors: ['#ffa21d', '#FF3A6E'],
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
                        text: 'Amounts'
                    },
                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();

    </script>
@endpush


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__("Invoice")}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        {{--        <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{__('Filter')}}">--}}
        {{--            <i class="ti ti-filter"></i>--}}
        {{--        </a>--}}

        <!-- <a href="{{ route('invoice.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class="ti ti-file-export"></i>
        </a> -->

        @can('create invoice')
            <a href="{{ route('invoice.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ti ti-plus"></i> Create Invoice
            </a>
        @endcan
    </div>
@endsection



@section('content')
<div class="row">
        <div class="col-md-12">
        <div class="card">
        <div class="col-sm-12">
            <div class="scrollbar-inner">
                <div id="chart-sales" data-color="primary" data-type="bar" data-height="300" ></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['invoice.index'], 'method' => 'GET', 'id' => 'customer_submit']) }}
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-label'])}}
                                    {{ Form::date('issue_date', isset($_GET['issue_date'])? $_GET['issue_date']:'', array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box">
                                        {{ Form::label('customer', __('Customer'),['class'=>'form-label'])}}
                                        {{ Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control select']) }}
                                    </div>
                                </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'),['class'=>'form-label'])}}
                                    {{ Form::select('status', [''=>'Select Status'] + $status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('customer_submit').submit(); return false;"
                                   data-toggle="tooltip" data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('invoice.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                   data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{ __('Invoice') }}</th>
{{--                                @if (!\Auth::guard('customer')->check())--}}
{{--                                    <th>{{ __('Customer') }}</th>--}}
{{--                                @endif--}}
                                <th>{{ __('Issue Date') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Due Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th class="actionTab">{{ __('Action') }}</th>
                                @endif
                                {{-- <th>
                                <td class="barcode">
                                    {!! DNS1D::getBarcodeHTML($invoice->sku, "C128",1.4,22) !!}
                                    <p class="pid">{{$invoice->sku}}</p>
                                </td>
                            </th> --}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if ($invoice->due_date < date('Y-m-d'))
                                            <p class="text-danger mt-3">
                                                {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->priceFormat($invoice->getDue()) }}</td>
                                    <td>
                                        @if ($invoice->status == 0)
                                            <span
                                                class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span
                                                class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span
                                                class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span
                                                class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span
                                                class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                                <span>
                                                @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    @can('show invoice')
                                                         <div class="action-btn bg-info ms-2">
                                                                   <a href="{{ route('invoice.print', \Crypt::encrypt($invoice->id)) }}" id="printInvoice"
                                                                      class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"  target="print_frame" title="print "
                                                                      data-original-title="{{ __('Detail') }}">
                                                                       <i class="ti ti-file text-white"></i>
                                                                    </a>
                                                                </div>
                                                                <iframe name="print_frame" hidden width=0 height=0 frameborder=0 ></iframe>
                                                    @endcan
                                                    @can('duplicate invoice')
                                                        <div class="action-btn bg-primary ms-2">
                                                           {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id]) !!}

                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-toggle="tooltip"
                                                               data-original-title="{{ __('Duplicate') }}" data-bs-toggle="tooltip" title="Duplicate Invoice"
                                                               data-confirm="You want to make a duplicate copy of this Invoice. Press Yes to continue or Cancel to go back"
                                                               data-confirm-yes="document.getElementById('duplicate-form-{{ $invoice->id }}').submit();">
                                                                <i class="ti ti-copy text-white"></i>
                                                                {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id]) !!}
                                                                {!! Form::close() !!}
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('show invoice')
{{--                                                        @if (\Auth::guard('customer')->check())--}}
{{--                                                            <div class="action-btn bg-info ms-2">--}}
{{--                                                                    <a href="{{ route('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}"--}}
{{--                                                                       class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "--}}
{{--                                                                       data-original-title="{{ __('Detail') }}">--}}
{{--                                                                        <i class="ti ti-eye text-white"></i>--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                        @else--}}
                                                            <div class="action-btn bg-info ms-2">
                                                                    <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                                       class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "
                                                                       data-original-title="{{ __('Detail') }}">
                                                                        <i class="ti ti-eye text-white"></i>
                                                                    </a>
                                                                </div>
{{--                                                        @endif--}}
                                                    @endcan
                                                    @can('edit invoice')
                                                        <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ route('invoice.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                   class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit "
                                                                   data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                    @endcan
                                                    @can('delete invoice')
                                                        <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id], 'id' => 'delete-form-' . $invoice->id]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                                                       data-original-title="{{ __('Delete') }}"
                                                                       data-confirm="{{  __('This action will delete the Invoice permanently. Do you want to continue?') }}"
                                                                       data-confirm-yes="document.getElementById('delete-form-{{ $invoice->id }}').submit();">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    @endcan
                                                </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myPartialView">
  
 </div>
@endsection
