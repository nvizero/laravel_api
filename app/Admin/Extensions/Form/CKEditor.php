<?php

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/packages/ckeditor/ckeditor.js',        
    ];

    protected $view = 'admin.ckeditor';

    public function render()
    {
        
        return parent::render();
    }
}
