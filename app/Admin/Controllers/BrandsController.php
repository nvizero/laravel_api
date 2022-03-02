<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BrandsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Brands';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Brand());

        $grid->column('id', __('Id'));
        $grid->column('title', __('商品分類名稱'));
        $grid->column('comment', __('是否在前台顯示'));
        $grid->column('sort', __('排序'));
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
        $show = new Show(Brand::findOrFail($id));
        $show->column('title', __('商品分類名稱'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Brand());
        $form->text('title', __('品牌名稱'))->rules('min:2');        
        $form->text('comment', __('品牌簡述'))->rules('min:1');                

        return $form;
    }
    /**
     * 
     */
}
