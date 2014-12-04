<?php

namespace app\Controllers;


use app\BaseController;

class Blog extends BaseController
{
    public function actions()
    {
        return [
            'actionList'=>'list',
            'actionArticle'=>'article',
        ];
    }

    public function index()
    {
        $this->render('main',[
            'content'=>'Blog some content',
        ]);
    }

    public function actionList()
    {
        $this->render('main',[
            'content'=>'actionList',
        ]);
    }

    public function actionArticle()
    {
        $this->render('main',[
            'content'=>'actionArticle',
        ]);
    }


    public function lists()
    {
        $this->render('main',[
            'content'=>'actionList',
        ]);
    }

    public function article()
    {
        $this->render('main',[
            'content'=>'actionArticle',
        ]);
    }

}