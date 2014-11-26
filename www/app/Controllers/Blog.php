<?php

namespace app\Controllers;


use app\BaseController;

class Blog extends BaseController
{

    public function index()
    {
        $this->render('main',[
            'content'=>'Blog some content',
        ]);
    }
}