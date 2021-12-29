<?php

namespace App\Admin\Controllers;

use App\Models\Product;
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

        $grid->column('id', __('Id'));
        $grid->column('name', __('商品名稱'));
        // $grid->column('description', __('說明'));
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
        // $form->textarea('name', __('商品名稱'));
        // //$form->ckeditor('description')->options(['lang' => 'fr', 'height' => 100]);
        // $form->selfMakeCkeditor('description');

        // $form->text('price', __('價錢'));
        // $form->multipleImage('image', __('圖片'))->move('uploads/images')->removable();
        // 第一列占据1/2的页面宽度
        $form->column(12, function ($form) {
            $form->text('name', __('名稱'))->rules('min:2');
            $form->text('tags', __('標籤'));
            $form->text('price', __('價錢'));
            $form->text('category_id', __('分類'));
            $form->selfMakeCkeditor('description', __('簡述'))->options(['lang' => 'fr', 'height' => 100]);
            $form->multipleImage('image', __('圖片'))->move('uploads/images')->removable();
        });
        // $form->saving(function (Form $form) {
        //     echo $form->model()->id;
        //     echo "\n..... ";
        //     echo $form->model()->price;
        //     dump($form->price);
        //     die;
        // });
        return $form;
    }
    /**
     * 
     */
}
