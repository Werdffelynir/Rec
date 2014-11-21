<?php

namespace app;

use app\Controllers\UserControl;
use app\Controllers\Records;
use \rec\Rec;
use \rec\Controller;
use \app\Models\Category;

class Base extends Controller
{
    public $title;
    public $auth = false;
    public $userData = null;

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

        # Контроль пользователей
        $this->UserControl = new UserControl();
        $this->auth = $this->UserControl->auth;
        $this->userData = $this->UserControl->userData;


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



    # Common views parts
    # * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

    public function viewCategory()
    {
        $publicCategory = $this->Records->publicCategory();
        $privateCategory = $this->Records->privateCategory();

        $this->renderPartial('//layout/menuCategory',
            [
                'auth'=>$this->auth,
                'userData'=>$this->UserControl->userData,
                'publicCategory'=>$publicCategory,
                'privateCategory'=>$privateCategory,
            ],
            false);
    }

    public function activeCategory()
    {
        $this->categories = Category::model()->db->getAll(null, "visibly=1 and type='public'");

        if($this->auth){
            $this->categoriesUsers = Category::model()->db->getAll(null, "visibly=1 and id_user='".$this->UserControl->id."'");
        }

    }



    public function userSidebar()
    {
        $htmlData = '';
        $htmlData .= $this->renderPartial('//main/user', [
                'auth'=>$this->auth,
                'userData'=>$this->UserControl->userData,
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