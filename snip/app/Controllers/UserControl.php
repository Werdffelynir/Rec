<?php

namespace app\Controllers;

use app\Models\Users;
use rec\Controller;

class UserControl extends Controller
{
    public $auth = false;
    public $id;
    public $role;
    public $userData;

    public $isGuest = false;
    public $isUser = false;
    public $isModerator = false;
    public $isGod = false;

    /** @var Users $usersModel */
    private $usersModel;

    public function __construct()
    {
        parent::__construct();

        $this->usersModel = new Users();
        $userPublicData =  $this->cookie('auth');

        if(!$this->userData && !empty($userPublicData)){
            $userPublicData = unserialize($userPublicData);

            $userData = $this->usersModel->db->getByAttr('login',$userPublicData['login'],null,"date_create='{$userPublicData['date_create']}' and role='{$userPublicData['role']}'");

            if(!empty($userData))
            {
                $this->auth = true;
                $this->id = $userData['id'];
                $this->role = $userData['role'];
                $this->userData = $userData;

                switch ($userData['role'])
                {
                    case 1:
                        $this->isUser = true;
                        break;
                    case 2:
                        $this->isModerator = true;
                        break;
                    case 3:
                        $this->isGod = true;
                        break;
                }

            }else{

                $this->isGuest = true;
            }
        }
    }


    public function login($login, $password)
    {
        $hashPassword = hash("md5",$password);
        $userData = $this->usersModel->db->getByAttr('login',$login, null, "password='{$hashPassword}'");

        if(!empty($userData))
        {
            $userPublicData = [
                    'id'=>$userData['id'],
                    'login'=>$userData['login'],
                    'name'=>$userData['name'],
                    'email'=>$userData['email'],
                    'date_create'=>$userData['date_create'],
                    'role'=>$userData['role'],
                ];

            $this->cookie('auth', serialize($userPublicData));
        }
    }


    public function logout()
    {
        $this->auth = false;
        $this->userData = [];
        $this->deleteCookie('auth');
    }

}