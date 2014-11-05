<?php

namespace rec;

class Rec
{
    /** @var bool $debug */
    public static $debug = true;

    /** @var string $applicationName */
    public static $applicationName = 'Web Application';

    /** @var null $url */
    public static $url = null;
    /** @var null $urlFull */
    public static $urlFull = null;
    /** @var null $urlPart */
    public static $urlPart = null;
    /** @var null $urlDomain */
    public static $urlDomain = null;

    /** @var null $path 'D:/server/domains/test.loc/rec/' */
    public static $path = null;
    /** @var null $pathApp 'D:/server/domains/test.loc/rec/app/' */
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
        include_once($this->recPath.'/Controller.php');
        include_once($this->recPath.'/Request.php');
        include_once($this->recPath.'/Model.php');
        include_once($this->recPath.'/RPDO.php');

        spl_autoload_register(array($this, 'autoloadAppClasses'));
    }
    private function autoloadAppClasses($className)
    {
        $className = str_replace('\\', '/', $className);

        if(substr($className, 0, 3) == 'app'){
            $fileName = $className.'.php';
            if (is_file($fileName))
                include_once($fileName);
        } else {
           /* $fileName = self::$pathApp . 'Controllers/' . $className . '.php';
            if (is_file($fileName)) {
                include_once($fileName);
                exit;
            }
            $fileName = self::$pathApp . 'Components/' . $className . '.php';
            if (is_file($fileName)) {
                include_once($fileName);
                exit;
            }
            $fileName = self::$pathApp . 'Models/' . $className . '.php';
            if (is_file($fileName)) {
                include_once($fileName);
                exit;
            }
            $fileName = self::$pathApp . 'Widgets/' . $className . '.php';
            if (is_file($fileName)) {
                include_once($fileName);
                exit;
            }*/
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

        if($this->request == 'error404' || $this->request == ' 404')
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
            \rec\Request::redirect(self::$url.'/error404',0,'404');
        }

    }


    public static function ExceptionError($errorMsg = 'File not exists', $fileName = null, $die = true)
    {
        try {
            throw new \Exception("TRUE.");
        } catch (\Exception $e) {
            echo "<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Rec: " . $errorMsg . "</title>
    <style>
        body,html{
            margin:0;padding:0;
            font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif;
        }
        .box{
            min-height: 600px;
            padding: 10px;
            font-size: 11px;
            color:#FFF;
            background: #0033FF;
        }
        .description{display: block; padding: 10px; color:#828282;}
    </style>
</head>
<body>
    <div  class='box'>

        <h2 style='font-size: 14px; color:#FF9900;'>Warning! throw Exception. </h2>

        <h2>Message: " . $errorMsg . " </h2>";

            if ($fileName != null):
                echo "<code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF; background: #000066'>"
                    . $fileName .
                    "</code>";
            endif;

            echo "<div class='description'>
            Function setup: " . $e->getFile() . "
            <br>
            Line: " . $e->getLine() . "
        </div>

        <h3>Trace As String: </h3>
        <code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF; background: #000066'>
            " . str_replace('#', '<br> ', $e->getTraceAsString()) . "<br>
        </code>

        <h3>Code: </h3>
        <code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF;  background: #000066'>
            " . $e->getCode() . "
        </code>

    </div>
</body>
</html>";
            if ($die) die();
        }

    }

}