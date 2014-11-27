<?php

namespace app\Controllers;


use app\BaseController;

class Index extends BaseController
{

    public function index(){
        $this->render('main');
    }


}