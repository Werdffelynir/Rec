<?php

namespace app\Controllers;


use app\BaseController;

class User extends BaseController
{
    public function actions()
    {
        return [
            'User/actionList'=>'list',
            'User/actionArticle'=>'article',
        ];
    }

    public function index()
    {
        $this->render('main',[
            'content'=>'User some content',
        ]);
    }

    public function actionList()
    {
        $this->render('main',[
            'content'=>'User actionList',
        ]);
    }

    public function actionArticle()
    {
        $this->render('main',[
            'content'=>'User actionArticle',
        ]);
    }

}