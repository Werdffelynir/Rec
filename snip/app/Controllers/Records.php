<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Snippets;
use app\Models\Subcategory;
use rec\Controller;

class Records extends Controller
{
    private $userData;
    private $modelSnippets;
    private $modelCategory;
    private $modelSubcategory;

    public function __construct($userData)
    {
        parent::__construct();

        $this->userData = $userData;
        $this->modelSnippets = new Snippets();
        $this->modelCategory = new Category();
        $this->modelSubcategory = new Subcategory();
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    public function publicSnippets()
    {

    }

    public function publicCategory()
    {
        return $this->modelCategory->db->getAll("*", "visibly=1 and type='public'");
    }

    public function publicSubcategory($category = null)
    {
        if (is_numeric($category))
            return $this->modelSubcategory->db->getAll("*", "visibly=1 AND type='public' AND id_category=".$category);
        else if(is_string($category))
            return $this->modelSubcategory->db->getByAttr('link',$category, null, " visibly=1AND type='public'");
        else
            return $this->modelSubcategory->db->getAll("*", "visibly=1 and type='public'");
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    public function privateSnippets()
    {
        return $this->modelSubcategory->db->getAll("*", "visibly=1 and type='public'");
    }

    public function privateCategory()
    {
        return $this->modelCategory->db->getAll("*", "visibly=1 and type='private' and id_user=" . $this->id);
    }

    public function privateSubcategory()
    {

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


}