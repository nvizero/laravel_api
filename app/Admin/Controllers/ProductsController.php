<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryStyle;
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
        $categorieStyles = CategoryStyle::get();
        $form->text('name', __('名稱'))->rules('min:2');
        $form->text('tags', __('標籤'));
        $form->text('price', __('價錢'))->rules('min:2');

        $form->select('category_id', '分類')->options($categories->pluck('title','id'))->rules('min:1');
        // 子表字段
        // $form->hasMany('stocks', 'size/大小/尺寸', function (Form\NestedForm $form) use ($categorieStyles) {            
        //     $form->select('category_style_id', 'size/大小/尺寸')->options($categorieStyles->pluck('name','id'))->rules('min:1');            
        // });
        $form->table('attrib','size/大小/尺寸-庫存', function ($table) use ($categorieStyles) {
            $table->text('style_txt', 'size/大小/尺寸')->options($categorieStyles->pluck('name','id'));
            $table->text('style', '顏色/型號');
            $table->text('num','庫存-數量');
            $table->text('price','價格');
            $table->image('image', __('圖片'))->move('uploads/images');
            $table->text('content','備註');
        });

        $form->table('other_price','其他價格/團購價', function ($table) {                
            $table->text('price','團購價');
            $table->text('num','數量');
        });
        
        $form->textarea('txt', __('簡述'))->options(['lang' => 'fr', 'height' => 100]);
        $form->selfMakeCkeditor('description', __('商品說明'))->options(['lang' => 'fr', 'height' => 100]);
        $form->multipleImage('image', __('圖片'))->move('uploads/images')->removable();
        
        
        return $form;
    }
    /**
     * 
     */
}
