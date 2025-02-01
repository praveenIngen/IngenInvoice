
    {{ Form::open(['url' => 'chart-of-account-type' ,'method'=>'post','class'=>'needs-validation']) }}
    <div class="card card-box">
        <div class="form-group" style="padding: 3%;">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
         </div>
    </div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
    {{ Form::close() }}
