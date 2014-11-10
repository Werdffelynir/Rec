<?php

namespace app\Controllers;

use \app\Base;
use app\Models\Items;
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

    public function docs()
    {
        $mItems = new Items();
        $list = $mItems->itemsList();

        $this->render('docs',['list'=>$list]);
    }

    public function doc($id)
    {
        $mItems = new Items();
        $document = $mItems->db->getById($mItems->table, $id);

        $this->render('doc',['document'=>$document]);
    }

} 