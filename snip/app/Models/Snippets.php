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


    public function allByCategoryLink($link)
    {
        $link = filter_var($link,FILTER_SANITIZE_STRING);

        $SQL = "SELECT sp.*,
                    ct.link as cat_link, ct.title as cat_title,
                    sct.link as sub_cat_link, sct.title as sub_cat_title
                FROM snippets sp
                LEFT JOIN Category ct ON (ct.id = sp.id_category)
                LEFT JOIN Subcategory sct ON (sct.id = sp.id_sub_category)
                WHERE ct.link='{$link}'";

        return $this->db->query($SQL)->all("obj");
    }

    public function recordLink($link)
    {
        $link = filter_var($link,FILTER_SANITIZE_STRING);

        $SQL = "SELECT sp.*,
                    ct.link as cat_link, ct.title as cat_title,
                    sct.link as sub_cat_link, sct.title as sub_cat_title
                FROM snippets sp
                LEFT JOIN Category ct ON (ct.id = sp.id_category)
                LEFT JOIN Subcategory sct ON (sct.id = sp.id_sub_category)
                WHERE sp.link='{$link}'";

        return $this->db->query($SQL)->row("obj");
    }

} 