<?php

namespace app\Controllers;

use \app\Base;

class Main extends Base
{

    public function init(){
        //echo 'init';
    }

    public function beforeAction(){
        //echo 'beforeAction';
    }

    public function afterAction(){
        //echo 'afterAction';
    }

    public function index()
    {
        $this->render('//out', [
            'title'=> $this->applicationName,
            'content'=>'Home Page',
        ]);
    }

    public function home()
    {
        $this->render('home',['text'=>'Home Page']);
    }

} 