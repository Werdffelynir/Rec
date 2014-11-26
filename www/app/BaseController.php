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
    }

}