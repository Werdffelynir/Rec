<?php

namespace app\Controllers;


use app\BaseController;

class Space extends BaseController
{
    public function index()
    {
        $this->render('//column_two',[
            'dataLeft' => $this->renderPartial('space',[]),
            'dataRight'=> $this->renderPartial('//parts/user_box',[]),
        ]);
    }


    public function profile()
    {
        $this->render('//column_two',[
            'dataLeft' => $this->renderPartial('profile',[]),
            'dataRight'=> $this->renderPartial('//parts/user_box',[]),
        ]);
    }


    public function edit($id=null)
    {
        $this->render('//column_one',[
            'data' => $this->renderPartial('editform',[]),
        ]);
    }

} 