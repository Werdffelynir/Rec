<?php

namespace app\Controllers;

use \app\Base;
use \app\Models\Pages;

class Main extends Base
{
    public function beforeAction(){}

    public function afterAction(){}

    public function actions()
    {
        return [
            'one'=>'//Components/actions/one',
            'two'=>'//Components/actions/two',
            'inside'=>'inside'
        ];
    }

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
        $this->render('index', [
            'title'=> $this->applicationName,
            'content'=>'<img src="'.\rec\Rec::$url.'public/images/img.jpg" alt=""/>'
        ]);
    }

    public function inside()
    {
        var_dump('inside');
    }

    public function ajax($on)
    {
        if($this->isAjax()) {
            echo 'IsAjax: '.$on;
            print_r($_POST);
        }else{
            echo 'Is NOT Ajax';
            echo $on;
        }
    }
} 