<?php

namespace app;


use rec\Controller;

class BaseController extends Controller
{

    public function init()
    {
        $this->baseScripts();
        $this->renderPoints();

    }

    private function baseScripts()
    {
        $this->addScript('jquery','js/jquery.js',1);
        $this->addScript('jqueryCookie','js/jquery.cookie.js');
        $this->addScript('jqueryMousewheel','js/jquery.mousewheel.js');
        $this->addScript('common','js/common.js');

        $this->addStyle('grid','css/grid.css',1);
        $this->addStyle('main','css/main.css');
    }

    public function renderPoints($point='', array $data=[])
    {
        $points = [
                'header'=>$this->renderPartial('//layout/header'),
                'menu'=>$this->renderPartial('//layout/menu'),
                'footer'=>$this->renderPartial('//layout/footer'),
            ];

        $this->addPoints(
            [
                'header'=>$points['header'],
                'menu'=>$points['menu'],
                'footer'=>$points['footer'],
            ]
        );

        switch($point)
        {
            case 'header':
                $points['header'] = $this->renderPartial('//layout/header', $data);
                break;
            case 'menu':
                $points['menu'] = $this->renderPartial('//layout/menu', $data);
                break;
            case 'footer':
                $points['footer'] = $this->renderPartial('//layout/footer', $data);
                break;
        }

        foreach ($points as $pointName => $pointData) {
            $this->intoPoint($pointName, $pointData);
        }
    }





} 