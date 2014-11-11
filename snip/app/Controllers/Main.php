<?php

namespace app\Controllers;

use \app\Base;

class Main extends Base
{
    public function beforeAction(){}

    public function afterAction(){}

    public function actions(){}

    public function index()
    {
        $this->render(false);
    }

    public function home()
    {
        $this->render(false);
    }

} 