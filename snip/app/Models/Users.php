<?php

namespace app\Models;

use rec\Model;

class Users extends Model
{
    public $table = 'users';
    public $primaryKey = 'id';

    public function init(){}

    /**
     * @param string $className
     * @return Model|$this
     */
    public static function model($className = __CLASS__)
    {
        $model = parent::model($className);
        return $model;
    }


    public function registerUser($data)
    {
        if( !empty($data['full_name']) && !empty($data['email']) &&
            !empty($data['password']) && $data['password'] == $data['password_again'])
        {
            $checkEmail = $this->db->getByAttr('email',$data['email']);
            if($checkEmail){
                return false;
            }else{
                // ava name
                $avaName = 'ava_3.png';
                $hashPassWord = hash("md5", $data['password']);
                $dataInsert = [
                    'login'=>$data['email'],
                    'password'=>$hashPassWord,
                    'name'=>$data['full_name'],
                    'email'=>$data['email'],
                    'date_create'=>date("d.m.Y H:i:s"),
                    'active'=>'1',
                    'role'=>'1',
                    'profession'=>$data['profession'],
                    'ava'=>$avaName,
                    'code_lang'=>$data['code_lang'],
                ];
                $resultInsert = $this->db->insert($dataInsert);

                if($resultInsert){

                    if(!empty($_FILES['ava']['name'])){
                        $tmpFile = $_FILES['ava']['tmp_name'];
                        if(is_uploaded_file($tmpFile)) {
                            $newAvaName = $resultInsert.'_user_ava.'.substr($_FILES['ava']['name'],strrpos($_FILES['ava']['name'],'.')+1);
                            $newFile = 'public/upload/user_ava/'.$newAvaName;
                            if(move_uploaded_file($tmpFile, $newFile)){
                                $this->db->update(
                                    [
                                    'ava'=>$newAvaName
                                    ],
                                    'id='.$resultInsert
                                );
                            }
                        }
                    }
                    return [
                        'email'=>$data['email'],
                        'password'=>$data['password']
                    ];
                }
            }
        }
        return false;
    }





}
