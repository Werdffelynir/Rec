<?php

namespace app\Controllers;

use \app\Base;
use app\Models\Snippets;
use app\Models\Users;
use rec\Rec;

class Main extends Base
{

    public function actions()
    {
        return [
            'acLogin'=>'login',
            'acLogout'=>'logout',
            'acRegister'=>'register',
        ];
    }

    public function index()
    {
        $this->render('index', [
            'contentLeft'=> null,
            'contentRight'=> null,
        ]);
    }

    public function acRegister()
    {

        if($this->isAjax())
        {
            $firstName = $this->post('full_name');
            $email = $this->post('email');
            $password1 = $this->post('password');
            $password2 = $this->post('password_again');

            if(!empty($firstName) && !empty($email) && !empty($password1) && !empty($password2) && $password1 == $password2){
                $usersModel = new Users();
                $checkEmail = $usersModel->db->getByAttr('email',$email);
                if($checkEmail){
                    echo 'error';
                }else{
                    $hashPassWord = hash("md5", $password1);
                    $resultInsert = $usersModel->db->insert(
                        ['login','password','name','email','date_create','role'],
                        [
                            'login'=>$email,
                            'password'=>$hashPassWord,
                            'name'=>$firstName,
                            'email'=>$email,
                            'date_create'=>date("d.m.Y H:i:s"),
                            'role'=>'1',

                        ]);
                    if($resultInsert){
                        $this->acLogin($email,$password1,false);
                        echo 'success';
                    }
                }
            }
            exit;
        }

        $this->render('index', [
            'contentLeft'=> $this->renderPartial('register'),
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
        $edit->formDataFill();
        $this->render('edit', [
            'formData'=> $edit->formData,
            'userId'=> $this->auth,
        ]);
    }

    public function edit($link)
    {
        $edit = new Edit();
        $edit->formDataFill();
        $edit->updateData($link);
        $this->render('edit', [
            'formData'=> $edit->formData,
            'userId'=> $this->auth,
        ]);
    }

    public function save($id=null)
    {
        $edit = new Edit();
        $edit->formDataFill();
        $edit->save($id);
    }

    public function delete($link)
    {

    }

} 