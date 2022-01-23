<?php
  
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Context;
use App\Models\Category;
use Illuminate\Support\Facades\Redis;
class ContextController extends Controller
{   
    public $mainKey =   'context';
    public $menusKey =   'menus';
    /**
     * 
     */
    public function detail(string $slogan, Request $request){
        
        $key = "{$this->mainKey}:$slogan";
        $expire = 3600;
        $content = Redis::get($key);
        if(!$content){
            $content = Context::where('short',$slogan)->first();
            $content = serialize($content);
            Redis::set($key,$content);            
            Redis::expire($key,$expire);
         }
        return $content;
    }

    public function menus(Request $request){
        
        $key = "{$this->menusKey}";
        $expire = 3600;
        $content = Redis::get($key);
        if(!$content){
            $content = Category::select("title",'sort')->where('is_show',1)->orderBy('sort','desc')->get();
            $content[]=["title"=>"所有","sort"=> 99];            
            $content = json_encode($content);
            $content = json_decode($content,true);
            $content = array_reverse($content);
            $content = json_encode($content);
            Redis::set($key,$content);        
            //title: "所有",    
            Redis::expire($key,$expire);
        }
        return  json_decode($content);
    }
}