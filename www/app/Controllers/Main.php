<?php

namespace app\Controllers;

use \app\Base;
use \app\Models\Pages;

class Main extends Base
{

/*    public function init(){
        //echo 'init';
    }*/

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
        $m = Pages::model()->getRecItem();
        $this->render('home',['text'=>'Home Page']);
    }

} 