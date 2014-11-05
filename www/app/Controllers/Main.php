<?php

namespace app\Controllers;

use \app\Base;

class Main extends Base
{
    public function index()
    {
        echo $this->title;
        echo ' class Main';
    }

    public function init(){
        //echo 'init';
    }

    public function beforeAction(){
        //echo 'beforeAction';
    }

    public function afterAction(){
        //echo 'afterAction';
    }

    public function test()
    {
        echo ' class Main test';
    }

    public function page()
    {
        echo ' class Main page';
    }
    public function main()
    {
        $this->render('//content');
    }
} 