<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SellerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vender_id',
        'customer_id',
        'trade_name',
        'vat_registration_number',
        'business_registration_number',
        'business_address',
        'contact_number',
        'ebs_counter_number',
        'cashierid',
        'created_by',
    ];

    protected $hidden = [
    ];

    protected $casts = [
    ];

    public $settings;

    public function creatorId()
    {
       
        return $this->created_by;

    }
    public static function saveData($request,$userid,$vendorid,$customerid,$creatorid)
    {
        $cashierid=$request->cashierid?$request->cashierid:0;
        $ebs_counter_number=$request->ebs_counter_number?$request->ebs_counter_number:0;
        // print_r($request->trade_name);


$update=[   'user_id'=>$userid,
            'vender_id'=>$vendorid,'customer_id'=>$customerid,
            'trade_name'=>$request->trade_name,'vat_registration_number'=>$request->vat_registration_number,
            'business_registration_number'=>$request->business_registration_number,'business_address'=>$request->business_address,
            'contact_number'=>$request->contact_number,'ebs_counter_number'=>$ebs_counter_number, 'cashierid'=>$cashierid,
            'created_by'=>$creatorid, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')
        ];

        $sellerData= \DB::table('seller_details')->select('seller_details.*')->where('seller_details.user_id', '=', $userid)->first();
// print_r($sellerData);
// echo "weee";
// print_r($userid);
// die;

        if (!empty($sellerData->user_id) && $sellerData->user_id>0) {
            \DB::table('seller_details')->where('user_id',$userid)->update($update);
        }else{
            \DB::insert(
                'insert into seller_details (`user_id`, `vender_id`,`customer_id`,`trade_name`,`vat_registration_number`,`business_registration_number`,
            `business_address`,
            `contact_number`,
            `ebs_counter_number`,
            `cashierid`,
            `created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?,?,?,?,?,?,?,?,?)', [
           $userid,$vendorid,$customerid, $request->trade_name,$request->vat_registration_number,$request->business_registration_number,$request->business_address,$request->contact_number,$ebs_counter_number, $cashierid,$creatorid, date('Y-m-d H:i:s'),
           date('Y-m-d H:i:s'),
                                                                                                                                                                                                                           ]
            );
          }
    }


    public static function getDataByUserid($userid)
    {
        return \DB::table('seller_details')->where('seller_details.user_id', '=', $userid)->first();
    }

    public static function getDataByVenderid($venderid)
    {
        return \DB::table('seller_details')->where('seller_details.vender_id', '=', $venderid)->first();
    }

    public static function getDataByCustomerid($customerid)
    {
        return \DB::table('seller_details')->where('seller_details.customer_id', '=', $customerid)->first();
    }

    public static function getSellerDataforInvoice($sellerName,$createdBy)
    {
        $sellerDetail=[];
        $sellerData= \DB::table('seller_details')->select('seller_details.*')->leftjoin('product_services', 'seller_details.user_id', '=', 'product_services.created_by')->where('seller_details.user_id', '=', $createdBy)->first();
        $sellerDetail['name']=$sellerName;
        $sellerDetail['tradeName']=$sellerData->trade_name;
        $sellerDetail['tan']=$sellerData->vat_registration_number;
        $sellerDetail['brn']=	$sellerData->business_registration_number;
        $sellerDetail['businessAddr']=$sellerData->business_address;
        $sellerDetail['businessPhoneNo']=$sellerData->contact_number;
        $sellerDetail['ebsCounterNo']=$sellerData->ebs_counter_number;
        $sellerDetail['cashierID']=$sellerData->cashierid;
        return $sellerDetail;
    }
}
