<?php

namespace app\Models;

use rec\Model;

class Snippets extends Model
{
    public $table = 'snippets';
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