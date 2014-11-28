<?php

namespace rec;


class View
{

    /**
     * Свойство передачи в вид или шаблон части вида
     * Работет совместно с методом setChunk()
     */
    private static $chunk = [];

    public static $stackScriptsData;
    public static $stackJavaScriptsHtml;
    public static $stackStylesData;
    private static $stackDefaultDepth = 10;
    private static $stackDefaultPosition = 'bottom';

    private static $instance = null;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function instance()
    {
        if(self::$instance===null){
            self::$instance = new self();
            return self::$instance;
        }else
            return self::$instance;
    }


    /**
     * @param string          $partial    Путь к виду шаблона после 'app/Views/'
     * @param array           $data       данные для екстракта в вид шаблон
     * @param bool            $returned   по умолчанию возвращает результат
     * @return bool|string
     */
    public static function renderPartial($partial, array $data = array(), $returned=true)
    {
        $viewPartial = Rec::$pathApp.'Views/'.$partial.'.php';


        ob_start();
        extract($data);
        if(is_file($viewPartial))
        {
            require_once $viewPartial;
        }
        else
            Rec::ExceptionError('File not exists', $viewPartial);

        $view = ob_get_clean();

        if($returned)
            return $view;
        else
            echo $view;
    }




    /**
     *
     * Обработка в указаном виде, переданых данных, результат будет передан в основной вид или тему по указаному $chunkName,
     * также есть возможность вернуть результат в переменную указав четвертый параметр в true.
     *
     *<pre>
     * Пример:
     * $this->setChunk("topSidebar", "blog/topSidebar", array( "var" => "value" ));
     *
     * в вид blog/topSidebar.php передается  переменная $var с значением  "value".
     *
     * В необходимом месте основного вида или темы нужно обявить чанк
     * напрямую:
     * echo $this->chunk["topSidebar"];
     * или методом:
     * $this->chunk("topSidebar");
     *</pre>
     *
     * @param string    $chunkName  зарегестрированое имя
     * @param string    $chunkView  путь у виду чанка, установки путей к виду имеют следующие особености:
     *                              	"partial/myview" сгенерирует "app/Views/partial/myview.php"
     *                              	"//partial/myview" сгенерирует "app/partial/myview.php"
     * @param array     $dataChunk  передача данных в вид чанка
     * @param bool      $returned   по умочнию FALSE производится подключения в шаблон, если этот параметр TRUE возвращает контент
     * @return string
     */
    public static function setChunk( $chunkName, $chunkView='', array $dataChunk=null, $returned=false )
    {
        // Если $chunkView передан как пустая строка, создается заглушка
        if(empty($chunkView))
            return self::$chunk[$chunkName] = '';
        else if($chunkView===false || $chunkView === null)
            $_chunkView = strtolower(Rec::$controller.'/'.Rec::$action);
        else if(substr($chunkView,0,2) == '//')
            $_chunkView = substr($chunkView,2);
        else
            $_chunkView = strtolower(Rec::$controller).'/'.$chunkView;

        $resultChunk = self::renderPartial($_chunkView, $dataChunk, true);

        if(!$returned)
            self::$chunk[$chunkName] = $resultChunk;
        else
            return $resultChunk;

    }

    /**
     * Вызов зарегистрированного чанка. Первый аргумент имя зарегестрированого чанка
     * второй тип возврата метода по умолчанию ECHO, если FALSE данные будет возвращены
     *
     * <pre>
     * Пример:
     *  $this->chunk("myChunk");
     * </pre>
     *
     * @param  string    $chunkName
     * @param  bool      $e
     * @return bool
     */
    public static function chunk( $chunkName, $e=true )
    {
        if(isset(self::$chunk[$chunkName])){
            if($e)
                echo self::$chunk[$chunkName];
            else
                return self::$chunk[$chunkName];
        }else{
            if(Rec::$debug){
                Rec::ExceptionError('ERROR Undefined chunk',$chunkName);
            }else
                return null;
        }
    }


