<?php

namespace rec;

class Rec
{
    /** @var bool $debug */
    public static $debug = true;

    /** @var string $applicationName */
    public static $applicationName = 'Web Application';

    /** @var null|string $protocol */
    public static $protocol = null;
    /** @var null|string $url */
    public static $url = null;
    /** @var null|string $urlFull */
    public static $urlFull = null;
    /** @var null|string $urlPart */
    public static $urlPart = null;
    /** @var null|string $urlDomain */
    public static $urlDomain = null;
    /** @var null|string $urlCurrent */
    public static $urlCurrent = null;

    /** @var null|string $path 'D:/server/domains/test.loc/rec/' */
    public static $path = null;
    /** @var null|string $pathApp 'D:/server/domains/test.loc/rec/app/' */
    public static $pathApp = null;

    /** @var null  */
    public $request = null;
    public $currentRequest = null;

    public static $controller = null;
    public static $action = null;
    public static $params = null;

    private $recPath = null;
    private $recUrls = array();


    /**
     * @param null $appPath
     * @param bool $debug
     */
    public function __construct($appPath = null, $debug = true) {
        self::$debug = $debug;
        self::$urlPart = substr($_SERVER['PHP_SELF'], 0, -9);
        self::$urlDomain = $_SERVER['HTTP_HOST'];
        self::$urlFull = self::$urlDomain . self::$urlPart;
        self::$url = self::$urlPart;
        self::$protocol = ($_SERVER['REQUEST_URI']=='on')?'https':'http';
        self::$urlCurrent = self::$protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        self::$path = substr($_SERVER['SCRIPT_FILENAME'], 0, -9);
        self::$pathApp = ($appPath == null) ? self::$path : self::$path . $appPath.'/';
        $this->request = rtrim(str_replace(self::$urlPart, '', $_SERVER['REQUEST_URI']), '/');
        $this->recPath = __DIR__;
    }


    /**
     * check secondary params in class url
     * @param $class
     * @return string
     */
    public function checkClass($class)
    {
        if (strpos($class, '/') === false)
            return $class . '/index';
        else
            return $class;
    }

    /**
     * set app name
     * @param $name
     */
    public function setApplicationName($name)
    {
        self::$applicationName = $name;
    }

    /**
     * set url to default app start page
     * @param $class
     */
    public function urlDefault($class)
    {
        $this->recUrls['recUrlDefault'] = $this->checkClass($class);
    }

    /**
     * set url to page 404
     * @param $class
     */
    public function urlNotFound($class)
    {
        $this->recUrls['recUrlNotFound'] = $this->checkClass($class);
    }

    /**
     *
     * @param $class
     * @param $url
     */
    public function urlAdd($class, $url)
    {
        $param = null;
        if ($paramPos = strpos($url, '{')) {
            $_url = $url;
            $url = substr($url, 0, $paramPos - 1);
            $param = substr($_url, $paramPos);
        }

        $paramValues = array(' '=>'', '/'=>'\/*', '{n}'=>'(\d*)', '{w}'=>'([a-z_]*)', '{p}'=>'(\w*)',
            '{!n}'=>'(\d+)', '{!w}'=>'([a-z_]+)', '{!p}'=>'(\w+)');

        $this->recUrls[$url] = array(
            'class' => $this->checkClass($class),
            'param' => $param,
            'regexp' => '|^('.strtr($url,$paramValues).')\/*'.strtr($param,$paramValues).'$|',
        );
    }


    /**
     * autoloaded classes from source and application
     */
    private function autoloadClasses()
    {
        include_once($this->recPath.'/Component.php');
        include_once($this->recPath.'/Controller.php');
        include_once($this->recPath.'/Request.php');
        include_once($this->recPath.'/Model.php');
        include_once($this->recPath.'/RPDO.php');
        include_once($this->recPath.'/Widgets.php');

        spl_autoload_register(array($this, 'autoloadAppClasses'));
    }
    private function autoloadAppClasses($className)
    {
        $className = str_replace('\\', '/', $className);

        if(substr($className, 0, 3) == 'app'){
            $fileName = $className.'.php';
            if (is_file($fileName))
                include_once($fileName);
        }
    }


