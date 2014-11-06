<?php

namespace app\Models;

use rec\Rec;
use rec\Model;
use rec\RPDO;

class Pages extends Model
{

    public function init()
    {
        //documentation.sqlite
        //self::setConnection('db','sqlite:'.Rec::$pathApp.'Database\documentation.sqlite');
        //self::getConnection('db');
    }

    /**
     * @param string $className
     * @return mixed|$this
     */
    public static function model($className = __CLASS__)
    {
        /** @var Model $model */
        $model = parent::model($className);
        return $model;
    }

    public function getRecItem($id=null)
    {
        //$lastId =  $this->db->lastId('items'); //getAll('items'); // query('select * from items where id=1');

        //$result = $this->db->query('select * from items where id=1')->all();
        $result = $this->dbMysql->query('select * from sample_two')->all();
        var_dump(
            $this->dbMysql//RPDO::$STH
        );
        //RPDO::$DB
        if($id==null){
            //$this->db->all();
        }
        return true;
    }


} 