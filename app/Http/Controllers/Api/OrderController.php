<?php
  
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Context;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderController extends Controller
{   
    public $mainKey =   'orders';
    public $expire  = 3600;
    public function __construct()
    {
        $this->middleware('auth:api');        
    }

    /**
     * 下單     
     * Show the profile for the given user.
     *
     * @param  Request  $request     
     * @return Response
     */
    public function addToCart(Request $request){
    	$user = auth()->user();    	
        $product_id = $request->product_id;
        $user_id = $request->user_id;
        $product = Product::findOrFail($product_id);        
        $user_key = "cart:$user_id";
        $style1 = $request->style1;
		$style2 = $request->style2;
		$cart = Redis::get($user_key);
		if($cart){
			$cart = json_decode($cart,true);        	
		}
        if(!isset($cart[$user_id][$product_id])) {        	
			// 透過全域幫助函式...			         	
        	$cart[$user_id][$product_id][1] = 
			            [
			                "name" => $product->name ,
			                "num" => $request->num ,
			                "price" => $request->price ,
			                "image" => $product->avatar ,
			                "style1" => $style1 ,
			                "style2" => $style2 ,
			                "product_id" => $request->product_id 
			            ];  
			
        } else {
        	foreach($cart[$user_id][$product_id] as $p_key => $product_row){        	
	    		if(
	    			$request->style1 == $cart[$user_id][$product_id][$p_key]["style1"] && 
	    			$request->style2 == $cart[$user_id][$product_id][$p_key]["style2"] &&
	    			$request->product_id == $cart[$user_id][$product_id][$p_key]["product_id"] &&
	    			$request->price == $cart[$user_id][$product_id][$p_key]["price"]        		
	    		){        			
	    			$cart[$user_id][$product_id][$p_key]['num']++;
	    		}else{       
	    			$cart[$user_id][$product_id][$p_key+1] = 
			            [
			                "name" => $product->name,
			                "image" => $product->avatar,
			                "num" => $request->num ,
			                "price" => $request->price,			                
			                "style1" => $style1 ,
			                "style2" => $style2,
			                "product_id" => $request->product_id 
			            ]; 
			    }  
		    }      	
        }
                
        Redis::set($user_key , json_encode($cart) );
        Redis::expire($user_key,$this->expire);
        return response()->json(
        	[	        		
        		'msg' => 'success Product added to cart successfully!', 
        		'state' => 1,
        		'cart' => $cart,
        		'count' => count($cart[$user_id][$product_id])
        	]
        );        
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

    /**
     * 產品細項
     * */    
    public function getCart(Request $request){
    	$user_id = $request->user_id;
		$user_key = "cart:$user_id";
        
		$cart = Redis::get($user_key);
    	return $cart;     	   
    }
}