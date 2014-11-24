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
    public static $params = [];
    public static $args = [];

    private $controllerPath = null;
    private $recPath = null;
    private $recUrls = array();


    /**
     * @param null $appPath
     * @param bool $debug
     */
    public function __construct($appPath=null, $debug=true)
    {
        self::$debug = $debug;

        self::$urlDomain = $_SERVER['HTTP_HOST'];
        self::$urlPart = substr($_SERVER['PHP_SELF'], 0, -9);
        self::$protocol = ($_SERVER['REQUEST_URI']=='on')?'https':'http';
        self::$url = self::$urlPart;
        self::$urlFull = self::$protocol.'://'.self::$urlDomain . self::$urlPart;
        self::$urlCurrent = self::$protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        self::$path = substr($_SERVER['SCRIPT_FILENAME'], 0, -9);
        self::$pathApp = ($appPath == null) ? self::$path : self::$path . $appPath.'/';

        $this->request = str_replace( trim(self::$urlPart,'/'), '',  trim($_SERVER['REQUEST_URI'],'/'));
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
        $regexp = null;
        $args = null;

        if ($paramPos = strpos($url, '{')) {
            $_url = $url;
            $url = substr($url, 0, $paramPos - 1);
            $param = substr($_url, $paramPos);
        }

        $paramValues = array(' '=>'', '/'=>'\/*', '{n}'=>'(\d*)', '{w}'=>'([a-z_]*)', '{p}'=>'(\w*)',
            '{!n}'=>'(\d+)', '{!w}'=>'([a-zA-Z_]+)', '{!p}'=>'(\w+)','{*}'=>'([\w\/-]*)');

        if(strpos($param,':') !==false)
        {
            preg_match_all('|\:(\w+)|', $param, $result);
            if(!empty($result[0])) {
                foreach ($result[0] as $_result)
                    $param = str_ireplace($_result, '', $param);
                foreach ($result[1] as $_result)
                    $args[] = $_result;
            }
        }

        $regexp = '|^('.strtr($url,$paramValues).')\/*'.strtr($param,$paramValues).'$|';

        $this->recUrls[$url] = [
            'class' => $this->checkClass($class),
            'param' => $param,
            'regexp' => $regexp,
            'args' => $args,
        ];
    }


    /**
     * autoloaded classes from source and application
     */
    private function autoloadClasses()
    {
        include_once('Component.php');
        include_once('Controller.php');
        include_once('Request.php');
        include_once('Model.php');
        include_once('RPDO.php');
        include_once('Widgets.php');

        spl_autoload_register(array($this, 'autoloadAppClasses'));
    }


    private function autoloadAppClasses($className)
    {
        $className = str_replace('\\', '/', $className);

        if(substr($className, 0, 3) == 'app'){
            $fileName = $className.'.php';
            if (is_file($fileName))
                include_once $fileName;
        }
    }


    /**
     * Base routes configs
     *
     * @return string
     */
    private function determineBaseParams()
    {

        if(empty($this->recUrls['recUrlDefault']))
            self::ExceptionError('Config option "recUrlDefault" UNDEFINED. Set value to method urlDefault("ControllerName")');
        $requestUrlDefault = explode('/',$this->recUrls['recUrlDefault']);

        if(empty($this->recUrls['recUrlNotFound']))
            $requestUrlNotFound = [$requestUrlDefault[0],'error404'];
        else
            $requestUrlNotFound = explode('/',$this->recUrls['recUrlNotFound']);

        if($this->request === '' || $this->request === '/')
            $classMethods = $requestUrlDefault;
        elseif($this->request == 'error404' || $this->request == '404')
                $classMethods = $requestUrlNotFound;
        else
            $classMethods = $requestUrlNotFound;

        self::$controller = $classMethods[0];
        self::$action = $classMethods[1];

        $this->controllerPath = $this->controllerChecked($classMethods[0]);

        $this->determineUrls();
    }


    /**
     * @return string
     */
    private function determineUrls()
    {
        $classMethods = [];
        $params = [];
        $args = [];

        foreach (array_keys($this->recUrls) as $kr)
        {
            if($kr=='recUrlDefault' || $kr=='recUrlNotFound')
                continue;

            $request = $this->request;

            if($_part = strpos($this->request,'/'))
                $request = substr($this->request,0,$_part);

            if ($request==$kr)
            {
                if(preg_match($this->recUrls[$kr]['regexp'], $this->request, $result))
                {
                    $classMethods = explode('/',$this->recUrls[$kr]['class']);
                    if(count($result) > 2)
                    {
                        array_shift($result);
                        array_shift($result);
                        $params = (isset($result)) ? $result : array();
                        $args = null;
                    }
                }

                if(!empty($this->recUrls[$kr]['args']) && !empty($params))
                    $args = array_combine($this->recUrls[$kr]['args'], $params);
            }

        }

        if(!empty($classMethods))
        {
            self::$controller = $classMethods[0];
            self::$action = $classMethods[1];
            self::$params = (!empty($params[0])) ? explode('/',$params[0]) : [];
            self::$args = $args;

            $this->controllerPath = $this->controllerChecked($classMethods[0]);
        }
    }


    /**
     * Проверка файла контролера, назначение параметров
     *
     * @param string $controllerName Имя контролера
     * @return string Путь к контролеру
     */
    private function controllerChecked($controllerName)
    {
        if(!$controllerName)
            return null;

        $controllerPath = self::$pathApp.'Controllers/'.$controllerName.'.php';

        if(!file_exists($controllerPath)) {
            if(self::$debug)
                self::ExceptionError('File not exists!',$controllerPath.'<br>  Call: '.$controllerName.' '.self::$controller.'::'.self::$action.'()<br>  Request URL: '.$this->request.' ');
            self::$controller = 'Controller';
            self::$action = 'error404';
            return 'Controller.php';
        }else
            return $controllerPath;
    }


    public function run()
    {
        #start autoload classes
        $this->autoloadClasses();

        #determine request params
        $this->determineBaseParams();

        $classControllerName = '\\app\\Controllers\\'.self::$controller;
        if(class_exists( $classControllerName ))
        {
            /** @var Controller $controllerObj */
            $controllerObj = new $classControllerName;
            $controllerObj->init();

            /** @var array $controllerActions обработка динамических запросов */
            if($controllerActions = $controllerObj->actions())
            {
                foreach ($controllerActions as $casKey=>$casVal)
                {
                    if($this->request == $casVal)
                    {
                        self::$action = $casKey;
                        continue;
                    }
                }
            }

            //var_dump(self::$controller,self::$action);
            //var_dump(method_exists($controllerObj, self::$action));


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

                    /** @var array $returnedArgs */
                    $controllerObj->beforeAction();
                    $returnedArgs = (!empty(self::$args)) ? self::$args : self::$params;
                    call_user_func_array(array((object)$controllerObj, self::$action), $returnedArgs );
                    $controllerObj->afterAction();
                    exit;
                }

            } else {
                call_user_func(array($controllerObj, 'error404'));
            }

        } else {
            if(self::$debug)
                self::ExceptionError('Class not exists','Class path: '.$this->controllerPath.'<br>Class name: '.self::$controller);
            Request::redirect(self::$url.'/error404',0,'404');
        }

    }

    public static $connectionSettings = [];

    public function setConnection(array $data)
    {
        foreach ($data as $key=>$value) {
            self::$connectionSettings[$key]=$value;
        }
    }

    public static $conf = [];

    public function setConf(array $data)
    {
        foreach ($data as $key=>$value) {
            self::$conf[$key]=$value;
        }
    }
    public static function conf($key)
    {
        if(isset(self::$conf[$key]))
            return self::$conf[$key];
        else
            return false;
    }


    /**
     * Метод возвращает параметры переданные через строку запроса.
    основное предназначение это передача неких параметров, но все же
    можно найти множество других применений для этого метода.
     *
     * <pre>
     * Например: http://site.com/edit/page/id/215/article/sun-light
     * /edit/page/                 - это контролер и екшен, они пропускаются
        $this->urlArgs()            - id возвращает первый аргумент
        $this->urlArgs(true)        - массив всех элементов "Array ( [1] => edit [2] => page [3] => id [4] => 215 [5]..."
        $this->urlArgs(1)           - id аналогично, но '1' != 1
        $this->urlArgs(3)           - возвращает третий аргумент article
        $this->urlArgs('id')        - возвращает следующий после указного вернет 215
        $this->urlArgs('article')   - sun-light
        $this->urlArgs('getArray')  - массив всех элементов "Array ( [1] => edit [2] => page [3] => id [4] => 215 [5]..."
        $this->urlArgs('getString') - строку всех элементов "edit/page/id/215/article/sun-light"
        $this->urlArgs('getController') - строку всех элементов "edit/page/id/215/article/sun-light"
        $this->urlArgs('getMethod') - строку всех элементов "edit/page/id/215/article/sun-light"
        $this->urlArgs('edit', 3)   - 215 (3 шага от 'edit')
     * </pre>
     *
     * @param bool $param
     * @param int $element
     * @return array|string
     */
    public static function urlArg($param=false, $element=1)
    {
        if(empty(self::$params)) return null;

        # отдает первый елемент
        if($param===false) {
            return self::$params[0];

        }elseif( $param===true ){
            return self::$params;

        # отдает по номеру елемент
        }elseif( is_int($param) ){
            $pNum = $param - 1;
            return (isset(self::$params[$pNum])) ? self::$params[$pNum] : null;

            //
        }elseif($param == 'getArray'){
            return self::$params;

            //
        }elseif($param == 'getString'){
            return join('/',self::$params);
            //
        }elseif($param == 'currentController'){
            return self::$controller;

            //
        }elseif($param == 'currentMethod'){
            return self::$action;

            // отдает елемент следующий после указаного
        }else{
            if(in_array($param, self::$params)){
                if($element > 0){
                    $keyElement = array_search($param, self::$params);
                    $key = $keyElement+$element;
                    return (isset(self::$params[$key]))?self::$params[$key]:null;
                } else {
                    return $param;
                }
            }else{
                return null;
            }
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

    /**
     * simple include file
     * @param string $fileName 'path/file' or '//path/file' of root app
     */
    public static function inc($fileName, $ext='php')
    {
        if(substr($fileName,0,2) == '//')
            $file = substr($fileName,2);
        else
            $file = 'Views/'.$fileName;

        $pathFile = self::$pathApp.$file.'.'.$ext;
        if(file_exists($pathFile))
            include $pathFile;
        else
            if(self::$debug)
                self::ExceptionError('File not exists', $pathFile);
    }


}