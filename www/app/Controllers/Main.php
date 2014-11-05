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