@extends('layouts.admin')
@section('page-title')
    {{__('Invoice Edit')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">{{__('Invoice')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice Edit')}}</li>
@endsection
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
    <script>
       
          if($(this).find('.item').length >=1){
            $("#AddItemButton").addClass('disabled');
            }else{
                $("#AddItemButton").removeClass('disabled');
            }

          $("#invoiceUpdateSubmit").addClass('disabled');
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: true,
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
                    // if($('.select2').length) {
                    //     $('.select2').select2();
                    // }
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
                            }else if(itemInput[z].value==$(this).find(".item").val()){
                                 totalDeletedTax += $(this).find(".itemTaxPrice").val();
                                 totalDeletedDiscount += $(this).find(".discount").val();
                            }
                        }
                       var itemInput = $('.item');
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
                                    if( $("input[name='items["+k+"][discount]']").val()!="" &&  $("input[name='items["+k+"][discount]']").val()!=undefined){
                                        totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                                    }
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
                            // alert(totalItemTaxPrice);
                            // alert(totalDeletedTax);
                        if(totalItemPrice!="" &&  totalItemPrice!=undefined && totalItemPrice>0){
                                totalItemTaxPrice = totalItemTaxPrice-totalDeletedTax;
                        }
                        // alert(totalItemTaxPrice);
                        if(totalItemDiscountPrice!=undefined && totalItemDiscountPrice>0){
                            totalItemDiscountPrice=totalItemDiscountPrice-totalDeletedDiscount;
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                        }else{
                            $('.totalDiscount').html("0.00");
                        }
                            // if(totalItemPrice!=undefined && totalItemPrice>0){
                                $('.subTotal').html(totalItemPrice.toFixed(2));
                                // $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                                $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                                $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                            // }
                            $("#AddItemButton").removeClass('disabled');
                            $("#invoiceUpdateSubmit").removeClass('disabled');
                            if(totalItemPrice==0 && subTotal==0){
                                    // $('.totalTax').html("0.00");
                                    $('.totalDiscount').html("0.00");
                                  
                                    $("#invoiceUpdateSubmit").addClass('disabled');
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

        $(document).on('change', '#customer', function () {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
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
                        $('#customer_detail').html(data);
                    } else {
                        $('#customer-box').removeClass('d-none');
                        $('#customer-box').addClass('d-block');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');
                    }
                },

            });
        });

        $(document).on('click', '#remove', function () {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

        var invoice_id = '{{$invoice->id}}';

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

                    $.ajax({
                            url: '{{route('invoice.items')}}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'invoice_id': invoice_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function (data) {
                            var invoiceItems = JSON.parse(data);
                            if (invoiceItems != null) {

                                var amount = (invoiceItems.price * invoiceItems.quantity);

                                $(el.parent().parent().find('.quantity')).val(invoiceItems.quantity);
                                $(el.parent().parent().find('.price')).val(invoiceItems.price);
                                $(el.parent().parent().find('.discount')).val(invoiceItems.discount);
                                $(el.parent().parent().parent().find('.pro_description')).val(invoiceItems.description);
                                // $('.pro_description').text(invoiceItems.description);

                            } else {

                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().find('.discount')).val(0);
                                // $(el.parent().parent().find('.pro_description')).val(item.product.description);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product.description);
                                // $('.pro_description').text(item.product.description);

                            }

                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {
                                taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);
                            }

                            var discount=$(el.parent().parent().find('.discount')).val();


                            if (invoiceItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((invoiceItems.price * invoiceItems.quantity)-discount);
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((item.product.sale_price * 1)-discount);
                            }

                            $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);
                            // $(el.parent().parent().find('.discount')).val(item.discount);


                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }

                            var totalItemPrice = 0;
                            var inputs_quantity = $(".quantity");

                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
                            }


                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if (invoiceItems != null) {
                                    $(el.parent().parent().find('.amount')).html(parseFloat(amount)+parseFloat(itemTaxPrice)-parseFloat(discount));
                                } else {
                                    $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount)+parseFloat(itemTaxPrice));
                                }

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
                                $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                                if(iteams_id!=undefined && iteams_id!=""){
                                    $("#AddItemButton").removeClass('disabled');
                                    $("#invoiceUpdateSubmit").removeClass('disabled');
                                    var itemInput = $('.item');
                                    for (var j = 0; j < itemInput.length; j++) {
                                        if(itemInput[j].value==""){
                                            $("#invoiceUpdateSubmit").addClass('disabled');
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
                            // $("#AddItemButton").addClass('disabled');
                            // $("#invoiceUpdateSubmit").addClass('disabled');
                           
                         
                        },
                    });


                },
            });
        }

 
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
                    $("#AddItemButton").addClass('disabled');
                    $("#invoiceUpdateSubmit").addClass('disabled');
                    $('.item :selected').each(function () {
                        $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', true);
                    });
                }
            }
          
        });

        $(document).on('click', '[data-repeater-create]', function () {
            $("#AddItemButton").addClass('disabled');
            $("#invoiceUpdateSubmit").addClass('disabled');
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


        $(document).on('click', '[data-repeater-delete]', function () {
        // $('.delete_item').click(function () {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();
                var amount = $(el.find('.amount')).html();
                var itemInput = $('.item');
                var totalDeletedTax= 0;
                var totalDeletedDiscount=0;
                for (var z = 0; z < itemInput.length; z++) {
                    if(itemInput[z].value=="" &&  itemInput[z].value==undefined){
                            totalDeletedTax +=  $("input[name='items["+z+"][itemTaxPrice]']").val();
                            totalDeletedDiscount +=  $("input[name='items["+z+"][discount]']").val();
                    }else if(itemInput[z].value==$(this).find(".item").val()){
                            totalDeletedTax += $(this).find(".itemTaxPrice").val();
                            totalDeletedDiscount += $(this).find(".discount").val();
                    }
                }
                var itemInput = $('.item');
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
                        if( $("input[name='items["+k+"][discount]']").val()!="" &&  $("input[name='items["+k+"][discount]']").val()!=undefined){
                            totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                        }
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

                var inputs = $(".amount");
                var subTotal = 0;
                for (var i = 0; i < inputs.length; i++) {
                    if(itemInput[i].value!="" &&  itemInput[i].value!=undefined){
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
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
                // $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                //         $('.subTotal').html(totalItemPrice.toFixed(2));
                //         // $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                //         $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                $.ajax({
                    url: '{{route('invoice.product.destroy')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id,
                        'amount': amount,
                    },
                    cache: false,
                    success: function (data) {
                        $("#AddItemButton").removeClass('disabled');
                     
                        var itemInput = $('.item');
                        for (var j = 0; j < itemInput.length; j++) {
                            if(itemInput[j].value!="" &&  itemInput[j].value!=undefined){
                                $("select[name='items["+j+"][item]']").removeClass('disabled');
                                $('.item :selected').each(function () {
                                    $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', false);
                                });
                            }
                        } 
                    },
                });
        });

    </script>
    <script>
        $(document).on('click', '[data-repeater-delete]', function () {
            $(".price").change();
            $(".discount").change();
            // $("#AddItemButton").removeClass('disabled');
       
            var itemInput = $('.item');
            for (var j = 0; j < itemInput.length; j++) {
                if(itemInput[j].value!="" &&  itemInput[j].value!=undefined){
                    $("select[name='items["+j+"][item]']").removeClass('disabled');
                    $('.item :selected').each(function () {
                        $('.item').not(this).find("option[value=" + itemInput[j].value + "]").prop('hidden', false);
                    });
                }
            } 
        });
    </script>
    <script>
    

    $(document).on('click', '#invoiceUpdateSubmit', function (event)
   {
    var variableName=['customer','issue_date','due_date','category_id','sales_transaction','item','quantity','discount'];
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
        $("#invoiceFormUpdate").submit();
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
    
    $('#issue_date').attr('min', minDate);
    $('#due_date').attr('min', minDate);
});
$("#issue_date").on("change", function(){
    $("#due_date").val($(this).val());
  $("#due_date").attr("min", $(this).val());
});
    </script>
@endpush

@section('content')
    {{--    @dd($invoice)--}}
    <div class="row">
        {{ Form::model($invoice, array('route' => array('invoice.update', $invoice->id), 'method' => 'PUT','class'=>'w-100', 'class'=>'needs-validation', 'id'=>'invoiceFormUpdate')) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="customer-box">
                                {{ Form::label('customer_id', __('Customer'),['class'=>'form-label']) }}<x-required></x-required>
                                {{ Form::select('customer_id', $customers,null, array('class' => 'form-control select ','id'=>'customer','data-url'=>route('invoice.customer'),'required'=>'required')) }}
                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-label']) }}<x-required></x-required>
                                        <div class="form-icon-user">
                                            {{Form::date('issue_date',null,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'),['class'=>'form-label']) }}<x-required></x-required>
                                        <div class="form-icon-user">
                                            {{Form::date('due_date',null,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('invoice_number', __('Invoice Number'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{$invoice_number}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
                                    {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required')) }}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Ref Number'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::text('ref_number', $invoice_number, array('class' => 'form-control' ,'readonly'=>'readonly')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('sales_transaction', __('Sales Transaction'),['class'=>'form-label']) }}<x-required></x-required>
                                        {{ Form::select('sales_transaction',array('CASH' => 'CASH', 'BNKTRANSFER' => 'BANK TRANSFER','CHEQUE'=>'CHEQUE','CARD'=>'CARD','CREDIT'=>'CREDIT'),$invoice?$invoice->sales_transaction:null, array('class' => 'form-control select','required'=>'required')) }}
                                    </div>
                                </div>
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-check custom-checkbox mt-4">--}}
{{--                                        <input class="form-check-input" type="checkbox" name="discount_apply" id="discount_apply" {{$invoice->discount_apply==1?'checked':''}}>--}}
{{--                                        <label class="form-check-label" for="discount_apply">{{__('Discount Apply')}}</label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{Form::label('sku',__('SKU')) }}--}}
{{--                                        {!!Form::text('sku', null,array('class' => 'form-control','required'=>'required')) !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                @if(!$customFields->isEmpty())
                                    <div class="col-md-6">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customFields.formBuilder')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{__('Product & Services')}}</h5>
            <div class="card repeater" data-value='{!! json_encode($invoice->items) !!}'>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal" id="AddItemButton" data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{__('Add item')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                            <thead>
                            <tr>
                                <th>{{__('Items')}}<x-required></x-required></th>
                                <th>{{__('Quantity')}}<x-required></x-required></th>
                                <th>{{__('Unit Price')}}<x-required></x-required></th>
                                <th>{{__('Discount')}}<x-required></x-required></th>
                                <th>{{__('Tax')}}</th>
                                <th class="text-end">{{__('Amount')}} </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                {{ Form::hidden('id',null, array('class' => 'form-control id')) }}
                                <td width="25%" class="form-group pt-0">
                                    {{ Form::select('item', $product_services,null, array('class' => 'form-control item select','data-url'=>route('invoice.product'))) }}

                                </td>
                                <td>

                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('quantity',null, array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
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
                                        {{ Form::text('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                                <td class="flexibleTax">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="taxes"></div>
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end amount">0.00</td>

                                <td>
{{--                                    @can('delete invoice product')--}}
{{--                                        <a href="#" class="ti ti-trash text-white text-danger delete_item" data-repeater-delete></a>--}}
{{--                                    @endcan--}}

                                        <a href="#" class="ti ti-trash text-white repeater-action-btn bg-danger ms-2  delete_item" data-repeater-delete></a>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        {{ Form::textarea('description', null, ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) }}
                                    </div>
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
                                <td class="text-end totalAmount blue-text">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route("invoice.index")}}';" class="btn btn-light me-3">
            <input type="submit" value="{{__('Update')}}" class="btn  btn-primary" id="invoiceUpdateSubmit">
        </div>
        {{ Form::close() }}
    </div>
@endsection

