<?php
  
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Context;
use Illuminate\Support\Facades\Redis;
class ContextController extends Controller
{   
    public $mainKey =   'context';
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
}