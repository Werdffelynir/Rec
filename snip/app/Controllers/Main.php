<?php

namespace app\Controllers;

use \app\Base;
use app\Models\Snippets;
use rec\Rec;

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

    public function search($words)
    {
        $this->render('index', [
            'contentLeft'=> null,
            'contentRight'=> null,
        ]);
    }

    /**
     *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
     * SHOW RECORDS
     */

    /**
     * @param $link
     * @param int $page
     */
    public function cat($link,$page=1)
    {
        $snipModel = new Snippets();
        $allRecords = $snipModel->allByCategoryLink($link);
        $treeRecords = $snipModel->treeCategoryLink($link);

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('items', ['allRecords'=>$allRecords]),
            'contentRight'=> $this->renderPartial('tree', ['treeRecords'=>$treeRecords]),
        ]);
    }

    public function subcat($link,$page=1)
    {

    }

    public function snippet($link,$page=1)
    {
        $snipModel = new Snippets();
        $record = $snipModel->recordLink($link);
        $treeRecords = $snipModel->treeCategoryLink($record->cat_link);

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('view', ['record'=>$record]),
            'contentRight'=> $this->renderPartial('tree', ['treeRecords'=>$treeRecords]),
        ]);
    }


    /**
     *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
     * EDITS RECORDS
     */
    public function create()
    {
        $edit = new Edit();
        $this->render('edit', [
            'formData'=> $edit->formData,
            'userId'=> $this->auth,
        ]);
    }

    public function edit($link)
    {
        //$edit = new Edit($type, $link);

        //$this->render('edit', [
        //    'formData'=> $edit->formData,
        //    'userId'=> $this->auth,
        //]);
    }

    public function delete($link)
    {

    }

} 