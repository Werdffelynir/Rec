<?php

namespace app\Controllers;


use app\BaseController;

class Panel extends BaseController
{

    public function index(){
        $this->render('index');
    }


}