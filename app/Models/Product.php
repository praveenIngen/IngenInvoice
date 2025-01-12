<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'type',
        'created_by',
    ];

    public $customField;

    public static function getAddedProductDetail($itemsData)
    {
       
       
        // Array ( [0] => Array ( [item] => 1 [quantity] => 1 [price] => 10000.00 [discount] => 0 [tax] => 1 [itemTaxPrice] => 1000.00 [itemTaxRate] => 10.00 [description] => ) )
        $item=[];
        $product=[];
      
        if(isset($itemsData['duplicate']) && $itemsData['duplicate']=="Duplicate Copy"){
            $productCount=count($itemsData)-1;
        }else{
            $productCount=count($itemsData);
        }
        for ($i = 0; $i < $productCount; $i++) {
         
            if(isset($itemsData['duplicate']) && $itemsData['duplicate']=="Duplicate Copy"){
                $product = ProductService::find($itemsData[$i]['product_id']);
            }else if(isset($itemsData[$i]['item'])){
                $product = ProductService::find($itemsData[$i]['item']);
            }
            $productVatAmount=!empty($itemsData[$i]['itemTaxPrice'])? $itemsData[$i]['itemTaxPrice']:(int)$itemsData[$i]['tax'];

            if(!empty($product['id']) && (isset($itemsData[$i]['item']) || isset($itemsData[$i]['product_id']))){
                $item[$i]['itemNo'] = $i;
                $item[$i]['taxCode'] =  $product->tax_category; //need to know
                $item[$i]['nature'] = $product->type;
                $item[$i]['productCodeMra']= "";
                $item[$i]['productCodeOwn']=$product->sku;
                $item[$i]['itemDesc'] =!empty($product->description)?$product->description:$itemsData[$i]['description'] ;
                $item[$i]['quantity']= $itemsData[$i]['quantity'];
                $item[$i]['unitPrice']= $itemsData[$i]['price'];
                $item[$i]['discount']= $itemsData[$i]['discount'];
                $item[$i]['discountedValue']= $itemsData[$i]['discount'];
                $item[$i]['amtWoVatCur']= $itemsData[$i]['price'];
                $item[$i]['amtWoVatMur']=  $itemsData[$i]['price'];
                $item[$i]['vatAmt']= $productVatAmount;
                $item[$i]['totalPrice']=$productVatAmount+$itemsData[$i]['price']-$itemsData[$i]['discount'];
            }
        }
//     print_r($item);
//         echo "===========================================================================================";
        
//    die;
        
       return $item;
    }
}
