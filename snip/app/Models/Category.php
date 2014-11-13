<?php

namespace app\Models;

use rec\Model;

class Category extends Model
{
    public $table = 'category';
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