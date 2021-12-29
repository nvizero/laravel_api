<?php

namespace App\Admin\Controllers;

use App\Models\OrderDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OrderDetail';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderDetail());

        $grid->column('id', __('Id'));
        $grid->column('user_name', __('使用者名稱'));
        $grid->column('price',  __('價格'));
        $grid->column('num', __('數量'));
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
        $show = new Show(OrderDetail::findOrFail($id));
        $show->column('title', __('商品分類名稱'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new OrderDetail());
        $form->text('title', __('商品分類名稱'))->rules('min:2');


        return $form;
    }
    /**
     * 
     */
}
