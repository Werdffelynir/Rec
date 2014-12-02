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

        $this->render('index',[
            'dataLeft' => $this->renderPartial('lists',[]),
            'dataRight'=> $this->renderPartial('//parts/user_box',[]),
        ]);
    }

    public function cat($link)
    {
        $this->render('index',[
            'dataLeft' => $this->renderPartial('lists',[]),
            'dataRight'=> $this->renderPartial('//parts/tree_box',[]),
        ]);
    }
    public function snippet($id)
    {
        $this->render('index',[
            'dataLeft' => $this->renderPartial('snippet',[]),
            'dataRight'=> $this->renderPartial('//parts/tree_box',[]),
        ]);
    }

}