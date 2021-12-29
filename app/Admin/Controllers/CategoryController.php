<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', __('Id'));
        $grid->column('title', __('商品分類名稱'));
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
        $show = new Show(Category::findOrFail($id));
        $show->column('title', __('商品分類名稱'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Category());
        $form->text('title', __('商品分類名稱'))->rules('min:2');
        $allCategories = Category::pluck('title', 'id')->all();
        $initialCategory = [0 => '第一層'];
        $allCategories = array_merge($initialCategory, $allCategories);
        $form->select('parent_id', '層級')->options($allCategories);

        return $form;
    }
    /**
     * 
     */
}
