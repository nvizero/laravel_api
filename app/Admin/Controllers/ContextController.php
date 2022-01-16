<?php

namespace App\Admin\Controllers;

use App\Models\Context;
use App\Models\Category;
use App\Models\CategoryStyle;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ContextController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Context';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Context());
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('title', __('名稱'));        
        $grid->column('short', __('名稱'));        
        $grid->column('content',  __('說明'))->display(function ($content) {
            $content = strip_tags($content);
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
        $show = new Show(Context::findOrFail($id));
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
        $form = new Form(new Context());
        $form->text('title', __('名稱'))->rules('min:2');
        $form->text('short', __('簡寫'))->rules('min:2');        
        $form->selfMakeCkeditor('content', __('說明'))->options(['lang' => 'fr', 'height' => 100]);

        return $form;
    }
    /**
     * 
     */
}
