<?php

namespace rec;

class Controller {

    /** Layout variables */
    public $lang = 'en';
    public $charset = 'UTF-8';
    public $doctype = 'html';
    public $title = 'Rec';
    public $bodyAttr;
    public $head;
    public $body;

    public $applicationName = null;
    public $layout = 'main';
    public $partial = 'main';

    public $layoutPosition = array();
    public $layoutPositionDefault = 'layout';

    public function __construct()
    {
        $this->layoutPosition[$this->layoutPositionDefault] = null;
        $this->applicationName = Rec::$applicationName;
        $this->bodyAttr = 'data-url="'.Rec::$url.'"';
    }

    public function init(){}
    public function beforeAction(){}
    public function afterAction(){}

    public function error404()
    {
        $protocol = ($_SERVER['REQUEST_URI']=='on')?'https://':'http://';
        $urlNotFound = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $linkHome = Rec::$url;
        $linkBeck = $_SERVER['HTTP_REFERER'];
        $applicationName = $this->applicationName;

        echo "<!doctype html><html lang='en'><head><meta charset='UTF-8'><title>$applicationName 404 Not Found</title>
            <style>
                body,html{margin:0;padding:0;font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif;background: #F5EFEF;}
                .box404{display: block;height: 300px;width: 600px;margin: 50px auto;padding: 15px;font-size: 12px;color:#EC7604;background: #282B2E;box-shadow: inset 0 0 8px 2px rgba(54,0,0,.8);}
                h1{ font-size: 48px;} h2{ font-size: 22px;}
                a{color: #FFFFFF;} a:hover{color: #FFD000;} code>a{color:#EC7604;}
            </style>
        </head><body><div class='box404'>
            <h1>404. That’s an error. </h1>
            <h2>The requested URL was not found on this server.</h2>
            <a href='$linkHome'>Go home </a><br>
            <a href='$linkBeck'>Go back </a><br>
            <a href='http://google.com'>Go go Google </a><br><br>
            <code><a href='$urlNotFound'>$urlNotFound</a></code>
        </div></body></html>";
    }

    /**
     * @param string $partial   если не пустая строка по умолчанию Views/controller/method.php
     * @param array $data
     */
    public function render($partial='', array $data = array())
    {
        $this->layoutPosition[$this->layoutPositionDefault] = $this->renderPartial($partial, $data, true);
        $this->renderLayout();
    }

    public function renderLayout()
    {
        $viewLayout = Rec::$pathApp.'Views/layout/'.$this->layout.'.php';

        if(is_file($viewLayout)) {
            require_once($viewLayout);
        } else {
            die('file not exists '.$viewLayout); //todo: Exception set
        }
    }

    /**
     * @param string          $partial    Путь к виду шаблона после 'app/Views/'
     * @param array           $data       данные для екстракта в вид шаблон
     * @param bool            $returned   по умолчанию возвращает результат
     * @return bool|string
     */
    public function renderPartial($partial, array $data = array(), $returned=true)
    {
        if(empty($partial))
            $this->partial = strtolower(Rec::$controller.'/'.Rec::$action);
        else if(substr($partial,0,2) == '//')
            $this->partial = substr($partial,2);
        else
            $this->partial = strtolower(Rec::$controller).'/'.$partial;

        $viewPartial = Rec::$pathApp.'Views/'.$this->partial.'.php';

        ob_start();

        extract($data);

        if(is_file($viewPartial)) {
            require_once($viewPartial);
        } else {
            die('file not exists '.$viewPartial); //todo: Exception set
        }

        $view = ob_get_clean();

        if($returned)
            return $view;
        else
            echo $view;
    }


    public function layout($position)
    {
        echo $this->layoutPosition[$position];
        return null;
    }

    public function innerLayout(array $data)
    {
        foreach($data as $variablePosition=>$variableData){
            $this->layoutPosition[$variablePosition]=$variableData;
        }
        return null;
    }

    public function redirect($url='', $thisApp=true)
    {
        if($thisApp)
            $url = Rec::$url.'/'.$url;

        Request::redirect($url);
    }



} 