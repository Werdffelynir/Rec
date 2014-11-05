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
        $this->init();
        $this->layoutPosition[$this->layoutPositionDefault] = null;
        $this->applicationName = Rec::$applicationName;

        $this->bodyAttr = 'data-url="'.Rec::$url.'"';
    }

    public function init(){}

    public function error404()
    {
        $protocol = ($_SERVER['REQUEST_URI']=='on')?'https://':'http://';
        $urlNotFound = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $linkHome = Rec::$url;
        $linkBeck = $_SERVER['HTTP_REFERER'];
        $applicationName = $this->applicationName;

        echo "<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>$applicationName 404 Not Found</title>
    <style>
        body,html{
            margin:0;padding:0;
            font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif;
        }
        .box{
            display: block;
            min-height: 600px;
            padding: 10px;
            font-size: 11px;
            color:#FFF;
            background: #0033FF;
        }
        .innerBox{
        }
        code{
            display: block;
            padding: 10px;
            font-size: 12px;
            font-weight: bold;
            font-family: Consolas, Courier New, monospace;
            color:#CBFEFF;
            background: #000066;
        }
        .header{
            font-size: 38px;
            color:#FFD000;
        }
        .description{display: block; padding: 10px; color:#828282;}
         a{
            display: block;
            font-size: 12px;
            color: #FFFFFF;
            text-decoration: none;;
        }
         a:hover{
            color: #FFD000;
        }
    </style>
</head>
<body>
<div class='box'>
    <div class='innerBox'>
        <h2 class='header' '>404. That’s an error. </h2>
        <h2>The requested URL was not found on this server.</h2>
        <div class='description'>
             <a href='$linkHome'>Go home </a>
             <a href='$linkBeck'>Go back </a>
             <a href='http://google.com'>Go go Google </a>
        </div>

        <code>
             <strong>$urlNotFound</strong>
        </code>
    </div>
</div>
</body>
</html>";
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