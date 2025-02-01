{{ Form::open(['url' => 'chart-of-account-type.storeSubType' ,'method'=>'post','class'=>'needs-validation']) }}
    <div class="card card-box" style="padding: 3%;">
        <div class="form-group" >
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
         </div>
         <div class="form-group">
            {{ Form::label('sub_type', __('Account Type'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::select('sub_type', $account_type, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>
    </div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
    {{ Form::close() }}
