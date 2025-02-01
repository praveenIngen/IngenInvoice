
@extends('layouts.admin')
@section('page-title')
    {{__('Purchase Create')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">{{__('Purchase')}}</a></li>
    <li class="breadcrumb-item">{{__('Purchase Create')}}</li>
@endsection
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
    <script>
           $("#AddPurchaseItemButton").addClass('disabled');
           $("#purchaseFormSubmit").addClass('disabled');
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function () {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    // $('.select2').select2();
                },
                hide: function (deleteElement) {
             
             $(this).slideUp(deleteElement);
             var itemInput = $('.item');
             var totalDeletedTax= 0;
             var totalDeletedDiscount=0;
             for (var z = 0; z < itemInput.length; z++) {
                 if(itemInput[z].value=="" &&  itemInput[z].value==undefined){
                      totalDeletedTax +=  $("input[name='items["+z+"][itemTaxPrice]']").val();
                      totalDeletedDiscount +=  $("input[name='items["+z+"][discount]']").val();
                 }else if(itemInput[z].value==$("input[name='items["+z+"][item]']").val()){
                      totalDeletedTax += $(this).find(".itemTaxPrice").val();
                      totalDeletedDiscount += $(this).find(".discount").val();
                 }
             }
         
             var totalItemTaxPrice = 0;
                 var itemTaxPriceInput = $('.itemTaxPrice');
                 for (var x = 0; x < itemTaxPriceInput.length; x++) {
                     if(itemInput[x].value!="" &&  itemInput[x].value!=undefined){
                         if( $("input[name='items["+x+"][itemTaxPrice]']").val()!="" &&  $("input[name='items["+x+"][itemTaxPrice]']").val()!=undefined){
                             totalItemTaxPrice += parseFloat( $("input[name='items["+x+"][itemTaxPrice]']").val());
                         }
                     }
                 }
                 

             var totalItemDiscountPrice = 0;
             var itemDiscountPriceInput = $('.discount');
             var itemTaxPrice =0;
     
             
                 for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                     if(itemInput[k].value!="" &&  itemInput[k].value!=undefined){
                         totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                     }
                 }
             // alert($(this).find(".itemTaxPrice").val());
             $(this).remove();
         
             var inputs = $(".amount");
             var subTotal = 0;
             for (var i = 0; i < inputs.length; i++) {
                 if(itemInput[i].value!="" &&  itemInput[i].value!=undefined){
                     subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                 }
             }
            
                 var priceInput = $('.price');
                 var totalItemPrice=0;
                 var inputs_quantity = $(".quantity");
                 for (var n = 0; n < priceInput.length; n++) {
                     if(itemInput[n].value!="" &&  itemInput[n].value!=undefined){
                         totalItemPrice += (parseFloat(priceInput[n].value) * parseFloat(inputs_quantity[n].value));
                        }
                 }
             if(totalItemPrice!="" &&  totalItemPrice!=undefined && totalItemPrice>0){
                     totalItemTaxPrice = totalItemTaxPrice-totalDeletedTax;
             }
             if(totalItemDiscountPrice!=undefined && totalItemDiscountPrice>0){
                 totalItemDiscountPrice=totalItemDiscountPrice-totalDeletedDiscount;
                 $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
             }else{
                 $('.totalDiscount').html("0.00");
             }
          
                 if(totalItemPrice!=undefined && totalItemPrice>0){
                     $('.subTotal').html(totalItemPrice.toFixed(2));
                     // $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                     $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                     $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                     $("#AddPurchaseItemButton").removeClass('disabled');
                     $("#purchaseFormSubmit").removeClass('disabled');
                 }else{
                     $('.subTotal').html("0.00");
                     $('.totalAmount').html("0.00");
                     $("#AddPurchaseItemButton").addClass('disabled');
                             $('.totalTax').html("0.00");
                             $('.totalDiscount').html("0.00");
                             $("#purchaseFormSubmit").addClass('disabled');
                  
                 }
           
                 if(totalItemPrice==0 && subTotal==0){
                         $('.totalTax').html("0.00");
                         $('.totalDiscount').html("0.00");
                         $("#purchaseFormSubmit").addClass('disabled');
                  }
                
         
     },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                // isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    tr.find('.item').val(value[i].product_id);
                    changeItem(tr.find('.item'));
                }
            }

        }

        $(document).on('change', '#vender', function () {
            $('#vender_detail').removeClass('d-none');
            $('#vender_detail').addClass('d-block');
            $('#vender-box').removeClass('d-block');
            $('#vender-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function (data) {
                    if (data != '') {
                        $('#vender_detail').html(data);
                    } else {
                        $('#vender-box').removeClass('d-none');
                        $('#vender-box').addClass('d-block');
                        $('#vender_detail').removeClass('d-block');
                        $('#vender_detail').addClass('d-none');
                    }
                },
            });
        });

        $(document).on('click', '#remove', function () {
            $('#vender-box').removeClass('d-none');
            $('#vender-box').addClass('d-block');
            $('#vender_detail').removeClass('d-block');
            $('#vender_detail').addClass('d-none');
        })


        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

  

        function changeItem(element) {


            var iteams_id = element.val();
            var url = element.data('url');
            var el = element;
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);
                    console.log(el.parent().parent().find('.quantity'))
                    $(el.parent().parent().find('.quantity')).val(1);
                    $(el.parent().parent().find('.price')).val(item.product.sale_price);
                    $(el.parent().parent().parent().find('.pro_description')).val(item.product.description);
                    // $('.pro_description').text(item.product.description);

                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;

                    if (item.taxes == 0) {
                        taxes += '-';
                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {
                            taxes += '<span class="badge bg-primary mt-1 mr-2">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);
                        }
                    }
                    var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((item.product.sale_price * 1));
                    $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().find('.discount')).val(0);

                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                 
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
              
                    }
                    

                    var totalItemPrice = 0;
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += parseFloat(priceInput[j].value);
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                        $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount)+parseFloat(itemTaxPriceInput[j].value));
                    }

                    var totalItemDiscountPrice = 0;
                    var itemDiscountPriceInput = $('.discount');

                    for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                        totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                    }
                   if(totalItemPrice!=undefined && totalItemPrice>0){
                    $('.subTotal').html(totalItemPrice.toFixed(2));
                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
                    if(iteams_id!=undefined && iteams_id!=""){
                        $("#AddPurchaseItemButton").removeClass('disabled');
                        $("#purchaseFormSubmit").removeClass('disabled');
                        var itemInput = $('.item');
                        for (var j = 0; j < itemInput.length; j++) {
                        if(itemInput[j].value==""){
                            $("#purchaseFormSubmit").addClass('disabled');
                        }
                        }
                    }
                }else{
                    $('.subTotal').html("0.00");
                   $('.totalTax').html("0.00");

                  $('.totalAmount').html("0.00"); 
                   }
                },
                error: function (data) {
                    $("#AddPurchaseItemButton").addClass('disabled');
                    $("#purchaseFormSubmit").addClass('disabled');
                },
            });
        };


       
        $(document).on('focusout', '.quantity', function () {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();
            if (this.value < 1) this.value = 1; // minimum is 1
            else this.value = Math.floor(this.value); 
       
                var quantity = $(this).val();
              
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            if(discount.length <= 0)
            {
                discount = 0 ;
            }

            var totalItemPrice = (quantity * price) - discount;

            var amount = (totalItemPrice);
            var itemInput = $('.item');

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var k = 0; k < itemTaxPriceInput.length; k++) {
                if(itemInput[k].value!="" &&  itemInput[k].value!=undefined){
                    totalItemTaxPrice += parseFloat(itemTaxPriceInput[k].value);
                }
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                if(itemInput[j].value!="" &&  itemInput[j].value!=undefined){
                    totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
                }
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                if(itemInput[i].value!="" &&  itemInput[i].value!=undefined){
                    subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                }
            }

           
            if( totalItemPrice!=undefined && totalItemPrice>0){
                $('.subTotal').html(totalItemPrice.toFixed(2));
                $('.totalTax').html(totalItemTaxPrice.toFixed(2));

                 $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));        
             }else{
                $('.subTotal').html("0.00");
                   $('.totalTax').html("0.00");

                  $('.totalAmount').html("0.00"); 
             }
        });

        // $(document).on('keyup change', '.price', function () {
        //     var el = $(this).parent().parent().parent().parent();
        //     var price = $(this).val();
        //     var quantity = $(el.find('.quantity')).val();

        //     var discount = $(el.find('.discount')).val();
        //     if(discount.length <= 0)
        //     {
        //         discount = 0 ;
        //     }
        //     var totalItemPrice = (quantity * price)-discount;

        //     var amount = (totalItemPrice);


        //     var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
        //     var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
        //     $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

        //     $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

        //     var totalItemTaxPrice = 0;
        //     var itemTaxPriceInput = $('.itemTaxPrice');
        //     for (var j = 0; j < itemTaxPriceInput.length; j++) {
        //         totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
        //     }


        //     var totalItemPrice = 0;
        //     var inputs_quantity = $(".quantity");

        //     var priceInput = $('.price');
        //     for (var j = 0; j < priceInput.length; j++) {
        //         totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
        //     }

        //     var inputs = $(".amount");

        //     var subTotal = 0;
        //     for (var i = 0; i < inputs.length; i++) {
        //         subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
        //     }

        //     $('.subTotal').html(totalItemPrice.toFixed(2));
        //     $('.totalTax').html(totalItemTaxPrice.toFixed(2));

        //     $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));


        // })

        $(document).on('focusout', '.discount', function () {
            var el = $(this).parent().parent().parent();
            if (this.value < 0) this.value = 0; // minimum is 1
            else this.value = Math.floor(this.value); 
            var discount = $(this).val();
            if(discount.length <= 0)
            {
                discount = 0 ;
            }
         
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var totalItemPrice = (quantity * price) - discount;


            var amount = (totalItemPrice);

            var itemInput = $('.item');
            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var m = 0; m < itemTaxPriceInput.length; m++) {
                if(itemInput[m].value!="" &&  itemInput[m].value!=undefined){
                    totalItemTaxPrice += parseFloat(itemTaxPriceInput[m].value);
                }
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
          
            
                    for (var n = 0; n < priceInput.length; n++) {
                        if(itemInput[n].value!="" &&  itemInput[n].value!=undefined){
                           totalItemPrice += (parseFloat(priceInput[n].value) * parseFloat(inputs_quantity[n].value));
                    
                            }
                    }
        
            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                if(itemInput[i].value!="" &&  itemInput[i].value!=undefined){
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                }
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');
        
    
             
                for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                    if(itemInput[k].value!="" &&  itemInput[k].value!=undefined){
                        totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                        if(totalItemDiscountPrice!=undefined && totalItemDiscountPrice>0){
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2)); 
                        }else{
                            $('.totalDiscount').html("0.00");
                        }
                    }
                }
                
            
        

                    if(totalItemPrice!=undefined && totalItemPrice>0){
                        $('.subTotal').html(totalItemPrice.toFixed(2));
                        $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                        $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                    }
             
            if(totalItemDiscountPrice!=undefined && totalItemDiscountPrice>0){
                $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2)); 
                }else{
                    $('.totalDiscount').html("0.00");
                }
            
        
        });
        $(document).on('change', '.item', function () {
            var itemInput = $('.item');
            for (var j = 0; j < itemInput.length; j++) {
                if(itemInput[j].value=="" &&  itemInput[j].value==undefined){
                    $("#AddPurchaseItemButton").addClass('disabled');
                    $("#purchaseFormSubmit").addClass('disabled');
                    $('.item :selected').each(function () {
                        $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', true);
                    });
                }
            }
          
        });

        $(document).on('click', '[data-repeater-create]', function () {
            $("#AddPurchaseItemButton").addClass('disabled');
            $("#purchaseFormSubmit").addClass('disabled');
            var itemInput = $('.item');
            for (var j = 0; j < itemInput.length; j++) {
                if(itemInput[j].value!="" &&  itemInput[j].value!=undefined){
                    $("select[name='items["+j+"][item]']").addClass('disabled');
                    $('.item :selected').each(function () {
                        $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', true);
                    });
                }
            } 
        })
        var vendorId = '{{$vendorId}}';
        if (vendorId > 0) {
            $('#vender').val(vendorId).change();
        }

    </script>

     <script>
        $(document).on('click', '[data-repeater-delete]', function () {
            $(".price").change();
            $(".discount").change();
            
      
       
            var itemInput = $('.item');
            for (var j = 0; j < itemInput.length; j++) {
                if(itemInput[j].value!="" &&  itemInput[j].value!=undefined){
                    $("#AddPurchaseItemButton").removeClass('disabled');
                    $("select[name='items["+j+"][item]']").removeClass('disabled');
                    $('.item :selected').each(function () {
                        $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', false);
                    });
                }
            } 
        
         
        });
      
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

  
 $(document).on('click', '#purchaseFormSubmit', function (event)
   {
       var variableName=['vender','purchase_date','warehouse_id','category_id','item','quantity','discount'];
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
                $errorSpan = $('<span>').addClass('error-message').text(errormessage);
              
                $('#'+value).next('.error-message').remove();
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
           ($('#'+variableName[3]).hasClass("error")==false) && ($('#'+variableName[4]).hasClass("error")==false) &&
            ($('#'+variableName[5]).hasClass("error")==false)){
        $("#CreatePurchaseForm").submit();
       }else{
        return false;
       }
    });

    $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var minDate= year + '-' + month + '-' + day;
    
    $('#purchase_date').attr('min', minDate);
});

    </script>
