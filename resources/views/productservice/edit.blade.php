{{ Form::model($productService, array('route' => array('productservice.update', $productService->id), 'method' => 'PUT','enctype' => "multipart/form-data", 'class'=>'needs-validation','id'=>'productFormUpdate')) }}
<div class="modal-body">
 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name',null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('SKU'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('sku', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_category', __('Tax Category'),['class'=>'form-label']) }}<x-required></x-required>
                    {{ Form::select('tax_category',array('TC01' => 'TC01', 'TC02' => 'TC02','TC03'=>'TC03'),null, array('class' => 'form-control select','required'=>'required')) }}
                </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('sale_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label']) }}<x-required></x-required>
            {{-- {{ Form::select('sale_chartaccount_id',$incomeChartAccounts,null, array('class' => 'form-control select','required'=>'required')) }} --}}
            <select name="sale_chartaccount_id" class="form-control" required="required">
                @foreach ($incomeChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount" {{ ($productService->sale_chartaccount_id == $key) ? 'selected' : ''}}>{{ $chartAccount }}</option>
                    @foreach ($incomeSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5" {{ ($productService->sale_chartaccount_id == $subAccount['id']) ? 'selected' : ''}}> &nbsp; &nbsp;&nbsp; {{ isset($subAccount['code_name']) ? $subAccount['code_name'] : '' }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('purchase_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label']) }}<x-required></x-required>
            {{-- {{ Form::select('expense_chartaccount_id',$expenseChartAccounts,null, array('class' => 'form-control select','required'=>'required')) }} --}}
            <select name="expense_chartaccount_id" class="form-control"  required="required">
                @foreach ($expenseChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount" {{ ($productService->expense_chartaccount_id == $key) ? 'selected' : ''}}>{{ $chartAccount }}</option>
                    @foreach ($expenseSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5" {{ ($productService->expense_chartaccount_id == $subAccount['id']) ? 'selected' : ''}}> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('tax_id', __('Tax'),['class'=>'form-label']) }}
            {{ Form::select('tax_id[]', $tax,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>'')) }}
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('unit_id', __('Unit'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('unit_id', $unit,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>

        <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control file-validate" name="pro_image" id="pro_image" data-filename="pro_image_create">
                <p id="" class="file-error text-danger"></p>
                    {{-- <img id="image"  class="mt-3" width="100" src="@if($productService->pro_image){{asset(Storage::url('uploads/pro_image/'.$productService->pro_image))}}@else{{asset(Storage::url('uploads/pro_image/user-2_1654779769.jpg'))}}@endif" /> --}}
                    <img id="image" class="mt-3" width="100" src="{{ $productService->pro_image ? \App\Models\Utility::get_file('uploads/pro_image/'.$productService->pro_image) : asset(Storage::url('uploads/pro_image/user-2_1654779769.jpg'))}}" />
                </label>
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group">
            {{ Form::label('type', __('Type'),['class'=>'form-label']) }}<x-required></x-required>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="GOODS" @if($productService->type=='GOODS') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio5">{{__('Goods')}}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="SERVICES" @if($productService->type=='SERVICES') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio6">{{__('Services')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-6 quantity {{$productService->type=='service' ? 'd-none':''}}">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::number('quantity',null, array('class' => 'form-control','min'=>"0",'required'=>'required')) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<x-required></x-required>
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>


    </div>
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
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary" id="productUpdateSubmit">
</div>
{{Form::close()}}
<script>
    document.getElementById('pro_image').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
    if ($('input[name="type"]:checked').val() == 'GOODS') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
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


    $(document).on('click', '#productUpdateSubmit', function (event)
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
        $("#productFormUpdate").submit();
       }else{
        return false;
       }
    });
</script>

