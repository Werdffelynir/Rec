<?php

namespace app;


use rec\Controller;

class BaseController extends Controller
{

    public function init()
    {
        $this->addedScripts();
    }

    private function addedScripts()
    {
        $this->addScript('jquery','js/jquery.js',1);
        //$this->addScript('jqueryCookie','js/jquery.cookie.js');
        //this->addScript('jqueryMousewheel','js/jquery.mousewheel.js');
        $this->addScript('common','js/common.js');

        $this->addStyle('grid','css/grid.css',1);
        $this->addStyle('common','css/common.css');
        $this->addStyle('elements','css/elements.css');
    }






} 