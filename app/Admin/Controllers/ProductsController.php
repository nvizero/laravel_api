<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductCategoryStyle;
use App\Models\ProductAttributes;
use App\Models\Category;
use App\Models\CategoryStyle1;
use App\Models\CategoryStyle2;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('商品名稱'));        
        $grid->column('description',  __('說明'))->display(function ($description) {
            $content = strip_tags($description);
            return mb_substr($content, 0, 20, 'utf-8');
        });
        $grid->filter(function($filter){            
            $filter->disableIdFilter();            
            $filter->like('name', '商品名稱');
            $filter->like('description', '商品說明');            
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('商品名稱'));
        $show->textarea('description', __('說明..'));
        $show->image()->unescape()->as(function ($avatar) {
            return "<img src='/{$avatar}' />";
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Product());    
        $categories = Category::get();
        $brands = Brand::get();
        $categorieStyles1 = CategoryStyle1::get();
        $categorieStyles2 = CategoryStyle2::get();
        $form->text('name', __('名稱'))->rules('min:2');
        $form->text('little', __('下方小標題'))->rules('min:2');
        $form->multipleSelect('tags',__('標籤'))->options($categories->pluck('title','title'));
        $form->text('price', __('價錢'))->rules('min:2');
        $form->text('taiwan_price', __('台灣價錢'))->rules('min:2');        
        $form->select('category_id', '分類')->options($categories->pluck('title','id'))->rules('min:1');

        $form->select('brand','品牌')->options($brands->pluck('title','title'));
        $form->image('avatar', '列表圖片')->move('uploads/images')->removable();

        $form->radio('status','上架')->options([1 => '上架', 2 => '下架'])->default('1');
        // $form->column(1/2, function ($form) {
            $form->time('start_time','開始時間')->format('YYYY-MM-DD');
            $form->time('end_time','開始時間')->format('YYYY-MM-DD');
        // });
        // 子表字段        
        $form->table('other_price','團購達標量', function ($table)     {
            $table->text('num','數量');
            $table->text('price','價格');            
            $table->text('content','備註');
        });

        $form->hasMany('attributes', 'size/大小/尺寸', function (Form\NestedForm $f) {
            $f->text('style1', 'size/大小/尺寸');
            $f->text('style2', '顏色/型號');
            $f->text('num','庫存-數量');
            $f->text('price','價格');
            $f->image('image', __('圖片'))->move('uploads/images');
            $f->text('content','備註');            
        });        
        
        $form->textarea('txt', __('簡述'))->options(['lang' => 'fr', 'height' => 200]);
        $form->selfMakeCkeditor('description', __('商品說明'))->options(['lang' => 'fr', 'height' => 200]);
        $form->multipleImage('image', __('圖片'))->move('uploads/images')->removable();

        $form->saved(function (Form $form) {
            $this->productSaving($form);            
        });
                

        return $form;
    }
    /**
     * 產品型號/尺寸/SIZE
     */
    public function productSaving($form){        
        if(!empty($form->attributes))     
        foreach($form->attributes as $attr_id => $row){
            
            //先插入ProductAttributes
            if(is_numeric($attr_id)){
                ProductAttributes::find($attr_id)->update(['category_id'=>$form->category_id]);
            }
            //STYLE1的字串切割
            //插入category_style    
            //再做關聯
            if(!empty($row['style1'])){
                $exStyle1 = explode('#',$row['style1']);                
                $this->updateProductCateStyle($exStyle1 , $form ,1);                
            }
            if(!empty($row['style2'])){
                $exStyle2 = explode('#',$row['style2']);
                $this->updateProductCateStyle($exStyle2 , $form ,2);
            }
        }
    }

    /**
     * 整理產品型號/尺寸/大小/寫入到DB
     */
    public function updateProductCateStyle($datas , $form , $type){
        foreach($datas as $key => $str){
            if(!empty($str)){
                if( $type == 1 ){
                    $categoryStyle = new CategoryStyle1();
                }else{
                    $categoryStyle = new CategoryStyle2();
                }                
                $categoryStyleData = ['name'=> $str, 'category_id'=>$form->category_id];
                $cateEntity = $categoryStyle->where($categoryStyleData)->first();
                if(!$cateEntity){                            
                    $cateEntity = $categoryStyle->create($categoryStyleData);
                }
                $insertProCateStyleData = [
                    'category_id' => $form->category_id,
                    'product_id'  => $form->model()->id,
                    'category_styles_id' => $cateEntity->id,
                    'type' => $type
                ];
                $prodCateStyleEntity = ProductCategoryStyle::where($insertProCateStyleData)->first();
                if(!$prodCateStyleEntity){
                    ProductCategoryStyle::create($insertProCateStyleData);
                }
            }
        }
    }
}
