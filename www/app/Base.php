<?php

namespace app;

use \rec\Rec;
use \rec\Controller;

class Base extends Controller{

    public $title;

/*    public function __construct(){
        parent::__construct();
        $this->title = Rec::$applicationName;
    }*/

    public function init() {
        $this->title = Rec::$applicationName;
    }

}