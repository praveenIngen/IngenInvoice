{{ Form::model($chartOfAccountType, array('route' => array('chart-of-account-type.update', $chartOfAccountType->id), 'method' => 'PUT','class'=>'needs-validation')) }}
<div class="card card-box">
    <div class="row">
        <div class="form-group" style="padding: 6%;">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
</div>
    {{ Form::close() }}

