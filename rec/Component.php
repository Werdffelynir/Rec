<?php

namespace rec;


class Component
{

    public static $_hookBind = array();
    public static $_filterBind = array();
    public static $_flashStorage = array();

    public function __construct() {}

    public function init() {}


    /**
     * Регистрация или вызов обработчиков событий, хука, в областе видемости. Первый аргумент
     * имя хука, второй анонимная функция или название метода в контролере, трений
     * задает аргументы для назначиного обработчика в втором аргументе. Если указан только первый
     * аргумент возвращает екземпляр этого хука, если имя не зарегестрировано возвращает NULL.
     *
     * <pre>
     * Пример:
     *  $this->hookRegister('hook-00', function(){ echo '$this->hookRegister'; });
     *  //showEvent - функция или класс с мтодом array('className','method')
     *  $this->hookRegister('hook-01', 'showEvent');
     *  $this->hookRegister('hook-02', 'showName', array('param1'));
     *  $this->hookRegister('hook-03', 'showNameTwo', array('param1','param2'));
     * </pre>
     *
     * @param string 	$event 		Название евента
     * @param null 		$callback 	Обработчик события обратного вызова
     * @param array 	$params 	Передаваемые параметры
     * @return boolean
     */
    public static function hookRegister($event, $callback = null, array $params = array())
    {
        if (func_num_args() > 2)
            self::$_hookBind[$event] = array($callback, $params);
        else if (func_num_args() > 1)
            self::$_hookBind[$event] = array($callback);
        else
            return self::$_hookBind;

        return true;
    }


    /**
     * Тригер для зарегестрированого евента. Первый аргумент зарегестрированый ранее хук
     * методом hookRegister(), второй параметры для зарегестрированого обработчика.
     * Возвращает исключение в случае если хук не зарегестрирован
     *
     * <pre>
     * Пример:
     *  $this->hookTrigger('hook-01');
     *  $this->hookTrigger('hook-02', array('param1'));
     *  $this->hookTrigger('hook-03', array('param1','param2'));
     * </pre>
     *
     * @param string $event
     * @param array $params
     */
    public static function hookTrigger($event, array $params = array())
    {
        if ($handlers = self::$_hookBind[$event]) {

            if(empty($params))
                $params = (!empty($handlers[1])) ? $handlers[1] : false;

            if($params){
                if (method_exists(__CLASS__, $handlers[0]))
                    call_user_func_array(array(__CLASS__, $handlers[0]), $params);
                else if (is_callable($handlers[0]))
                    call_user_func_array($handlers[0], $params);
                else
                    if (Rec::$debug)
                        Rec::ExceptionError('<b>Error hookTrigger</b> Invalid callable or invalid num arguments');
            } else {
                if (method_exists(__CLASS__, $handlers[0]))
                    call_user_func(array(__CLASS__, $handlers[0]));
                else if (is_callable($handlers[0]))
                    call_user_func($handlers[0]);
                else
                    if (Rec::$debug)
                        Rec::ExceptionError('<b>Error hookTrigger</b> Invalid callable or invalid num arguments');
            }
        }
    }


    /**
     * Регистрация фильтра.
     *
     * @param string $filterName Имя фильтра
     * @param callable $callable солбек, функция или класс-метод
     * @param int $acceptedArgs количество принимаюих аргументов
     */
    public static function filterRegister($filterName, $callable, $acceptedArgs = 1)
    {
        if (is_callable($callable)) {
            self::$_filterBind[$filterName]['callable'] = $callable;
            self::$_filterBind[$filterName]['args'] = $acceptedArgs;
        }
    }

    /**
     * Тригер для зарегестрированого фильтра.
     *
     * @param string $filterName Имя фильтра
     * @param string|array $args входящие аргументы
     */
    public static function filterTrigger($filterName, $args)
    {
        if (isset(self::$_filterBind[$filterName]))
        {
            if (is_string($args))
                call_user_func(array(__CLASS__, self::$_filterBind[$filterName]['callable']), $args);
            else if (is_array($args) AND self::$_filterBind[$filterName]['args'] == sizeof($args))
                call_user_func_array(array(__CLASS__, self::$_filterBind[$filterName]['callable']), $args);
        }
        else
        {
            if (Rec::$debug)
                Rec::ExceptionError('<b>Error filterTrigger</b> Invalid callable or invalid num arguments');
        }
    }


    /**
     * Выводит или регистрирует флеш сообщения для даной страницы или следующей переадрисации.
     * Указать два аргумента для регистрации сообщения, один для вывода. Если указать претий аргумент
     * в FALSE, сообщение будет удалено поле первого вывода.
     *
     * <pre>
     * Регистрация сообщения:
     * App::flash('edit','Запись в базе данных успешно обновлена!');
     * Вывод после переадрисации:
     * App::flash('edit');
     * </pre>
     *
     * @param string $key Ключ флеш сообщения
     * @param mixed $value Значение
     * @param bool $keep Продлить существования сообщения до следущего реквкста; по умолчанию TRUE
     *
     * @return mixed
     */
    public static function flash($key = null, $value = null, $keep = true)
    {
        if (!isset($_SESSION)) session_start();
        $flash = '_flash';

        if (func_num_args() > 1)
        {
            $old = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;

            if (isset($value)) {
                $_SESSION[$flash][$key] = $value;

                if ($keep)
                    self::$_flashStorage[$key] = $value;
                else
                    unset(self::$_flashStorage[$key]);

            } else {
                unset(self::$_flashStorage[$key]);
                unset($_SESSION[$flash][$key]);
            }

            return $old;

        }
        else if (func_num_args())
        {
            $flashMessage = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
            unset(self::$_flashStorage[$key]);
            unset($_SESSION[$flash][$key]);
            return $flashMessage;
        }
        else
            return self::$_flashStorage;

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
            require_once $viewPartial;
        else
            Rec::ExceptionError('File not exists', $viewPartial);

        $view = ob_get_clean();

        if($returned)
            return $view;
        else
            echo $view;
    }

}