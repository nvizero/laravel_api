<?php
  
namespace App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryStyle1;
use App\Models\CategoryStyle2;

class CategoryService
{   
    public $mainKey =   'categoryService';
    public $expire  = 3600;
    public function __construct()
    {        
    }

    /**
     * 取得產品類別
     */ 
    public function getStyleByName(int $type ,$name){    	
    	if($type==1){    		
    		$res = CategoryStyle1::where('name',$name)->first();
    	}elseif($type==2) {
    		$res = CategoryStyle2::where('name',$name)->first();
    	}    	
        return $res->id;	     	    
    }
}