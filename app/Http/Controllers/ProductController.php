<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Product;
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
        $main = 'product';
        $key = 'list';
        $expire = 3600;
        $products = Redis::hget($main , $key);
        if(!$products){
            $products = Product::select("id","name","image","price","tags")
                ->orderBy('id','desc')->take(1)->limit(30)->get()->toArray();
            $products = serialize($products);
            Redis::hset($main, $key, $products);
            Redis::expire($main, $expire);
        }
        return unserialize($products);
    }

    /**
     * 
     */
    public function detail(int $id, Request $request){
        $main = 'product';
        $key = $id;
        $expire = 3600;
        $product = Redis::hget($main, $key);
        if(!$product){
            $product = Product::find($id);
            $product = serialize($product);
            Redis::hset($main,$key,$product);
            
            Redis::expire($main,$expire);
        }
        return unserialize($product);
    }
}