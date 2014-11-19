<?php

namespace app;

use \rec\Rec;
use \rec\Controller;
use \app\Models\Category;
use \app\Models\Users;

class Base extends Controller
{
    public $title;
    public $auth = false;
    public $authData = null;

    public $categories = [];
    public $subcategory = [];


    public function init()
    {
        $this->title = Rec::$applicationName;

        # check user is login
        $this->checkLogin();

        # partial views
        $this->activeCategory();

    }


    /**LOGIN
     * *********************  *********************  *********************
     */
    public function acLogin($_login=null,$_password=null,$redirect=true)
    {
        $login = $this->post('login');
        $password = hash("md5",$this->post('password'));

        if(!empty($_login) && !empty($_password)){
            $login = $_login;
            $password = hash("md5",$_password);
        }

        $usersModel = new Users();
        $userData = $usersModel->db->getByAttr('login',$login,null,"and password='{$password}'");

        if(!empty($userData)){
            $userPublicData = [
                'id'=>$userData['id'],
                'login'=>$userData['login'],
                'name'=>$userData['name'],
                'email'=>$userData['email'],
                'date_create'=>$userData['date_create'],
                'role'=>$userData['role'],
            ];
            $this->authData = $userPublicData;
            $this->auth = $userData['role'];
            $this->cookie('auth', serialize($userPublicData));
        }
        if($redirect)
            $this->redirect();
    }
    public function acLogout()
    {
        $this->auth = false;
        $this->authData = [];
        $this->deleteCookie('auth');
        $this->redirect();
    }
    public function checkLogin()
    {
        $userPublicData =  $this->cookie('auth');
        if(!$this->auth && !empty($userPublicData)){
            $userPublicData = unserialize($userPublicData);

            $usersModel = new Users();
            $userData = $usersModel->db->getByAttr('login',$userPublicData['login'],null,"and date_create='{$userPublicData['date_create']}' and role='{$userPublicData['role']}'");
            if(!empty($userData)){
                $this->auth = $userData['role'];
                $this->authData = $userData;
            }
        }
    }


    /** COMMON VIEWS PARTS
     * *********************  *********************  *********************
     */
    public function activeCategory()
    {
        $this->categories = Category::model()->db->getAll(null, 'visibly=1');
    }
    public function viewCategory()
    {
        $this->renderPartial('//layout/menuCategory',
            [
                'categories'=>$this->categories
            ],
            false);
    }


    /**
     * *********************  *********************  *********************
     */
}