<?php

namespace app;


use rec\Controller;

class BaseController extends Controller
{
    public $layout = 'main';

    /**
     * Возможность задать базовый функционал для приложения
     */
    public function init()
    {

        //пример добавлен позиций выводу контента
        $mainMenu = $this->renderPartial('//layout/mainMenu');
        $mainFooter = $this->renderPartial('//layout/mainFooter');

        $this->addOut([
            'mainMenu'=>$mainMenu,
            'mainFooter'=>$mainFooter,
        ]);

        //
        //$this->setOut('mainMenu',$mainMenu,true);
        //$this->setOut('mainFooter',$mainFooter,true);

        /*
        $this->addScript('jquery','js/jquery.js',1);
        $this->addScript('jquery.cookie','js/jquery.cookie.js');
        $this->addScript('base','js/base.js');
        $this->addScript('interface','js/interface.js');
        */
        $this->addScript([
            ['jquery','js/jquery.js',1],
            ['jquery.cookie','js/jquery.cookie.js'],
            ['base','js/base.js'],
        ]);

        /*
        $this->addStyle('grid','css/grid.css');
        $this->addStyle('base','css/base.css');
        $this->addStyle('interface','css/interface.css');
        */
        $this->addStyle([
            ['grid','css/grid.css'],
            ['base','css/base.css'],
            ['interface','css/interface.css'],
        ]);

    }

}