@endpush

@section('content')
    <div class="row">
        {{ Form::open(array('url' => 'purchase','class'=>'w-100', 'class'=>'needs-validation' ,'id'=>'CreatePurchaseForm')) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="vender-box">
                                {{ Form::label('vender_id', __('Vendor'),['class'=>'form-label']) }}<x-required></x-required>
                                {{ Form::select('vender_id', $venders,$vendorId, array('class' => 'form-control select','id'=>'vender','data-url'=>route('bill.vender'),'required'=>'required')) }}
                            </div>
                            <div id="vender_detail" class="d-none">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('warehouse_id', __('Warehouse'),['class'=>'form-label']) }}<x-required></x-required>
                                        {{ Form::select('warehouse_id', $warehouse,null, array('class' => 'form-control select','required'=>'required')) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
                                        {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('purchase_date', __('Purchase Date'),['class'=>'form-label']) }}<x-required></x-required>
                                        {{Form::date('purchase_date',null,array('class'=>'form-control','required'=>'required'))}}

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('purchase_number', __('Purchase Number'),['class'=>'form-label']) }}
                                        <input type="text" class="form-control" value="{{$purchase_number}}" readonly>

                                    </div>
                                </div>
                            </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{__('Product & Services')}}</h5>
            <div class="card repeater">
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal" data-target="#add-bank" id="AddPurchaseItemButton">
                                    <i class="ti ti-plus"></i> {{__('Add item')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0" data-repeater-list="items" id="sortable-table">
                            <thead>
                            <tr>
                                <th>{{__('Items')}}<x-required></x-required></th>
                                <th>{{__('Quantity')}}<x-required></x-required></th>
                                <th>{{__('Price')}}<x-required></x-required></th>
                                <th>{{__('Discount')}}<x-required></x-required></th>
                                <th>{{__('Tax')}} (%)</th>
                                <th class="text-end">{{__('Amount')}} <br><small class="text-danger font-weight-bold">{{__('after tax & discount')}}</small></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                <td width="25%" class="form-group pt-1">
                                    {{ Form::select('item', $product_services,'', array('class' => 'form-control select2 item','data-url'=>route('purchase.product'),'required'=>'required')) }}
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('quantity','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}

                                        <span class="unit input-group-text bg-transparent"></span>
                                    </div>
                                </td>
                                <td>
                                <div class="priceWidth">
                                    <span class="">{{\Auth::user()->currencySymbol()}}</span>
                                        {{ Form::number('price','', array('class' => 'priceInput price','required'=>'required','readonly','placeholder'=>__('Price'),'required'=>'required')) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::number('discount','', array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="taxes"></div>
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end amount">
                                    0.00
                                </td>
                                <td>
                                    <a href="#" class="ti ti-trash text-white text-white repeater-action-btn bg-danger ms-2" data-repeater-delete></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">{{ Form::textarea('description', null, ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) }}</div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Sub Total')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Discount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalDiscount">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Tax')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalTax">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="blue-text text-end totalAmount">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route("purchase.index")}}';" class="btn btn-light">
            <input type="submit" value="{{__('Create')}}" class="btn  btn-primary" id='purchaseFormSubmit'>
        </div>
    {{ Form::close() }}
    </div>

@endsection

