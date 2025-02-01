{{ Form::open(array('url' => 'productservice','enctype' => "multipart/form-data", 'class'=>'needs-validation','id'=>'productFormCreate')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Product Name'))) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('Product Code'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('sku', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Sku Number'))) }}
                <span>SKU code should be unique for every product</span>
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_category', __('Tax Category'),['class'=>'form-label']) }}<x-required></x-required>
                    {{ Form::select('tax_category',array('TC01' => 'TC01', 'TC02' => 'TC02','TC03'=>'TC03'),null, array('class' => 'form-control select','required'=>'required' , 'placeholder'=>__('Tax Category'))) }}
                </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('sale_price', '', array('class' => 'form-control','required'=>'required','step'=>'0.01' ,'placeholder'=>__('Sales Price'))) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label']) }}<x-required></x-required>
            <select name="sale_chartaccount_id" class="form-control" required="required">
                @foreach ($incomeChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
                    @foreach ($incomeSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('purchase_price', '', array('class' => 'form-control','required'=>'required','step'=>'0.01' ,'placeholder'=>__('Purchase Price'))) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label']) }}<x-required></x-required>
            <select name="expense_chartaccount_id" class="form-control" required="required" >
                @foreach ($expenseChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
                    @foreach ($expenseSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('tax_id', __('Tax'),['class'=>'form-label']) }}
            {{ Form::select('tax_id[]', $tax,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required' , 'placeholder'=>__('Category'))) }}

            <div class=" text-xs">
                {{__('Please add constant category. ')}}<a href="{{route('product-category.index')}}"><b>{{__('Add Category')}}</b></a>
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('unit_id', __('Unit'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('unit_id', $unit,null, array('class' => 'form-control select','required'=>'required' , 'placeholder'=>__('Unit data'))) }}
        </div>
        <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control file-validate" name="pro_image" id="pro_image" data-filename="pro_image_create">
                <p id="" class="file-error text-danger"></p>
                    <img id="image" class="mt-3" style="width:25%;"/>
                </label>
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group">
                <div class="btn-box">
                {{ Form::label('type', __('Type'),['class'=>'form-label']) }}<x-required></x-required>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="GOODS" checked="checked" >
                                <label class="custom-control-label form-label" for="customRadio5">{{__('GOODS')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="SERVICES" >
                                <label class="custom-control-label form-label" for="customRadio6">{{__('Services')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-6 quantity">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::number('quantity',null, array('class' => 'form-control', 'min'=>"0",'required'=>'required' , 'placeholder'=>__('Quantity'))) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<x-required></x-required>
            {!! Form::textarea('description', null, ['class'=>'form-control','required'=>'required','rows'=>'2','placeholder'=>__('Product description')]) !!}
        </div>

        @if(!$customFields->isEmpty())
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary" id="productCreateSubmit">
</div>
{{Form::close()}}


<script>
    document.getElementById('pro_image').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }

    //hide & show quantity

    $(document).on('click', '.type', function ()
    {
        var type = $(this).val();
        if (type == 'GOODS') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
        }
    });


   $(document).on('click', '#productCreateSubmit', function (event)
   {
       var variableName=['name','sku','tax_category','sale_price','sale_chartaccount_id','expense_chartaccount_id','purchase_price','category_id','unit_id','type','quantity','description'];
       var submitData=false;
       $.each(variableName, function (key, value) {
        var errormessage="";
            var valueData=$("#"+value).val();
            var maxAttr=$("#"+value).attr('maxlength');
            var type=$("#"+value).attr('type');
            var minAttr=$("#"+value).attr('minlength');
            // alert(value+maxAttr)
            var validEmailRegEx = /^[A-Z0-9_'%=+!`#~$*?^{}&|-]+([\.][A-Z0-9_'%=+!`#~$*?^{}&|-]+)*@[A-Z0-9-]+(\.[A-Z0-9-]+)+$/i;
            if(valueData==""){
                errormessage="Please fill the valid data \n";
            }else if(type=="email" && validEmailRegEx.test(valueData)===false){
                    errormessage="Please fill the valid email";
            }else if(type!=="email" && maxAttr!==undefined && minAttr!==undefined && maxAttr === minAttr && (valueData!="" && (valueData.length>maxAttr || valueData.length<minAttr))){
                errormessage="Please fill the valid data which must be "+ minAttr+" digit "+type+" \n";
            }else if((maxAttr!=undefined && minAttr!=undefined) && (valueData=="" || (valueData!="" && (valueData.length>maxAttr || valueData.length<minAttr)))){
                 errormessage="Please fill the valid data  with minimum "+minAttr+" digit "+type+" and maximum "+maxAttr+ " digit  "+type+"\n";
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
        if( $('input[name="type"]:checked').val() =="SERVICES"){
                    $('#quantity').removeClass("error");
                    $('#quantity').next('.error-message').remove();
                    submitData=true;
                }
       if(submitData===true &&  ($('#'+variableName[0]).hasClass("error")==false) && 
           ($('#'+variableName[1]).hasClass("error")==false) && ($('#'+variableName[2]).hasClass("error")==false) && 
           ($('#'+variableName[3]).hasClass("error")==false) && ($('#'+variableName[4]).hasClass("error")==false) &&
            ($('#'+variableName[5]).hasClass("error")==false) &&  ($('#'+variableName[6]).hasClass("error")==false) &&  ($('#'+variableName[7]).hasClass("error")==false)
        &&  ($('#'+variableName[8]).hasClass("error")==false) &&  ($('#'+variableName[9]).hasClass("error")==false) &&  ($('#'+variableName[10]).hasClass("error")==false) && ($('#'+variableName[11]).hasClass("error")==false)){
        $("#productFormCreate").submit();
       }else{
        return false;
       }
    });
</script>
