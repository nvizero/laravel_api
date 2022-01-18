<?php
  
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Context;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends Controller
{   
    public $mainKey =   'orders';

    public function __construct()
    {
        $this->middleware('auth:api');        
    }
    /**
     * 下單 
     */
    public function orderProduct(Request $request){
    	DB::beginTransaction();
    	$params = ['user_id' ,'user_name', 'serial','total'];
    	try {
    		$s = '';
    		$order = new Order();
    		
    		foreach($params as $key => $val){    			
    			$order->$val = $request->$val;
    		}
    		$order->user_id = auth()->user()->id;
    		$order->user_name = auth()->user()->name;
    		$order->order_status = 1;
    		$order->order_status_name = '1';
    		$order->ship_status = 1;
    		$order->ship_status_name = '1';
    		$order->pay_status = 1;
    		$order->pay_status_name = '1';
    		$order->user_context = 1;
    		$order->admin_context = '1';
    		$order->serial = date("YmdHis").rand(1,1111111);
    		$order->save();
    		self::oneProduct($request->products , $order->id);    		    		
		    DB::commit();
		    return true;
		} catch (\PDOException $e) {
		    // Woopsy
		    DB::rollBack();
		    return $e->getMessage();
		}		
        
    }

    /**
     * 產品細項
     * */    
    public function oneProduct($datas , $order_id ){
		foreach($datas as $key => $row){
    		$orderDetail = new OrderDetail();
	        $orderDetail->order_id = $order_id;
        	$orderDetail->product_id = $row['product_id'];
	        $orderDetail->product_name = $row['product_name'];
	        $orderDetail->category_id   = $row['category_id'];
	        $orderDetail->category_name = $row['category_name'];
	        $orderDetail->category_style_id_1   = $row['category_style_id_1'];
	        $orderDetail->category_style_1_name = $row['category_style_1_name'];
	        $orderDetail->category_style_id_2   = $row['category_style_id_2'];
	        $orderDetail->category_style_2_name = $row['category_style_2_name'];
	        $orderDetail->num = $row['num'];
	        $orderDetail->one_price = $row['one_price'];
	        $orderDetail->total_price = $row['total_price'];	        
	        $orderDetail->save();
	    }
    	        
    }
}