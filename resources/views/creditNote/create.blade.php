{{ Form::open(array('route' => array('invoice.credit.note',$invoice_id),'mothod'=>'post', 'class'=>'needs-validation', 'id'=>'creditFormCreate')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Credit Issue Date'),['class'=>'form-label']) }}<x-required></x-required>
            {{Form::date('date',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
          
            {{ Form::label('amount', __('Credit Amount'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::number('amount', !empty($invoiceDue)?$invoiceDue->getDue():0, array('class' => 'form-control','required'=>'required','step'=>'0.01' , 'placeholder'=>__('Enter Amount'))) }}

        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', '', ['class'=>'form-control','rows'=>'3' , 'placeholder'=>__('Enter Description')]) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Add')}}" class="btn  btn-primary" id="creditCreateSubmit">
</div>
{{ Form::close() }}
<script>
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
    
    $('#date').attr('min', minDate);
});
 $(document).on('click', '#creditCreateSubmit', function (event)
   {
       var variableName=['date','amount'];
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
            var CurrentDate = new Date();
            if(value=="date" && valueData < CurrentDate){
               errormessage="Please fill the date greater than or equal to current date";
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
           ($('#'+variableName[1]).hasClass("error")==false)){
        $("#creditFormCreate").submit();
       }else{
        return false;
       }
    });    
</script>