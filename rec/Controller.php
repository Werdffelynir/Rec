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
    private $partial = 'main';

    public $viewPosition = array();
    private $viewPositionDefault = 'default';


    public function __construct()
    {
        $this->viewPosition[$this->viewPositionDefault] = null;
        $this->applicationName = Rec::$applicationName;
        $this->bodyAttr = 'data-url="'.Rec::$url.'"';
    }

    public function init(){}
    public function beforeAction(){}
    public function afterAction(){}

    public function actions()
    {
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
    public function renderPartial($partial, array $data=[],$returned=true)
    {
        if(!$partial)
            $partial = strtolower(Rec::$controller.'/'.Rec::$action);
        else if(substr($partial,0,2) == '//')
            $partial = substr($partial,2);
        else
            $partial = strtolower(Rec::$controller).'/'.$partial;

        $view = View::renderPartial($partial,$data,true);

        if($returned)
            return $view;
        else
            echo $view;
    }


    /**
     * @param string $partial   если не пустая строка по умолчанию Views/controller/method.php
     * @param array $data
     */
    public function render($partial='//out', array $data=[])
    {
        if($partial !== false)
            $this->viewPosition[$this->viewPositionDefault] = $this->renderPartial($partial, $data, true);

        $this->renderLayout();
    }


    public function renderLayout()
    {
        $generateData = View::generate();
        $this->body = $generateData['body'];
        $this->head = $generateData['head'];

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

    public function out($position='default', $skipExists=false)
    {
        $this->view($position,$skipExists);
    }*/

    public function point()
    {
        $this->pointOut('default');
    }

    public function pointOut($position, $skipExists=false)
    {
        if(array_key_exists($position, $this->viewPosition)) {
            echo $this->viewPosition[$position];
        } else {

            if(!$skipExists) {
                if(Rec::$debug)
                    Rec::ExceptionError('Out position undefined', $position);
            } else {
                $this->addPoints([$position=>'']);
                $this->point($position,$skipExists);
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

    public function addOut(array $data)
    {
        $this->addView($data);
    } */

    public function addPoints(array $data)
    {
        foreach($data as $variablePosition=>$variableData)
        {
            $this->viewPosition[$variablePosition]=$variableData;
        }
    }

    /**
     * Устанавлевает значение для позиций
     *
     * @param string $position          название позиции
     * @param null|string $variableData строка данных
     * @param bool $skipExists          создает позицию если не существует, и устанавлевает ей значение, false поумолчанию

    public function setOut($position='default', $variableData = null, $skipExists=false)
    {
        $this->setView($position,$variableData,$skipExists);
    } */

    public function intoPoint($position, $variableData=null, $skipExists=false)
    {
        if(array_key_exists($position, $this->viewPosition))
        {
            $this->viewPosition[$position]=$variableData;

        }else{
            if(!$skipExists) {
                if(Rec::$debug)
                    Rec::ExceptionError('Out position undefined', $position);
            } else {
                $this->addPoints([$position=>'']);
                $this->intoPoint($position,$variableData,$skipExists);
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

    public function hookRegister($event, $callback = null, array $params = array())
    {
        return Event::hookRegister($event,$callback,$params);
    }

    public function hookTrigger($event, array $params = array())
    {
        Event::hookTrigger($event,$params);
    }

    public function filterRegister($filterName, $callable, $acceptedArgs = 1)
    {
        Event::filterRegister($$filterName, $callable, $acceptedArgs);
    }

    public function filterTrigger($filterName, $args)
    {
        Event::filterTrigger($filterName, $args);
    }

    public function flash($key = null, $value = null, $keep = true)
    {
        return Event::flash($key, $value, $keep);
    }


    #
    #   View
    #

    public function setChunk( $chunkName, $chunkView='', array $dataChunk=null, $returned=false )
    {
        return View::setChunk($chunkName, $chunkView, $dataChunk, $returned);
    }
    public function chunk( $chunkName, $echo=true )
    {
        return View::chunk($chunkName, $echo);
    }
    public function addScript($data, $src=null, $depth=null, $position=null)
    {
        View::addScript($data, $src, $depth, $position);
    }
    public function delScript($name)
    {
        View::delScript($name);
    }
    public function addBeforeScript($searchName,$addName,$src)
    {
        View::addBeforeScript($searchName,$addName,$src);
    }
    public function addAfterScript($searchName,$addName,$src)
    {
        View::addAfterScript($searchName,$addName,$src);
    }
    public function addStyle($data, $src=null, $depth=null)
    {
        View::addStyle($data, $src, $depth);
    }
    public function delStyle($name)
    {
        View::delStyle($name);
    }
    public function addBeforeStyle($searchName,$addName,$src)
    {
        View::addBeforeStyle($searchName,$addName,$src);
    }
    public function addAfterStyle($searchName,$addName,$src)
    {
        View::addAfterStyle($searchName,$addName,$src);
    }
    public function addJavascript($dataString)
    {
        View::addJavascript($dataString);
    }

}

