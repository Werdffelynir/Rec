<?php

namespace app\Models;

use rec\Model;

class Subcategory extends Model
{
    public $table = 'subcategory';
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