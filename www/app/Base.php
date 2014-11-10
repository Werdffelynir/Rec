<?php

namespace app;

use \rec\Rec;
use \rec\Controller;

class Base extends Controller
{
    public $title;

    public function init() {
        $this->title = Rec::$applicationName;
    }



}