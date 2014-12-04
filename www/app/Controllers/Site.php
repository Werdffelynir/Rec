<?php

namespace app\Controllers;


use app\BaseController;

class Site extends BaseController
{
    public function actions()
    {
        /*
        return [
            'actionLogin'=>'login',
            'actionLogout'=>'logout',
            'actionRegister'=>'register',
            'actionDownloads'=>'downloads',
            'actionContacts'=>'contacts',
        ];
        */
    }

    public function index()
    {
        $this->render('main',[
            'leftBox'=>'Content into left block',
            'rightBox'=>'Content into right block',
        ]);
    }

    public function actionLogin()
    {
        if($this->isAjax()){
            echo 'some data';
            exit;
        }

        $this->render('formLogin');
    }

    public function actionLogout()
    {
        $this->render('formLogin');
    }

    public function actionRegister()
    {
        $this->render('formRegister');
    }

    public function actionDownloads()
    {
        $this->render('main',[
            'leftBox'=>'Content into left block',
            'rightBox'=>'Content into right block',
        ]);
    }
    public function actionContacts()
    {
        $this->intoPoint('mainFooter');
        $this->render('formContacts');
    }

    /*TEMP*/
    public function login() {
        if(!$this->isAjax()) {
            $this->render('formLogin');
        } else echo 'Template is loaded!';
    }
    public function register() {
        $this->render('formRegister');
    }
    public function downloads() {
        $this->render('main',[
            'leftBox'=>'Content into left block',
            'rightBox'=>'Content into right block',
        ]);
    }
    public function contacts() {
        $this->render('formContacts');
    }
    /*TEMP_END*/
}