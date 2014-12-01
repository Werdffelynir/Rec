<?php

namespace app\Controllers;


use app\BaseController;

class Index extends BaseController
{

    public function index()
    {
        //$this->renderPoints('header');
        //$this->renderPoints('menu');
        //$this->renderPoints('footer');

        $this->render('main');
    }


}