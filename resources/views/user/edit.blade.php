<style>
    #input-wrapper .CountryCode {
  position: absolute;
}

#input-wrapper span.CountryCode {
    z-index: 99;
    /* line-height: 25px; */
    padding: 8px;
    position: fixed;
    top: 57%;
    margin-left: -7%;
}

#input-wrapper .form-control {
  /* height: 25px; */
  text-indent: 35px;
  display: block;
    width: 100%;
    padding: 0.575rem 1rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #293240;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 6px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
 </style>
{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT','id'=>'userUpdate', 'class'=>'needs-validation')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                {{Form::label('name',__('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('User Name'),'required' => 'required'))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('User Email'), 'required' => 'required'))}}
                @error('email')
                <small class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('trade_name', __('Trade Name'), ['class' => 'form-label']) }}
                    {{ Form::text('trade_name',$sellerDetail?$sellerDetail->trade_name:null, ['class' => 'form-control', 'placeholder' => __('Trade Name')]) }}
                    @error('trade_name')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('vat_registration_number', __('Vat Registration Number'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('vat_registration_number', $sellerDetail?$sellerDetail->vat_registration_number:null, ['class' => 'form-control', 'placeholder' => __('Vat Registration Number'),'maxlength'=>'8', 'minlength'=>'8', 'required' => 'required']) }}
                    @error('vat_registration_number')
                        <small class="" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('business_registration_number', __('Business Registration Number'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('business_registration_number', $sellerDetail?$sellerDetail->business_registration_number:null, ['class' => 'form-control', 'placeholder' => __('Business Registration Number'),  'maxlength'=>'10', 'minlength'=>'8', 'required' => 'required']) }}
                    @error('business_registration_number')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('business_address', __('Business Address'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('business_address', $sellerDetail?$sellerDetail->business_address:null, ['class' => 'form-control', 'placeholder' => __('Business Address'), 'required' => 'required']) }}
                    @error('business_address')
                        <small class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="input-wrapper">
                    {{ Form::label('contact_number', __('Contact Number'), ['class' => 'form-label']) }}
                    <span class="CountryCode" readonly>+230</span>
                    {{ Form::number('contact_number', $sellerDetail?$sellerDetail->contact_number:null, ['class' => 'form-control','maxlength'=>'15', 'minlength'=>'9', 'placeholder' => __('Contact Number')]) }}
                    @error('contact_number')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ebs_counter_number', __('EBS Counter Number'), ['class' => 'form-label']) }}
                    {{ Form::text('ebs_counter_number', $sellerDetail?$sellerDetail->ebs_counter_number:null, ['class' => 'form-control', 'placeholder' => __('EBS Counter Number')]) }}
                    @error('ebs_counter_number')
                        <small class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        @if(\Auth::user()->type != 'super admin')
            <div class="form-group col-md-12">
                {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}<x-required></x-required>
                {!! Form::select('role', $roles, $user->roles,array('class' => 'form-control select','required'=>'required')) !!}
                @error('role')
                <small class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        @endif
        @if(!$customFields->isEmpty())
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light"data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary" id="userUpdateSubmit">
</div>

{{Form::close()}}
<script>
   $(document).on('click', '#userUpdateSubmit', function (event)
   {
       var variableName=['name','email','vat_registration_number','business_registration_number','business_address'];
       var submitData=false;
       $.each(variableName, function (key, value) {
        var errormessage="";
            var valueData=$("#"+value).val();
            var maxAttr=$("#"+value).attr('maxlength');
            var type=$("#"+value).attr('type');
            var minAttr=$("#"+value).attr('minlength');
            // alert(value+maxAttr)
            var validEmailRegEx = /^[A-Z0-9_'%=+!`#~$*?^{}&|-]+([\.][A-Z0-9_'%=+!`#~$*?^{}&|-]+)*@[A-Z0-9-]+(\.[A-Z0-9-]+)+$/i;
            var pattern =$("#"+value).attr('pattern');
            var exp = new RegExp(pattern);
            if(valueData=="" || (pattern !=undefined && !exp.test($("#"+value).val()))){
                errormessage="Please fill the valid data \n";
            }else if(type=="email" && validEmailRegEx.test(valueData)===false){
                    errormessage="Please fill the valid email";
            }else if(type!=="email" && maxAttr!==undefined && minAttr!==undefined && maxAttr === minAttr && (valueData!="" && (valueData.length>maxAttr || valueData.length<minAttr))){
                errormessage="Please fill the valid data which must be "+ minAttr+" digit "+type+" \n";
            }else if((maxAttr!=undefined && minAttr!=undefined) && (valueData=="" || (valueData!="" && (valueData.length>maxAttr || valueData.length<minAttr)))){
                 errormessage="Please fill the valid data  with minimum "+minAttr+" digit "+type+" and maximum "+maxAttr+ " digit  "+type+"\n";
            }
            var regex =/[^\w\s]/gi;
            //Validate TextBox value against the Regex.
            var isnotValid = regex.test(valueData);
            var nameregex=/^[a-zA-Z\s]+$/;;
            if((type!="email" && isnotValid) || (value=="name" && !nameregex.test(valueData))){
                errormessage="Please fill the valid data in valid format. \n";
                $('#'+value).addClass("error");
            }
            if(errormessage!=""){
                $('#'+value).next('.error-message').remove();
                $errorSpan = $('<span>').addClass('error-message').text(errormessage);
                $('#'+value).addClass("error");
                $('#'+value).after($errorSpan);
               
                submitData=false;
            }else{
                // $errorSpan = $('<span>').removeClass('error-message').text();
                $('#'+value).removeClass("error");
                $('#'+value).next('.error-message').remove();
                submitData=true;
            }
        });
       if(submitData===true &&  ($('#'+variableName[0]).hasClass("error")==false) && 
           ($('#'+variableName[1]).hasClass("error")==false) && ($('#'+variableName[2]).hasClass("error")==false) && 
           ($('#'+variableName[3]).hasClass("error")==false) && ($('#'+variableName[4]).hasClass("error")==false)){
        $("#userUpdate").submit();
       }else{
        return false;
       }
    });