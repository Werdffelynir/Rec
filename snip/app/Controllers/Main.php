<?php

namespace app\Controllers;

use \app\Base;

class Main extends Base
{

    public function actions()
    {
        return [
            'actionLogin' => 'login',
            'actionLogout' => 'logout',
            'actionRegister' => 'register',
        ];
    }

    public function index()
    {
        $this->render('index', [
            'contentLeft' => null,
            'contentRight' => $this->userSidebar(),
        ]);
    }

    public function search($words)
    {
        $this->render('index', [
            'contentLeft' => null,
            'contentRight' => null,
        ]);
    }

    # SHOW RECORDS
    #  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *

    /**
     * @param $link
     * @param int $page
     */
    public function cat($link, $page = 1)
    {
        $allRecords = $this->modelSnippets->allSnippetsByCategoryLink($link, 'public');

        $this->render('index', [
            'contentLeft' => $this->renderPartial('items', [
                'allRecords' => $allRecords,
                'type' => 'public',
            ]),
            'contentRight' => $this->viewTree($link, 'public'),
        ]);
    }

    public function subcat($link, $page = 1)
    {

    }

    public function snippet($link, $page = 1)
    {
        $record = $this->modelSnippets->recordLink($link);

        $this->render('index', [
            'contentLeft' => $this->renderPartial('view', [
                'record' => $record,
                'type' => 'public',
                'auth' => $this->auth,
                'userData' => $this->userData,
            ]),
            'contentRight' => $this->viewTree($record->cat_link,'public'),
        ]);
    }


    # ADMIN CONTROL PANEL
    #  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *


    public function panel()
    {
        if (!$this->auth) $this->redirect();
        $status = strtolower($this->UserControl->status);

        $this->render('index', [
            'contentLeft' => $status,
            'contentRight' => 'panel',
        ]);
    }





    # EDITS RECORDS
    #  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *

    public function create()
    {
        $edit = new Edit();
        $edit->formDataFill();
        $this->render('edit', [
            'formData' => $edit->formData,
            'userId' => $this->auth,
        ]);
    }

    public function edit($link)
    {
        $edit = new Edit();
        $edit->formDataFill();
        $edit->updateData($link);
        $this->render('edit', [
            'formData' => $edit->formData,
            'userId' => $this->auth,
        ]);
    }

    public function save($id = null)
    {
        $edit = new Edit();
        $edit->formDataFill();
        $edit->save($id);
    }

    public function delete($link)
    {

    }

} 