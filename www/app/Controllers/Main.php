<?php

namespace app\Controllers;

use \app\Base;
use \app\Models\Pages;

class Main extends Base
{
    public function beforeAction(){}

    public function afterAction(){}

    public function index()
    {
        $this->render('index', [
            'title'=> $this->applicationName,
            'content'=>'Home Page',
        ]);
    }

    public function home()
    {
        //$m = Pages::model()->select();
        $this->render('home',['text'=>'Home Page']);
    }

    public function page()
    {
        var_dump($this->urlArg());
        //$m = Pages::model()->select();
        //$this->render('home',['text'=>'Home Page']);
    }

} 