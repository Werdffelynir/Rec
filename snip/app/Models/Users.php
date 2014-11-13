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

} 