<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Context;
use Illuminate\Support\Facades\Redis;
class ProductController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {            
        $products = Product::all();
        return view('products', compact('products'));
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart()
    {
        return view('cart');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
          
        $cart = session()->get('cart', []);
  
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
          
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    /**
     * 
     */
    public function atest(Request $request){
        return view('atest');
    }

    /**
     * 
     */
    public function productList(Request $request){        
        $key = 'productslist';
        $expire = 3600;
        $products = Redis::get( $key);
        if(!$products){
            $products = Product::select("id","name","image","price","tags" ,'txt')
                ->orderBy('id','desc')->take(1)->limit(90)->get()->toArray();
            $products = serialize($products);
            Redis::set($key, $products);
            Redis::expire($key, $expire);
        }
        return unserialize($products);
    }

    /**
     * 
     */
    public function detail(int $id, Request $request){                
        return [
            'result' =>self::productDetail($id),
            'buyToKnow'=>self::getBuyToKnow()
        ];
    }

    /**
     * 
     */
    public function productDetail(int $id){
        $key = "product:$id";
        $expire = 3600;
        $product = Redis::get($key);
        if(!$product){
            $product = Product::find($id);
            $product = serialize([
                                    'attrib'    => $product->attributes,
                                    'product'   => $product,                                    
                                ]);        
            Redis::set($key,$product);
            Redis::expire($key,$expire);
        }
        return unserialize($product);
    }

    public function getBuyToKnow(){
        $key = "buyToKnow";
        $expire = 3600;
        $context = Redis::get($key);
        if(!$context){
            $context = Context::where('short','buy-to-know')->first();
            $context = serialize($context);        
            Redis::set($key,$context);
            Redis::expire($key,$expire);
        }
        return unserialize($context);   
    }
}