{{ Form::model($creditNote, array('route' => array('invoice.edit.credit.note',$creditNote->invoice, $creditNote->id), 'method' => 'post', 'class'=>'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
    
            
                <strong>{{ __('Invoice Number') }} :</strong><br>
                {{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}<br><br>
          
        </div>
   
        <div class="form-group  col-md-6">
        
                <strong>{{ __('Credit Issue Date') }} :</strong><br>
                {{ $creditNote->date }}<br><br>
          
    
        </div>
        <div class="form-group  col-md-6">
        
                <strong>{{ __('Credit Amount') }} :</strong><br>
                {{ $creditNote->amount }}<br><br>
          
       
        </div>
        <div class="form-group col-md-6">
        
                <strong>{{ __('Description') }} :</strong><br>
                {{ $creditNote->description }}<br><br>
          
         
        </div>
        <div class="form-group col-md-6">
        <img src="data:image/png;base64,{{ $creditNote->credit_qrcode }}" style="position: relative;float:left;width:70%;" alt="barcode"   />
         
        </div>
    
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