    /**
     * Determine params to run application
     * @return string
     */
    public function determineRunParams()
    {
        $params = array();
        $classMethods = explode('/', $this->recUrls['recUrlNotFound']);

        if($this->request === '' || $this->request === '/')
            $classMethods = explode('/',$this->recUrls['recUrlDefault']);

        if($this->request == 'error404' || $this->request == '404')
            $classMethods = explode('/',$this->recUrls['recUrlNotFound']);

        foreach (array_keys($this->recUrls) as $kr)
        {
            if($kr=='recUrlDefault' || $kr=='recUrlNotFound') continue;

            if (stripos($this->request, $kr) === 0) {
                if(preg_match($this->recUrls[$kr]['regexp'], $this->request, $result)) {
                    $classMethods = explode('/',$this->recUrls[$kr]['class']);
                    if(count($result)>2) {
                        array_shift($result);
                        array_shift($result);
                        $params = (isset($result)) ? $result : array();
                    }
                }
            }
        }

        $controllerPath = self::$pathApp.'Controllers/'.$classMethods[0].'.php';

        if(!file_exists($controllerPath)) {
            if(self::$debug)
                self::ExceptionError('File not exists!',$controllerPath.'<br>  Call: '.$classMethods[0].'::'.$classMethods[1].'()<br>  Request URL: '.$this->request.' ', true);

            $classMethods[0] = 'Controller';
            $classMethods[1] = 'error404';
            $controllerPath = 'Controller.php';
        }

        self::$controller = $classMethods[0];
        self::$action = $classMethods[1];
        self::$params = $params;

        return $controllerPath;
    }


    public function run()
    {
        #start autoload classes
        $this->autoloadClasses();
        $definedControllerPath = $this->determineRunParams();

        $classControllerName = '\\app\\Controllers\\'.self::$controller;
        if(class_exists( $classControllerName ))
        {
            /** @var Controller $controllerObj */
            $controllerObj = new $classControllerName;
            $controllerObj->init();

            if (method_exists($controllerObj, self::$action))
            {
                if(self::$action=='error404')
                    header("HTTP/1.0 404 Not Found");

                if (empty(self::$params)) {

                    $controllerObj->beforeAction();
                    call_user_func(array($controllerObj, self::$action));
                    $controllerObj->afterAction();
                    exit;

                } else {

                    $controllerObj->beforeAction();
                    call_user_func_array(array((object)$controllerObj, self::$action), self::$params );
                    $controllerObj->afterAction();
                    exit;
                }

            } else {
                \rec\Request::redirect(self::$url.'/error404',false,'404');
            }

        } else {
            if(self::$debug)
                self::ExceptionError('Class not exists','Class path: '.$definedControllerPath.'<br>Class name: '.self::$controller);
            Request::redirect(self::$url.'/error404',0,'404');
        }

    }

    public static $connectionSettings = [];

    public function connection(array $data)
    {
        foreach ($data as $key=>$value) {
            self::$connectionSettings[$key]=$value;
        }
    }


    public static function ExceptionError($errorMsg = 'File not exists', $fileName = null, $die = true)
    {
        try {
            throw new \Exception("TRUE.");
        } catch (\Exception $e) {

            echo "<!doctype html><html lang='en'><head><meta charset='UTF-8'><title>Exception</title>
                <style>
                    body, html {margin: 0;  padding: 0;  font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif;  background: #F5EFEF;}
                    .box404 { display: block; width: 800px;  margin: 10px auto;  padding: 15px;  font-size: 12px;  color: #EC7604;
                    background: #282B2E;  box-shadow: inset 0 0 8px 2px rgba(54, 0, 0, .8);}
                    h1 {font-size: 36px; line-height: 36px; margin: 0; padding: 0;  }
                    h2 {font-size: 14px; line-height: 16px; margin: 0; padding: 0;}
                    .text{ font-size: 12px; line-height: 16px; margin: 15px 0; padding: 0;color: #e9ebd0; }
                    a {color: #FFD000;} a:hover {color: #D6F5AD;}
                    hr{ border: none; width: 100%; height: 2px; background-color: #FFD000;}
                    code{font-size: 11px; font-weight: bold; font-family: Consolas, Courier New, monospace;color: #9FF565;}
                    code.block{color: #282B2E; display: block; padding: 10px;background: #F5EFEF}
                </style>
                </head><body>
                <div class='box404'>
                    <h1>Warning! throw Exception.</h1><hr/>
                    <a href='".self::$urlCurrent."'>Reload page</a>
                    <p class='text'><b>Message:</b> <code>" . $errorMsg . "</code></p>
                    <p class='text'><b>File:</b> <code>" . $fileName . "</code></p>
                    <h3>Trace As String:</h3>
                    <code class='block'>" . str_replace('#', '<br> ', $e->getTraceAsString()) ."<br></code>
                    <h3>Code:</h3>
                    <code class='block'>" . $e->getCode() . "</code>
                    <hr/>
                </div>
                </body></html>";

            if ($die) die();
        }

    }

}