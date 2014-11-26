<?php

namespace app;

use app\Controllers\UserControl;
use app\Controllers\Records;
use app\Models\Snippets;
use app\Models\Subcategory;
use app\Models\Users;
use \rec\Rec;
use \rec\Controller;
use \app\Models\Category;

class Base extends Controller
{
    public $title;
    public $auth = false;
    public $userData = null;

    /** @var Users $modelUsers*/
    public $modelUsers = null;
    /** @var Snippets $modelSnippets*/
    public $modelSnippets = null;
    /** @var Category $modelCategory*/
    public $modelCategory = null;
    /** @var Subcategory $modelSubcategory*/
    public $modelSubcategory = null;

    /** @var UserControl $UserControl*/
    public $UserControl;
    /** @var Records $Records*/
    public $Records;

    public $categories = [];
    public $categoriesUsers = [];
    public $subcategory = [];

    public function init()
    {
        $this->title = Rec::$applicationName;

        # Models
        $this->modelUsers = new Users();
        $this->modelSnippets = new Snippets();
        $this->modelCategory = new Category();
        $this->modelSubcategory = new Subcategory();

        # Контроль пользователей
        $this->UserControl = new UserControl();
        $this->userData = $this->UserControl->userData;
        $this->auth = $this->UserControl->auth;

        # Контроль записей
        $this->Records = new Records($this->userData);

        # partial views
        $this->activeCategory();
    }


    # Authorization actions
    # * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

    public function actionLogin($login='',$password='',$redirect=true)
    {
        if(empty($login) && empty($password))
        {
            $login = $this->post('login');
            $password = $this->post('password');
        }

        $this->UserControl->login($login, $password);

        if($redirect)
            $this->redirect();
    }

    public function actionLogout()
    {
        $this->UserControl->logout();
        $this->redirect();
    }

    public function actionRegister()
    {
        if($this->auth)
            $this->redirect('index');

        if($this->isAjax())
        {
            $result = $this->modelUsers->registerUser($_POST);
            if($result){
                $this->actionLogin($result['email'],$result['password'],false);
                echo 'success';
            }
            exit;
        }

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('register',['auth'=> $this->auth,]),
            'contentRight'=> null,
        ]);
    }



    # Common views parts
    # * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

    public function viewCategory()
    {
        $publicCategory = $this->Records->publicCategoryList();
        $privateCategory = $this->Records->privateCategoryList();

        $this->renderPartial('//layout/menuCategory',
            [
                'auth'=>$this->auth,
                'userData'=>$this->userData,
                'publicCategory'=>$publicCategory,
                'privateCategory'=>$privateCategory,
            ],
            false);
    }


    public function viewTree($link, $type)
    {

        $treeRecords = [];
        if($type=='public')
            $treeRecords = $this->modelSnippets->treeCategoryLink($link, 'public');
        else if($this->auth && $type=='private')
            $treeRecords = $this->modelSnippets->treeCategoryLink($link, 'private', $this->UserControl->id);

        return $this->renderPartial('//main/tree',[
            'treeRecords' => $treeRecords,
            'userData' => $this->userData,
            'auth' => $this->auth,
            'link' => $link,
            'type' => $type,
        ]);
    }

    public function viewCreate()
    {
        /*
        return $this->renderPartial('tree',[
            'userData' => $this->userData,
            'auth' => $this->auth,
        ]);*/
    }

    public function activeCategory()
    {
        /*$this->categories = Category::model()->db->getAll(null, "visibly=1 and type='public'");

        if($this->auth){
            $this->categoriesUsers = Category::model()->db->getAll(null, "visibly=1 and id_user='".$this->UserControl->id."'");
        }*/

    }



    public function userSidebar()
    {
        $htmlData = '';
        $htmlData .= $this->renderPartial('//main/user', [
                'auth'=>$this->auth,
                'userData'=>$this->UserControl->userData,
                'status'=>$this->UserControl->status,
            ]);
        $htmlData .= $this->renderPartial('//main/services', [
                'auth'=>$this->auth
            ]);
        return $htmlData;
    }


    public function snippetsTreeSidebar()
    {

    }


}