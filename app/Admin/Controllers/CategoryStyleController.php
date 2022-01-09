<?php

namespace App\Admin\Controllers;

use App\Models\CategoryStyle;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryStyleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CategoryStyle';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CategoryStyle());
        $grid->column('id', __('Id'));
        $grid->column('name', __('尺寸/大小/SIZE'));
        // $allCategories = Category::pluck('title', 'id')->all();
        $grid->category()->title();

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
        $show = new Show(CategoryStyle::findOrFail($id));
        $show->column('name', __('尺寸/大小/SIZE'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new CategoryStyle());
        $form->text('name', __('尺寸/大小/SIZE'))->rules('min:1');
        $allCategories = Category::pluck('title', 'id')->all();        
        $form->select('category_id', '產品分類')->options($allCategories);

        return $form;
    }
    /**
     * 
     */
}
