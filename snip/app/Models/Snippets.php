<?php

namespace app\Models;

use rec\Model;

class Snippets extends Model
{
    public $table = 'snippets';
    public $primaryKey = 'id';
    public $fields = [
        'id'=>'',
        'id_category'=>'',
        'id_sub_category'=>'',
        'id_user'=>'',
        'link'=>'',
        'description'=>'',
        'title'=>'',
        'content'=>'',
        'visibly'=>'',
        'ithelp'=>'',
        'tags'=>'',
        'type'=>'',
        'unlock'=>'',
        'datecreate'=>'',
    ];

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