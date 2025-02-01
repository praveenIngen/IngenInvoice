@extends('layouts.admin')
@section('page-title')
    {{__('Manage Chart of Account Type')}}
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create constant chart of account type')
            <a href="#" data-url="{{ route('chart-of-account-type.create') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Account Type') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> Create Account Type
            </a>
            <a href="#" data-url="{{ route('charofAccount.createSubType') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Account Sub Type') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> Create Account Sub Type
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('type Name')}}</th>
                                <th> {{__('Sub type Name')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                          
                            @foreach ($account_subtype as $key => $type)
                          
                            <tr>
                               <td>{{ $key }}</td>
                               <td></td>
                               @foreach ($type as $skey => $subtypename)
                               @if($skey==0)
                               <td class="Action">
                               <span>
                                            @can('edit constant chart of account type')
                                                <a href="#" class="edit-icon" data-url="{{ route('chart-of-account-type.edit',$type[$skey]['typeId']) }}" data-ajax-popup="true" data-title="{{__('Edit Unit')}}" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-primary"></i>
                                            </a>
                                            @endcan
                                            @can('delete constant chart of account type')
                                                <a href="#" class="delete-icon" data-bs-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$type[$skey]['typeId']}}').submit();">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['chart-of-account-type.destroy', $type[$skey]['typeId']],'id'=>'delete-form-'.$type[$skey]['typeId']]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </span>
                               </td>
                               @endif
                               @endforeach
                            </tr>
                          
                                @foreach ($type as $skey => $subtypename)
                                <tr>
                                    <td></td>
                                    <td> {{$subtypename['name']}}</td>    
                                    <td class="Action">
                                    <span>
                                            @can('edit constant chart of account type')
                                                <a href="#" class="edit-icon" data-url="{{ route('charofAccount.subTypeEdit',$subtypename['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Unit')}}" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-primary"></i>
                                            </a>
                                            @endcan
                                            @can('delete constant chart of account type')
                                                <a href="#" class="delete-icon" data-bs-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$subtypename['id']}}').submit();">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['chart-of-account-type.destroy', $subtypename['id']],'id'=>'delete-form-'.$subtypename['id']]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </span>
                                    </td>
                                </tr>
                                @endforeach 
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
