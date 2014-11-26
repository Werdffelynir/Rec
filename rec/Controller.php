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
    public $outPositionDefault = 'default';

    public function __construct()
    {
        $this->outPosition[$this->outPositionDefault] = null;
        $this->applicationName = Rec::$applicationName;
        $this->bodyAttr = 'data-url="'.Rec::$url.'"';
    }

    public function init(){}
    public function beforeAction(){}
    public function afterAction(){}

    public function actions(){
        return [];
    }

    public function error404()
    {
        $urlCurrent = Rec::$urlCurrent;
        $linkHome = Rec::$url;
        $linkBeck = (isset($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:'#';

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
                <a href='".$urlCurrent."'>Reload page</a>
                <p class='text'>
                    <a href='".$linkHome."'>Go home </a><br>
                    <a href='".$linkBeck."'>Go back </a><br>
                    <a href='http://google.com'>Go go Google </a><br><br></p>
                <h3>Url:</h3>
                <code><a href='".$urlCurrent."'>".$urlCurrent."</a></code><hr/>
            </div></body></html>";
        exit;
    }



    /**
     * @param string          $partial    Путь к виду шаблона после 'app/Views/'
     * @param array           $data       данные для екстракта в вид шаблон
     * @param bool            $returned   по умолчанию возвращает результат
     * @return bool|string
     */
    public function renderPartial($partial='', array $data = array(), $returned=true)
    {
        if(!$partial)
            $this->partial = strtolower(Rec::$controller.'/'.Rec::$action);
        else if(substr($partial,0,2) == '//')
            $this->partial = substr($partial,2);
        else
            $this->partial = strtolower(Rec::$controller).'/'.$partial;

        $view = Component::renderPartial($this->partial, $data, true);

        if($returned) return $view;
        else echo $view;
    }

    /*public function renderOut($position, array $data = array())
    {
        if(is_array($position))
        {
            foreach ($position as $variablePosition=>$variableData) {
                $this->outPosition[$variablePosition]=$variableData;
            }

        }else if(is_string($position)){

        }
    }*/

    /**
     * @param string $partial   если не пустая строка по умолчанию Views/controller/method.php
     * @param array $data
     */
    public function render($partial='//out', array $data = array())
    {
        if($partial!==false)
            $this->outPosition[$this->outPositionDefault] = $this->renderPartial($partial, $data, true);

        $this->renderLayout();
    }

    public function renderLayout()
    {
        $viewLayout = Rec::$pathApp.'Views/layout/'.$this->layout.'.php';

        if(is_file($viewLayout))
            require_once $viewLayout;
        else
            if(Rec::$debug)
                Rec::ExceptionError('Layout file not find', 'file not exists '.$viewLayout);

    }


    /**
     * Позиция вывода контента render() в layout шаблоне
     *
     * @param string $position
     * @param bool $skipExists
     */
    public function out($position='default', $skipExists=false)
    {
        if(array_key_exists($position, $this->outPosition)) {
            echo $this->outPosition[$position];
        } else {
            if(!$skipExists) {
                if(Rec::$debug)
                    Rec::ExceptionError('Out position undefined', $position);
            } else {
                $this->addOut([$position=>'']);
                $this->out($position,$skipExists);
            }
        }
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

    /**
     * Устанавлевает значение для позиций
     *
     * @param string $position          название позиции
     * @param null|string $variableData строка данных
     * @param bool $skipExists          создает позицию если не существует, и устанавлевает ей значение, false поумолчанию
     */
    public function setOut($position='default', $variableData = null, $skipExists=false)
    {
        if(array_key_exists($position, $this->outPosition)){
            $this->outPosition[$position]=$variableData;
        }else{
            if(!$skipExists) {
                if(Rec::$debug)
                    Rec::ExceptionError('Out position undefined', $position);
            } else {
                $this->addOut([$position=>'']);
                $this->setOut($position,$variableData,$skipExists);
            }
        }
    }

    /**
     * @param string $url
     * @param bool $thisApp
     * @param int $sleep
     */
    public function redirect($url='', $thisApp=true, $sleep=0)
    {
        if($thisApp)
            $url = Rec::$urlFull.$url;

        sleep($sleep);

        Request::redirect($url);
    }

    public function urlArg($param=false, $element=1)
    {
        return Rec::urlArg($param, $element);
    }



    /**
     *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
     *                          A L I A S E S
     *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
     */


    #
    #   Request
    #

    /**
     * Checked if ajax request
     * @return bool
     */
    public function isAjax()
    {
        return Request::isAjax();
    }

    /**
     * Еквивалентно глобальному массиву $_POST[$name]
     * @param null $name
     * @param bool $clear
     * @return null|string
     */
    public function post($name=null, $clear=false)
    {
        return Request::post($name, $clear);
    }

    /**
     * Еквивалентно глобальному массиву $_GET[$name]
     *
     * @param null $name
     * @param bool $clear
     * @return null|string
     */
    public function get($name=null, $clear=false)
    {
        return Request::get($name, $clear);
    }

    /**
     * Еквивалентно глобальному массиву $_POST[$name] || $_GET[$name]
     * @param $name
     * @param bool $clear
     * @return null|string
     */
    public function value($name, $clear=false)
    {
        return Request::value($name, $clear);
    }

    /**
     * Устанавлевает или извлекает значние session
     *
     * @param string $name      Если указано только одно значение session извлекаються по имени
     * @param null $setValue    Задает значение для $name, если указано null
     * @param bool $clear
     * @return bool|null|string
     */
    public function session($name, $setValue=null, $clear=false)
    {
        return Request::session($name, $setValue, $clear);
    }


    /**
     * Устанавлевает или извлекает значние cookie
     *
     * @param string $key           Если указано только одно значение cookie извлекаються по имени
     * @param bool|string $value    Задает значение для $key, если указано null удаляет это значение, при этом сама кука остается существовать
     * @param null $expire
     * @param null $domain
     * @param null $path
     * @return bool|null|string
     */
    public function cookie($key, $value=false, $expire = null, $domain = null, $path = null)
    {
        return Request::cookie($key, $value, $expire, $domain, $path);
    }

    /**
     * Удаляет cookie
     *
     * @param $key
     * @param null $domain
     * @param null $path
     * @return bool
     */
    public function deleteCookie($key, $domain = null, $path = null)
    {
        return Request::deleteCookie($key, $domain, $path);
    }

    #
    #   Components
    #
/**/
    public function hookRegister($event, $callback = null, array $params = array())
    {
        return Component::hookRegister($event,$callback,$params);
    }

    public function hookTrigger($event, array $params = array())
    {
        Component::hookTrigger($event,$params);
    }

    public function filterRegister($filterName, $callable, $acceptedArgs = 1)
    {
        Component::filterRegister($$filterName, $callable, $acceptedArgs);
    }

    public function filterTrigger($filterName, $args)
    {
        Component::filterTrigger($filterName, $args);
    }

    public function flash($key = null, $value = null, $keep = true)
    {
        return Component::flash($key, $value, $keep);
    }

    public function setChunk( $chunkName, $chunkView='', array $dataChunk=null, $returned=false )
    {
        return Component::setChunk($chunkName, $chunkView, $dataChunk, $returned);
    }

    public function chunk( $chunkName, $echo=true )
    {
        return Component::chunk($chunkName, $echo);
    }
}