    /**
     * @return array with keys top, bottom
     */
    public static function generate()
    {
        $htmlHead = '';
        $htmlBody = '';

        if(!empty(self::$stackStylesData)){
            usort(self::$stackStylesData, function($arg1,$arg2){
                return ($arg1['depth']>$arg2['depth'])?1:-1;
            });
            foreach(self::$stackStylesData as $style){
                $htmlHead .= "<link href=\"{$style['src']}\" rel=\"stylesheet\" type=\"text/css\" />\n";
            }
        }
        if(!empty(self::$stackScriptsData)){
            usort(self::$stackScriptsData, function($arg1,$arg2){
                return ($arg1['depth']>$arg2['depth'])?1:-1;
            });
            foreach(self::$stackScriptsData as $script){
                if($script['position']=='bottom'||$script['position']==true||$script['position']=='b')
                    $htmlBody .= "<script type=\"text/javascript\" src=\"{$script['src']}\"></script>\n";
                else
                    $htmlHead .= "<script type=\"text/javascript\" src=\"{$script['src']}\"></script>\n";
            }
        }
        if(!empty(self::$stackJavaScriptsHtml)){
            $htmlBody .= "\n<script type=\"text/javascript\">\n".self::$stackJavaScriptsHtml."\n</script>\n";
        }

        return [
            'head'=>$htmlHead,
            'body'=>$htmlBody,
        ];
    }


    public static function addJavascript($dataString)
    {
        self::$stackJavaScriptsHtml .= $dataString;
    }

    /**
     * @param $data
     * @param $callable
     */
    private static function addTo($data, $callable)
    {
        foreach($data as $d) {
            $arg1 = (isset($d[0]))?$d[0]:null;
            $arg2 = (isset($d[1]))?$d[1]:null;
            $arg3 = (isset($d[2]))?$d[2]:self::$stackDefaultDepth;
            $arg4 = (isset($d[3]))?$d[3]:self::$stackDefaultPosition;

            if($arg1 && $arg2)
                self::$callable($arg1,$arg2,$arg3,$arg4);
            else
                continue;
        }
    }

    /**
     * @param $data
     * @param null $src
     * @param null $depth
     * @param null $position
     */
    public static function addScript($data, $src=null, $depth=null, $position=null)
    {
        if(is_string($data))
        {
            if($depth==null) $depth = self::$stackDefaultDepth;
            if($position==null) $position = self::$stackDefaultPosition;
            self::$stackScriptsData[$data]['name'] = $data;
            self::$stackScriptsData[$data]['src'] = '/public/'.trim($src,'/');
            self::$stackScriptsData[$data]['depth'] = $depth;
            self::$stackScriptsData[$data]['position'] = $position;
            self::$stackDefaultDepth ++;
        }
        else if(is_array($data))
        {
            self::addTo($data, 'addScript');
        }
    }

    /**
     * @param $name
     */
    public static function delScript($name)
    {
        if(isset(self::$stackScriptsData[$name])){
            unset(self::$stackScriptsData[$name]);
        }
    }

    /**
     * @param $searchName
     * @param $addName
     * @param $src
     * @throws \ErrorException
     */
    public static function addBeforeScript($searchName,$addName,$src)
    {
        if(isset(self::$stackScriptsData[$searchName])){
            self::addScript($addName,$src,self::$stackScriptsData[$searchName]['depth']-1);
        }else
            throw new \ErrorException();
    }

    /**
     * @param $searchName
     * @param $addName
     * @param $src
     * @throws \ErrorException
     */
    public static function addAfterScript($searchName,$addName,$src)
    {
        if(isset(self::$stackScriptsData[$searchName])){
            self::addScript($addName,$src,self::$stackScriptsData[$searchName]['depth']+1);
        }else
            throw new \ErrorException();
    }

    /**
     * @param $data
     * @param null $src
     * @param null $depth
     */
    public static function addStyle($data, $src=null, $depth=null)
    {
        if(is_string($data))
        {
            if($depth==null) $depth = self::$stackDefaultDepth;
            self::$stackStylesData[$data]['name'] = $data;
            self::$stackStylesData[$data]['src'] = '/public/'.trim($src,'/');
            self::$stackStylesData[$data]['depth'] = $depth;
        }
        else if(is_array($data))
        {
            self::addTo($data, 'addStyle');
        }
    }

    /**
     * @param $name
     */
    public static function delStyle($name)
    {
        if(isset(self::$stackStylesData[$name])){
            unset(self::$stackStylesData[$name]);
        }
    }

    /**
     * @param $searchName
     * @param $addName
     * @param $src
     * @throws \ErrorException
     */
    public static function addBeforeStyle($searchName,$addName,$src)
    {
        if(isset(self::$stackStylesData[$searchName])){
            self::addStyle($addName,$src,self::$stackStylesData[$searchName]['depth']-1);
        }else
            throw new \ErrorException();
    }

    /**
     * @param $searchName
     * @param $addName
     * @param $src
     * @throws \ErrorException
     */
    public static function addAfterStyle($searchName,$addName,$src)
    {
        if(isset(self::$stackStylesData[$searchName])){
            self::addStyle($addName,$src,self::$stackStylesData[$searchName]['depth']+1);
        }else
            throw new \ErrorException();
    }

} 