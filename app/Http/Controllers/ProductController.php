<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductCategoryStyle;
use App\Models\Context;
use App\Models\CategoryStyle1;
use App\Models\CategoryStyle2;
use App\Service\CategoryService;
use Illuminate\Support\Facades\DB;
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
     * siteMap 用 哇出全部的ID 
     */
    public function allProducts(Request $request){        
        $key  = 'allProducts';
        $expire = 3600;
        $products = Redis::get($key);
        if(!$products){
            $products = Product::select("id",'name')->get();            
            $products = serialize($products);
            Redis::set($key, $products);
            Redis::expire($key, $expire);
        }
        return unserialize($products);
    }

    /**
     * 
     */
    public function productList(Request $request){
        $limit = $request->limit ? $request->limit : 9;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;
        $key  = 'productslist:'."$page:$limit";
        $expire = 3600;
        $products = Redis::get( $key);
        if(!$products){
            $products = Product::select("id","name","avatar","image","price","tags" ,'txt')
                ->orderBy('id','desc')->limit($limit)->paginate($limit);
            $products->setPath('');
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
        if(true){
            $product = Product::select("id",'name','txt','description','image','price','tags','taiwan_price')->find($id);
            $product = serialize([
                                    'attrib'    => $this->getProductAttrib($id), 
                                    'product'   => $product,
                                    'txt'       => strip_tags($product->txt),
                                ]);
            Redis::set($key,$product);
            Redis::expire($key,$expire);
        }
        return unserialize($product);
    }
    /**
     * 取出產品型號
     */
    public function getProductAttrib(int $product_id){
        $productAttributes = ProductAttributes::select('style2','style1')
                            ->where('product_id',$product_id)->get();
                            
        $result=[];
        $cateService = new CategoryService();
        foreach([2=>'category_styles2',1=>'category_styles1'] as $key => $row){
            if($key==2  ){                
                $res = DB::table('product_attributes')
                        ->select(  'id',"style1", "style2")                    
                        ->where('product_id', $product_id )
                        ->get();
                $empty = [];
                foreach($res as $row){
                    $strs = explode('#',$row->style1);                    
                    $category_styles  = DB::table('category_styles1')
                            ->select(  "id", 'category_id',"name")                    
                            ->whereIn('name',$strs )
                            ->get();
                    $empty[] = 
                                [
                                    'id'=> $cateService->getStyleByName(2,$row->style2),
                                    'name' => $row->style2,
                                    'style'=>$category_styles,

                                ];    
                }

                $result[] = $empty;
            }elseif($key==1){
                $res = DB::table('product_category_style')
                    ->select( "{$row}.name", "{$row}.id" , 'category_styles_id')
                    ->join("{$row}",'product_category_style.category_styles_id', '=', "{$row}.id")
                    ->where([
                        'product_category_style.product_id' => $product_id,
                        'product_category_style.type' => $key
                            ])
                    ->get();
                $result[] = $res;
            }            
        }        
        return $result;
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