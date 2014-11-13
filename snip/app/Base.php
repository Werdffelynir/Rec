<?php

namespace app;

use \rec\Rec;
use \rec\Controller;
use \app\Models\Category;

class Base extends Controller
{
    public $title;
    public $auth = false;
    public $authData = null;

    public $categories = [];
    public $subcategory = [];

    public function init() {
        $this->title = Rec::$applicationName;

        # partial views
        $this->activeCategory();
    }

    public function login()
    {

    }

    public function logout()
    {

    }

    public function activeCategory()
    {
        $this->categories = Category::model()->db->getAll(null, 'disabled=0');
    }

    public function viewCategory()
    {
        $this->renderPartial('//layout/category_menu',
            [
                'categories'=>$this->categories
            ],
            false);
    }

}