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
        'datecreate'=>''
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


    public function allByCategoryLink($link, $type='public')
    {
        $link = filter_var($link,FILTER_SANITIZE_STRING);

        $SQL = "SELECT sp.*,
                    ct.link as cat_link, ct.title as cat_title,
                    sct.link as sub_cat_link, sct.title as sub_cat_title
                FROM snippets sp
                LEFT JOIN Category ct ON (ct.id = sp.id_category)
                LEFT JOIN Subcategory sct ON (sct.id = sp.id_sub_category)
                WHERE ct.link='{$link}' and sp.type='{$type}'";

        return $this->db->query($SQL)->all("obj");
    }

    public function treeCategoryLink($link, $type='public')
    {
        $tree = [];
        $_tree = $this->allByCategoryLink($link, $type);
        foreach ($_tree as $tKey=>$tVal) {
            $tree[$tVal->sub_cat_title][] = $tVal;
        }
        return $tree;
    }

    public function recordLink($link, $type='public')
    {
        $link = filter_var($link,FILTER_SANITIZE_STRING);

        $SQL = "SELECT sp.*,
                    ct.link as cat_link, ct.title as cat_title,
                    sct.link as sub_cat_link, sct.title as sub_cat_title
                FROM snippets sp
                LEFT JOIN Category ct ON (ct.id = sp.id_category)
                LEFT JOIN Subcategory sct ON (sct.id = sp.id_sub_category)
                WHERE sp.link='{$link}' and sp.type='{$type}'";

        return $this->db->query($SQL)->row("obj");
    }

} 