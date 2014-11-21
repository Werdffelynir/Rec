<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Snippets;
use app\Models\Subcategory;
use rec\Controller;

class Records extends Controller
{
    public $auth = false;
    private $userId;
    private $userData;
    private $modelSnippets;
    private $modelCategory;
    private $modelSubcategory;

    public function __construct($userData)
    {
        parent::__construct();

        if($userData){
            $this->auth = true;
            $this->userId = $userData['id'];
            $this->userData = $userData;
        }

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
        return $this->modelCategory->db->getAll("*", "visibly=1 and type='public' and id_user=0");
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


    public function privateCategory()
    {
        if($this->auth){
            return $this->modelCategory->db->getAll("*", "visibly=1 and id_user=" . $this->userId);
        }else{
            return [];
        }
    }

    public function privateSubcategory()
    {
        if($this->auth){
            return '';
        }else{
            return false;
        }
    }

    public function privateSnippets()
    {
        if($this->auth){
            return $this->modelSubcategory->db->getAll("*", "visibly=1 and type='public'");
        }else{
            return false;
        }
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


}