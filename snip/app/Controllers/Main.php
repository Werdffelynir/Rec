<?php

namespace app\Controllers;

use \app\Base;

class Main extends Base
{

    public function beforeAction(){}

    public function afterAction(){}

    public function actions()
    {
        return [
            'acLogin'=>'login',
        ];
    }

    public function acLogin()
    {
        print_r($_POST);
    }

    public function index()
    {
        $this->render(false);
    }

    public function home()
    {
        $this->render(false);
    }

} 