  
{{Form::open(array('url'=>'customer','method'=>'post', 'id'=>'FormCreate','class'=>'needs-validation'))}}
<div class="modal-body">

    <h6 class="sub-title">{{__('Basic Info')}}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('name',__('Full Name'),array('class'=>'form-label')) }}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required' ,'placeholder'=>__('Full Name')))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group" >
             {{Form::label('contact',__('Contact'),['class'=>'form-label'])}}<x-required></x-required>
             <div class="input-group">
                        <span class="input-group-text">+230</span>
                         <input type="number" aria-label="contact" value="" maxlength="15"  minlength="9" name="contact" id="contact" placeholder="Contact" class="form-control">
                    </div>
             </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::email('email',null,array('class'=>'form-control' , 'placeholder'=>__('Email'),'required'=>'required'))}}

            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('tax_number',__('Vat Registration Number'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::number('tax_number',null,array('class'=>'form-control' ,'required' => 'required','maxlength'=>'8', 'minlength'=>'8', 'placeholder' => __('Vat Registration Number')))}}
            </div>
        </div>
        <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('business_registration_number', __('Business Registration Number'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('business_registration_number', null, ['class' => 'form-control','maxlength'=>'10', 'minlength'=>'8', 'placeholder' => __('Business Registration Number'), 'required' => 'required']) }}
                    @error('business_registration_number')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('nic_number', __('National Identification Number'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('nic_number', null, ['class' => 'form-control','maxlength'=>'14', 'minlength'=>'14', 'placeholder' => __('National Identification Number'), 'required' => 'required']) }}
                    @error('nic_number')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('buyer_type', __('Buyer Type'),['class'=>'form-label']) }}<x-required></x-required>
                    {{ Form::select('buyer_type',array('VATR' => 'VATR', 'NVTR' => 'NVTR','EXMP'=>'EXMP'),null, array('class' => 'form-control select','required'=>'required')) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('transaction_nature', __('Transaction Nature'),['class'=>'form-label']) }}<x-required></x-required>
                    {{ Form::select('transaction_nature',array('B2B' => 'B2B', 'B2G' => 'B2G','B2C'=>'B2C'),null, array('class' => 'form-control select','required'=>'required')) }}
                </div>
            </div>
            
        @if(!$customFields->isEmpty())
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

    <h6 class="sub-title">{{__('Billing Address')}}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label')) }}
                {{Form::text('billing_name',null,array('class'=>'form-control' , 'placeholder'=>__('Name')))}}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
                {{Form::number('billing_phone',null,array('class'=>'form-control' , 'placeholder'=>__('Phone')))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('billing_address',__('Address'),array('class'=>'form-label')) }}
                {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Address')))}}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_city',__('City'),array('class'=>'form-label')) }}
                {{Form::text('billing_city',null,array('class'=>'form-control' , 'placeholder'=>__('City')))}}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_state',__('State'),array('class'=>'form-label')) }}
                {{Form::text('billing_state',null,array('class'=>'form-control' , 'placeholder' => __('State')))}}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_country',__('Country'),array('class'=>'form-label')) }}
                {{Form::text('billing_country',null,array('class'=>'form-control' , 'placeholder'=>__('Country')))}}
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label')) }}
                {{Form::text('billing_zip',null,array('class'=>'form-control' , 'placeholder'=>__('Zip Code')))}}

            </div>
        </div>

    </div>

    @if(App\Models\Utility::getValByName('shipping_display')=='on')
        <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="{{__('Shipping Same As Billing')}}" class="btn btn-primary">
        </div>
        <h6 class="sub-title">{{__('Shipping Address')}}</h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_name',__('Name'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_name',null,array('class'=>'form-control' , 'placeholder'=>__('Name')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_phone',null,array('class'=>'form-control' , 'placeholder'=>__('Phone')))}}

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                    <label class="form-label" for="example2cols1Input"></label>
                    {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Address')))}}

                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_city',null,array('class'=>'form-control' , 'placeholder'=>__('City')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_state',null,array('class'=>'form-control' , 'placeholder'=>__('State')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_country',null,array('class'=>'form-control' , 'placeholder'=>__('Country')))}}

                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_zip',null,array('class'=>'form-control' , 'placeholder' => __('Zip Code')))}}

                </div>
            </div>

        </div>
    @endif

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary" id="CreateSubmit">
</div>
{{Form::close()}}
<script>
   $(document).on('click', '#CreateSubmit', function (event)
   {
       var variableName=['name','contact','email','tax_number','business_registration_number','nic_number'];
       var submitData=false;
       $.each(variableName, function (key, value) {
        var errormessage="";  
            var valueData=$("#"+value).val();
            var maxAttr=$("#"+value).attr('maxlength');
            var type=$("#"+value).attr('type');
            var minAttr=$("#"+value).attr('minlength');

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
            if((type!=="email" && isnotValid) || (value=="name" && !nameregex.test(valueData)) ){
                errormessage="Please fill the valid data in valid format. \n";
                $('#'+value).addClass("error");
            }
            if(errormessage!=""){
                $('#'+value).next('.error-message').remove();
                $errorSpan = $('<span>').addClass('error-message').text(errormessage);
                $('#'+value).addClass("error");
               
                $('#'+value).after($errorSpan);
                if(value=="contact" ){
                    $('.error-message').addClass('width100');
                }
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
           ($('#'+variableName[3]).hasClass("error")==false) && ($('#'+variableName[4]).hasClass("error")==false) &&
            ($('#'+variableName[5]).hasClass("error")==false)){
        $("#FormCreate").submit();
       }else{
        return false;
       }
    });
</script>