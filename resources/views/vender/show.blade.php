@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Manage Vendor-Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('vender.index')}}">{{__('Vendor')}}</a></li>
    <li class="breadcrumb-item">{{$vendor['name']}}</li>

@endsection

@section('action-btn')
    <div class="float-end">
     

        @can('edit vender')
            <a href="#" class="btn btn-sm btn-primary" data-size="xl" data-url="{{ route('vender.edit',$vendor['id']) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan
        @can('delete vender')
            {!! Form::open(['method' => 'DELETE', 'route' => ['vender.destroy', $vendor['id']],'class'=>'delete-form-btn','id'=>'delete-form-'.$vendor['id']]) !!}
            <a href="#" class="btn btn-sm btn-danger bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $vendor['id']}}').submit();">
                <i class="ti ti-trash text-white"></i>
            </a>
            {!! Form::close() !!}
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box vendor_card">
                <div class="card-body">
                    <h5 class="card-title">{{__('Vendor Info')}}</h5>
                    <p class="card-text mb-0">{{$vendor->name}}</p>
                    <p class="card-text mb-0">{{$vendor->email}}</p>
                    <p class="card-text mb-0">{{$vendor->contact}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box vendor_card">
                <div class="card-body">
                    <h3 class="card-title">{{__('Billing Info')}}</h3>
                    <p class="card-text mb-0">{{$vendor->billing_name}}</p>
                    <p class="card-text mb-0">{{$vendor->billing_address}}</p>
                    <p class="card-text mb-0">{{$vendor->billing_city.', '. $vendor->billing_state .', '.$vendor->billing_zip}}</p>
                    <p class="card-text mb-0">{{$vendor->billing_country}}</p>
                    <p class="card-text mb-0">{{$vendor->billing_phone}}</p>

                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box vendor_card">
                <div class="card-body">
                    <h3 class="card-title">{{__('Shipping Info')}}</h3>
                    <p class="card-text mb-0">{{$vendor->shipping_name}}</p>
                    <p class="card-text mb-0">{{$vendor->shipping_address}}</p>
                    <p class="card-text mb-0">{{$vendor->shipping_city.', '. $vendor->shipping_state .', '.$vendor->shipping_zip}}</p>
                    <p class="card-text mb-0">{{$vendor->shipping_country}}</p>
                    <p class="card-text mb-0">{{$vendor->shipping_phone}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <div class="card-body">
                    <h5 class="card-title">{{__('Company Info')}}</h5>
                    <div class="row">
                        @php
                            $totalBillSum=$vendor->vendorTotalBillSum($vendor['id']);
                            $totalBill=$vendor->vendorTotalBill($vendor['id']);
                            $averageSale=($totalBillSum!=0)?$totalBillSum/$totalBill:0;
                        @endphp
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Vendor Id')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->venderNumberFormat($vendor->vender_id)}}</h6>
                                <p class="card-text mb-0">{{__('Total Sum of Bills')}}</p>
                                <h6 class="report-text mb-0">{{\Auth::user()->priceFormat($totalBillSum)}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Date of Creation')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->dateFormat($vendor->created_at)}}</h6>
                                <p class="card-text mb-0">{{__('Quantity of Bills')}}</p>
                                <h6 class="report-text mb-0">{{$totalBill}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Balance')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->priceFormat($vendor->balance)}}</h6>
                                <p class="card-text mb-0">{{__('Average Sales')}}</p>
                                <h6 class="report-text mb-0">{{\Auth::user()->priceFormat($averageSale)}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Overdue')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->priceFormat($vendor->vendorOverdue($vendor->id))}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
@endsection
