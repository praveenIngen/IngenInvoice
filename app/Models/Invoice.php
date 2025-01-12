<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
// use App\Models\Config;
use Illuminate\Encryption\Encrypter;
use Spatie\Crypto\Rsa\PrivateKey;
use Illuminate\Support\Facades\Storage;
use Spatie\Crypto\Rsa\PublicKey;
// use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
class Invoice extends Model
{
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'issue_date',
        'due_date',
        'ref_number',
        'status',
        'category_id',
        'created_by',
    ];

    public static $statues = [
        'Draft',
        'Sent',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];


    public function tax()
    {
        return $this->hasOne('App\Models\Tax', 'id', 'tax_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\InvoiceProduct', 'invoice_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\InvoicePayment', 'invoice_id', 'id');
    }
    public function bankPayments()
    {
        return $this->hasMany('App\Models\InvoiceBankTransfer', 'invoice_id', 'id')->where('status','!=','Approved');
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }




    // private static $getTotal = NULL;
    // public static function getTotal(){
    //     if(self::$getTotal == null){
    //         $Invoice = new Invoice();
    //         self::$getTotal = $Invoice->invoiceTotal();
    //     }
    //     return self::$getTotal;
    // }

    public function getTotal()
    {

        return ($this->getSubTotal() -$this->getTotalDiscount()) + $this->getTotalTax();
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $product)
        {

            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }

    public static function getInvoiceCounter()
    {
        return \DB::table('invoices')->where('created_by', '=', \Auth::user()->creatorId())->distinct('invoice_id')->count('invoice_id');
    }

    public static function getPersonType($customerId)
    {
        return Customer::select('buyer_type')->where('customers.customer_id', '=', $customerId)->pluck('buyer_type');
    }

    public static function generateInvoiceThroughPotrtal($request,$invoiceNumber,$invoiceTypeDesc,$invoiceissue_date)
    {

     
      
        if($request->duplicateCopy=="Duplicate Copy" ||  $invoiceTypeDesc=='CRN'){
             if($invoiceTypeDesc=='CRN'){
               $invoiceid= $request->invoice_id;
             }else{
                $invoiceid= $request['invoice_id'];
             }
            $products=InvoiceProduct::Where('invoice_id','=',$invoiceid )->get();
            $products['duplicate']="Duplicate Copy";
            $productCount=count($products)-1;
        }else{
            $products=$request->items;
            $productCount=count($products);
        }
     
        $updatePrice = 0;
        $totaltaxPrice=0;
        $totalPrice=0;
        $totalDiscount=0;
        for ($i = 0; $i < $productCount; $i++) {
            $productVatAmount=!empty($itemsData[$i]['itemTaxPrice'])? $products[$i]['itemTaxPrice']:(int)$products[$i]['tax'];
            $totalDiscount=$totalDiscount+$products[$i]['discount'];
            $totaltaxPrice=  $totaltaxPrice + $productVatAmount;
            $totalPrice=  $totalPrice+$products[$i]['price']+$totaltaxPrice;
        }
        $customer = Customer::find($request->customer_id);
        $invoiceData=[];
        $invoiceData['invoiceCounter']=self::getInvoiceCounter(); 
        $invoiceData['transactionType']=$customer->transaction_nature; 
        $invoiceData['personType']=$customer->buyer_type;
        $invoiceData['invoiceTypeDesc']=$invoiceTypeDesc;
        $invoiceData['currency']="MUR";
        $invoiceData['invoiceIdentifier']=$invoiceNumber; 
        if($invoiceData['invoiceTypeDesc']=="CRN" ){
            $invoiceData['invoiceRefIdentifier']='CRN';  //need to know
        }elseif($invoiceData['invoiceTypeDesc']=="DRN"){
            $invoiceData['invoiceRefIdentifier']='DRN';
        }elseif($invoiceData['invoiceTypeDesc']=="STD"){
            $invoiceData['invoiceRefIdentifier']='';
        }
        $prevInvoiceId= $invoiceData['invoiceIdentifier']-1;
        $prevInvoice=Invoice::find($prevInvoiceId);
        if($prevInvoiceId > 0 && !empty($prevInvoice)){
            $prevInvoiceDateTime=$prevInvoice->created_at;
            $prevInvoiceAmount=$prevInvoice->price;
            $prevInvoiceBrn=$customer->business_registration_number;
            $prevNote=hash::make($prevInvoiceDateTime.$prevInvoiceAmount.$prevInvoiceBrn.$prevInvoiceId);
        }
        $invoiceData['previousNoteHash']=!empty($prevNote)?$prevNote:"prevNote";
        $invoiceData['reasonStated']="Test Invoice";
        $invoiceData['totalVatAmount']=$totaltaxPrice;
        $invoiceData['totalAmtWoVatCur']=$totalPrice-$totaltaxPrice;
        $invoiceData['totalAmtWoVatMur']=$totalPrice-$totaltaxPrice; 
        $invoiceData['invoiceTotal']=$totalPrice+$totaltaxPrice-$totalDiscount;
        $invoiceData['discountTotalAmount']=$totalDiscount;
        $invoiceData['totalAmtPaid']=$totalPrice+$totaltaxPrice-$totalDiscount;
        $invoiceData['dateTimeInvoiceIssued']= date('Ymd H:i:s', strtotime($invoiceissue_date)); //need to find
        $invoiceData['salesTransactions']=$request->sales_transaction; // Allowed values: CASH or BNKTRANSFER or CHEQUE or CARD or CREDIT. 
        $invoiceData['seller']=SellerDetail::getSellerDataforInvoice(\Auth::user()->name,\Auth::user()->creatorId());
        $invoiceData['buyer']=Customer::getByuerDetail($request->customer_id);
        $invoiceData['itemList']=Product::getAddedProductDetail($products);
        $jsonData=json_encode(array($invoiceData));
//    print_r($jsonData);
//      die;
        $apiURL = 'https://vfisc.mra.mu/einvoice-token-service/token-api/generate-token';
        $encryptionKey = openssl_random_pseudo_bytes(32);
        $aeskey = base64_encode($encryptionKey);
        $ebsMraId='17316751415290WJS5BBA11N';
        $ebsMraUsername='KuberaaConsult';
        $areaCode='100';
        $payloaddata=[  'username'=>'KuberaaConsult',
                        'password'=>'Kub@2024',
                        'encryptKey'=>$aeskey,
                        'refreshToken'=>false
                     ];
        $jsonDataSec=json_encode($payloaddata);

        $encryptedData= self::encryptDataWithCrt($jsonDataSec,"js/pubkey.pem");

        $payloadForToken=['requestId'=>  $invoiceNumber,'payload'=>$encryptedData];

        $requestHeadersAuth = [
            'Content-Type: application/json',
            'ebsMraId: '.$ebsMraId,
            'username: '.$ebsMraUsername
           ];
           $chAuth = curl_init();
           curl_setopt($chAuth, CURLOPT_URL,$apiURL);
           curl_setopt($chAuth, CURLOPT_POST, 1);
           curl_setopt($chAuth, CURLOPT_POSTFIELDS,json_encode($payloadForToken)); //Post Fields
           curl_setopt($chAuth, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($chAuth, CURLOPT_HTTPHEADER, $requestHeadersAuth);
           curl_setopt($chAuth, CURLOPT_SSL_VERIFYHOST, 0);
           curl_setopt($chAuth, CURLOPT_SSL_VERIFYPEER, 0);
           $responseDataAuth = curl_exec($chAuth);
        //    echo "<br> responseDataAuth: = " .$responseDataAuth;
    //  print_r($responseDataAuth);
    //  die;
           $responseArray = json_decode($responseDataAuth, true);
           foreach ($responseArray as $key => $value) {
             $responseArray[$key] =  $value;
           }

        //   echo "<br><br> token = " .$token;
           
        if (isset($responseArray['token']) && !empty($responseArray['token'])) { // 200 OK
                $decryptedKey = openssl_decrypt($responseArray['key'], 'AES-256-ECB', base64_decode($aeskey));
                $encryptedInvoiceData = openssl_encrypt($jsonData,"AES-256-ECB",base64_decode($decryptedKey) , OPENSSL_RAW_DATA );
                $requetToSend=[];
                $requetToSend['requestId']=$invoiceNumber;
                $requetToSend['requestDateTime']=date('Ymd H:i:s');
                $requetToSend['signedHash']="";
                $requetToSend['encryptedInvoice']=base64_encode($encryptedInvoiceData);
                $jsonToSendInvoice=json_encode($requetToSend);
                $token=$responseArray['token'];
                $requestHeadersInv = [
                    'Content-Type: application/json',
                    'ebsMraId: '.$ebsMraId,
                    'username: '.$ebsMraUsername,
                    'areaCode: ' .$areaCode,
                    'token: '.$token
                   ];
                   
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL,"https://vfisc.mra.mu/realtime/invoice/transmit");
                   curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($requetToSend)); //Post Fields
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeadersInv);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                   $responseData = curl_exec($ch);
                
                //   echo "<br><br> responseData: = " .$responseData;
                   return json_decode($responseData);
                // print_r($responseData);
                // die;
        }
    }


    public static function decrypt_RSA($publicPEMKey, $data)
    {
      $decrypted = '';
      $pub_key = openssl_pkey_get_public(file_get_contents($publicPEMKey));

      $keyData = openssl_pkey_get_details($pub_key);
      
            file_put_contents('./key.pub', $keyData['key']);
      //decode must be done before spliting for getting the binary String
      $data = base64_decode($data);
  
     
        //be sure to match padding
        $decryptionOK = openssl_public_decrypt($data, $decrypted, $keyData['key'], OPENSSL_PKCS1_PADDING);
  
        $decrypted = $decrypted;
        // print_r(base64_encode($decrypted));
        // die;
        return base64_decode($decrypted);
     
    }



    public static function decrptyBySymmetricKey($encSekB64, $appKey) {
        $encryptedData = '';
        $iv_length = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_length); 
        $sek = openssl_decrypt($encSekB64,"aes-256-cbc",$appKey,  OPENSSL_RAW_DATA ,$iv); 
                    // the SEK
        $sekB64 = base64_encode($sek);                                                  // the Base64 encoded SEK
        return $sekB64;
    }

    
   public static function encryptBySymmetricKey($data,$key){
        $data = base64_decode($data);                                                // the data to encrypt
        $sek = base64_decode($key);  
        $iv_length = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_length); 
        $encryptedData = '';             
        $encDataB64 = openssl_encrypt($data, "aes-256-cbc", $sek,  OPENSSL_RAW_DATA ,$iv); 
        // print_r($encDataB64);
        // die;                                  // the SEK
        // $encDataB64 = openssl_public_encrypt($data, $encryptedData,$sek);                   // the Base64 encoded ciphertext
        return base64_encode($encDataB64);
    }

    public static function encryptDataWithCrt($data, $crtFilePath)
    {
        $pub_key = openssl_pkey_get_public(file_get_contents($crtFilePath));

        $keyData = openssl_pkey_get_details($pub_key);

        file_put_contents('./key.pub', $keyData['key']);
    
        // Encrypt the data
        $encryptedData = '';
        $success = openssl_public_encrypt($data, $encryptedData, $keyData['key']);
        return base64_encode($encryptedData);
    }



    // public function getTotalTax()
    // {
    //     $totalTax = 0;
    //     foreach($this->items as $product)
    //     {
    //         $taxes = Utility::totalTaxRate($product->tax);


    //         $totalTax += ($taxes / 100) * ($product->price * $product->quantity - $product->discount) ;
    //     }

    //     return $totalTax;
    // }

    public function getTotalTax()
    {
        $taxData = Utility::getTaxData();
        $totalTax = 0;
        foreach($this->items as $product)
        {
            // $taxes = Utility::totalTaxRate($product->tax);

            $taxArr = explode(',', $product->tax);
            $taxes = 0;
            foreach ($taxArr as $tax) {
                // $tax = TaxRate::find($tax);
                $taxes += !empty($taxData[$tax]['rate']) ? $taxData[$tax]['rate'] : 0;
            }

            $totalTax += ($taxes / 100) * ($product->price * $product->quantity);
        }

        return $totalTax;
    }
    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->items as $product)
        {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }

    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due) - $this->invoiceTotalCreditNote();
    }

    public static function change_status($invoice_id, $status)
    {

        $invoice         = Invoice::find($invoice_id);
        $invoice->status = $status;
        $invoice->update();
    }

    public function category()
    {
        return $this->hasOne('App\Models\ProductServiceCategory', 'id', 'category_id');
    }

    public function creditNote()
    {

        return $this->hasMany('App\Models\CreditNote', 'invoice', 'id');
    }

    public function invoiceTotalCreditNote()
    {
        return $this->creditNote->sum('amount');
    }

    public function lastPayments()
    {
        return $this->hasOne('App\Models\InvoicePayment', 'id', 'invoice_id');
    }

    public function taxes()
    {
        return $this->hasOne('App\Models\Tax', 'id', 'tax');
    }

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
