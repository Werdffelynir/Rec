<?php

namespace app\Controllers;


use app\BaseController;

class Site extends BaseController
{
    public function actions()
    {
        return [
            'actionLogin'=>'login',
            'actionLogout'=>'logout',
            'actionRegister'=>'register',
            'actionDownloads'=>'downloads',
            'actionContacts'=>'contacts',
        ];
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
        $this->setOut('mainFooter');

        $this->render('formContacts');
    }

}