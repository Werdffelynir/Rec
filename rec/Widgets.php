<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 05.11.2014
 * Time: 2:32
 */

namespace rec;


class Widgets
{
    private static $widgetsStack = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(){}
    public function run(){}

    public static function widget(array $data=null, $returned=false)
    {
        ob_start();
        ob_implicit_flush(false);

        $className = get_called_class();

        if(!isset(self::$widgetsStack[$className]))
            self::$widgetsStack[$className] = new $className();

        /* @var Widgets $widget */
        $widget =  self::$widgetsStack[$className];

        foreach ($data as $key=>$value)
            $widget->$key = $value;

        $outWidget = $widget->run();

        $contentWidget = ob_get_clean() . $outWidget;

        if($returned)
            return $contentWidget;
        else
            echo $contentWidget;
    }

    public function renderPartial($partial, array $data = array(), $returned=true)
    {
        if(substr($partial,0,2) == '//')
            $_partial = substr($partial,2);
        else
            $_partial = 'widgets/'.$partial;

        $view = Component::renderPartial($_partial, $data, true);

        if($returned) return $view;
        else echo $view;
    }

}