<?php

namespace app\Controllers;

use \app\Base;
use app\Models\Snippets;

class Main extends Base
{

    public function actions()
    {
        return [
            'acLogin'=>'login',
            'acLogout'=>'logout',
        ];
    }

    public function index()
    {
        $this->render('index', [
            'contentLeft'=> null,
            'contentRight'=> null,
        ]);
    }


    public function edit($type='create',$link=null)
    {
        $edit = new Edit($type, $link);

        $this->render('edit', [
            'formData'=> $edit->formData,
            'userId'=> $this->auth,
        ]);
    }

    public function cat($c_link, $sc_link=null)
    {
        $snipModel = new Snippets();
        $allRecords = $snipModel->allByCategoryLink($c_link);
        $treeRecords = $snipModel->treeCategoryLink($c_link);

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('items', ['allRecords'=>$allRecords]),
            'contentRight'=> $this->renderPartial('tree', ['treeRecords'=>$treeRecords]),
        ]);
    }

    public function snippet($s_link)
    {
        $snipModel = new Snippets();
        $record = $snipModel->recordLink($s_link);
        $treeRecords = $snipModel->treeCategoryLink($record->cat_link);

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('view', ['record'=>$record]),
            'contentRight'=> $this->renderPartial('tree', ['treeRecords'=>$treeRecords]),
        ]);
    }
} 