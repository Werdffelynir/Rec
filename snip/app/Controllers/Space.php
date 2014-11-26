<?php

namespace app\Controllers;

use \app\Base;

class Space extends Base
{
    public $action;
    public $actionParam;
    public $actionParams = [];

    public function init()
    {
        parent::init();

        if (!$this->auth) $this->redirect();
    }

    public function index()
    {
        if($args = func_get_args())
        {
            $this->action = $args[0];
            if(!empty($args[2]))
                $this->actionParams[$args[1]] = $args[2];
            if(!empty($args[1]))
                $this->actionParam = $args[1];
        }

        switch ($this->action)
        {
            case 'create':
                $this->editform();
                break;

            case 'edit':
                //snippet_45
                //$this->actionParam
                $this->editform();
                break;

            case 'save':
                if(is_numeric($this->actionParam))
                    $this->Records->save($this->actionParam);
                else
                    $this->Records->save();
                break;

            case 'profile':
                $this->space();
                break;

            case 'cat':
                $this->cat($this->actionParam);
                break;

            case 'snippet':
                $this->snippet($this->actionParam);
                break;

            default:
                $this->space();
        }

    }

    public function space()
    {

        $this->render('index',
            [
                'contentLeft' => '<h1>space</h1>',
                'contentRight' => $this->renderPartial('sidebarProfile',
                    [
                        'userData' => $this->userData,
                        'status' => $this->UserControl->status,
                    ]),
            ]);
    }

    public function editform()
    {
        $fields = $this->modelSnippets->fields;

        $subcategories = [];
        $_subcategories = $this->Records->privateSubcategory();
        foreach($_subcategories as $_subcat) {
            $subcat['id'] = $_subcat['id'];
            $subcat['title'] = $_subcat['title'];
            $subcategories[$_subcat['id_category']][] = $subcat;
        }

        $this->render('editform',
            [
                'userData' => $this->userData,
                'status' => $this->UserControl->status,
                'fields' => $fields,
                'categories' => $this->Records->privateCategoryList(),
                'subcategories' => $subcategories,
            ]);
    }

    public function profile()
    {
        $this->render('index',
            [
                'contentLeft' => '<h1>profile</h1>',
                'contentRight' => $this->renderPartial('sidebarProfile',
                    [
                        'userData' => $this->userData,
                        'status' => $this->UserControl->status,
                    ]),
            ]);
    }

    public function cat($link, $page = 1)
    {
        $allRecords = $this->modelSnippets->allSnippetsByCategoryLink($link, 'private', $this->UserControl->id);

        $this->render('index', [
            'contentLeft' => $this->renderPartial('//main/items', [
                'allRecords' => $allRecords,
                'type' => 'private',
            ]),
            'contentRight' => $this->viewTree($link, 'private'),
        ]);
    }

    public function snippet($link, $page = 1)
    {
        $record = $this->modelSnippets->recordLink($link, $this->UserControl->id);

        $this->render('index', [
            'contentLeft' => $this->renderPartial('//main/view', [
                'record' => $record,
                'type' => 'private',
                'auth' => $this->auth,
                'userData' => $this->userData,
            ]),
            'contentRight' => $this->viewTree($record->cat_link,'private', $this->UserControl->id),

        ]);
    }

} 