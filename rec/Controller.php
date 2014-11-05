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
    public $auth = false;

    public $applicationName = null;
    public $layout = 'main';
    public $partial = 'main';

    public $outPosition = array();
    public $outPositionDefault = 'out';

    public function __construct()
    {
        $this->outPosition[$this->outPositionDefault] = null;
        $this->applicationName = Rec::$applicationName;
        $this->bodyAttr = 'data-url="'.Rec::$url.'"';
    }

    public function init(){}
    public function beforeAction(){}
    public function afterAction(){}

    public function error404()
    {
        $linkHome = Rec::$url;
        $linkBeck = $_SERVER['HTTP_REFERER'];

        echo "<!doctype html><html lang='en'><head> <meta charset='UTF-8'><title>404 Not Found</title>
                <style>
                    body, html { margin: 0;padding: 0;font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif; background: #F5EFEF;}
                    .box404 {display: block;  width: 800px;  margin: 10px auto;  padding: 15px;  font-size: 12px;
                        color: #EC7604;background: #282B2E;  box-shadow: inset 0 0 8px 2px rgba(54, 0, 0, .8);}
                    h1 {font-size: 36px; line-height: 36px; margin: 0; padding: 0;}
                    h2 {font-size: 14px; line-height: 16px; margin: 0; padding: 0;}
                    .text{ font-size: 12px; line-height: 16px; margin: 15px 0; padding: 0;color: #e9ebd0; }
                    a {color: #FFD000;} a:hover {color: #D6F5AD;}
                    hr{ border: none; width: 100%; height: 2px; background-color: #FFD000;}
                    code>a{color: #282B2E; text-decoration: none;}code>a:hover{color: #282B2E;text-decoration: underline}
                    code{color: #282B2E; display: block; padding: 10px; font-size: 11px; font-weight: bold;
                        font-family: Consolas, Courier New, monospace; background: #F5EFEF;}
                </style>
            </head><body><div class='box404'>
                <h1>404. That’s an error.</h1>
                <h2>The requested URL was not found on this server.</h2><hr/>
                <a href='".Rec::$urlCurrent."'>Reload page</a>
                <p class='text'>
                    <a href='".$linkHome."'>Go home </a><br>
                    <a href='".$linkBeck."'>Go back </a><br>
                    <a href='http://google.com'>Go go Google </a><br><br></p>
                <h3>Url:</h3>
                <code><a href='".Rec::$urlCurrent."'>".Rec::$urlCurrent."r</a></code><hr/>
            </div></body></html>";
        exit;
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

        $view = Component::renderPartial($this->partial, $data, true);

        if($returned) return $view;
        else echo $view;
    }


    /**
     * @param string $partial   если не пустая строка по умолчанию Views/controller/method.php
     * @param array $data
     */
    public function render($partial='//out', array $data = array())
    {
        $this->outPosition[$this->outPositionDefault] = $this->renderPartial($partial, $data, true);
        $this->renderLayout();
    }

    public function renderLayout()
    {
        $viewLayout = Rec::$pathApp.'Views/layout/'.$this->layout.'.php';

        if(is_file($viewLayout))
            require_once $viewLayout;
        else
            die('file not exists '.$viewLayout); //todo: Exception set

    }


    /**
     * Позиция вывода контента render() в layout шаблоне
     *
     * @param string $position
     */
    public function out($position='out')
    {
        echo $this->outPosition[$position];
    }


    /**
     * Добавление позиций выводов
     * Принимает массив, ключ которого название позиции вывода в out($position), а значение его контент
     *
     * Например в контолере добавить позиции column1 и column2
     * $this->addOut([
     *      'column1'=>'Some Data',
     *      'column2'=>'Some Data'
     *  ]);
     * Теперь в виде данные позиций можно вывести $this->out('column1')
     *
     * @param array $data
     */
    public function addOut(array $data)
    {
        foreach($data as $variablePosition=>$variableData){
            $this->outPosition[$variablePosition]=$variableData;
        }
    }

    public function redirect($url='', $thisApp=true)
    {
        if($thisApp)
            $url = Rec::$url.'/'.$url;

        Request::redirect($url);
    }



} 