<?php

namespace app\Models;

use rec\Model;

class Items extends Model
{
    public $table = 'items';
    public $primaryKey = 'id';
    /**
     * @param string $className
     * @return Model|$this
     */
    public static function model($className = __CLASS__)
    {
        $model = parent::model($className);
        return $model;
    }

    public function itemsList()
    {
        $result = $this->db->getAll($this->table,[],"type='blog' AND visibly=1");
        return $result;
    }






}
