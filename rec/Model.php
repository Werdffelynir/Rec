<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 05.11.2014
 * Time: 1:38
 */

namespace rec;


class Model
{
    public  static $db = null;
    private static $dbhGroup = null;
    public  static $connectionCount = 0;
    private static $_models;

    public function __construct()
    {
        $this->init();
    }


    public function init(){}


    /**
     * Метод универсального доступу к моделя, метод переписываеться в создаваемых маделях
     * обезательно
     *
     * @param   string  $className  Имя класса модели
     * @return  mixed
     */
    public static function model($className = __CLASS__)
    {
        if (isset(self::$_models[$className])) {
            return self::$_models[$className];
        } else {
            $model = self::$_models[$className] = new $className();
            return $model;
        }
    }


    /**
     * Установка соединение и проверка соединения
     *
     * <pre>
     * Пример:
     * // Создание первого основного подключения
     * $connect='mysql:host=localhost;dbname=myDatabaseName';
     * $user='root';
     * $pass = '';
     * $DB1Cheack = Model::setConnection('mysql1', $connect, $user, $pass);
     * if(!$DB1Cheack) die('Not Connect to database!');
     *
     *
     * // Создание второго подключения
     * $connect='sqlite:D:\server\domains\experement.loc\litemvc\docs\DataBase\database.db';
     * $DB2Cheack = Model::setConnection('sqlite', $connect);
     * if(!$DB2Cheack) die('Not Connect to database!');
     *
     * <pre>
     *
     * @param string $connectionName Имя соединения, обезательно если подключений несколько,
     *                               обращение к подключеню по его имене через метод
     *                               getConnection(name)
     *
     * @param string $config         Строка конфигурации соединения. Например:<br><br>
     *                               # MS SQL Server и Sybase через PDO_DBLIB<br>
     *                                     "mssql:host=localhost;dbname=my_batabase"<br>
     *                                     "sybase:host=localhost;dbname=my_batabase"<br>
     *
     *                               # MySQL через PDO_MYSQL<br>
     *                                     "mysql:host=localhost;dbname=my_batabase"<br>
     *
     *                               # SQLite<br>
     *                                     "sqlite:my/database/path/database.db"<br>
     *
     *                               # Oracle<br>
     *                                     "oci:dbname=//dev.mysite.com:1521/orcl.mysite.com;charset=UTF8"<br>
     * @param string $name           Имя логин к базе данных
     * @param string $password       Пароль к базе данных
     * @return bool
     */
    public static function setConnection($connectionName='db', $config, $name='root', $password='')
    {

        $db = new RPDO($config,$name,$password);

        $db->tableName = $connectionName;

        if($db != null){
            self::$connectionCount+=1;
            self::$dbhGroup[$connectionName] = $db;
            return true;
        } else
            return false;
    }

    /**
     * Возвращает установленное соединение
     *
     * <pre>
     * Пример:
     *
     * // Способ выборки статический с основного соединения
     * $products = Model::$db->query('select * from products where buyPrice>100')->all();
     *
     * // Способ выборки первого соединения
     * $DB1 = $Model::getConnection('mysql');
     * $products = $DB1->query('select * from products where buyPrice>100')->all();
     *
     * // Способ выборки с второго соединения
     * $DB2 = $Model::getConnection('sqlite');
     * $result = $DB2->query('SELECT * FROM pages WHERE id>10')->all();
     * <pre>
     *
     * @param  string       $name     Имя соединение, при условии что оно существует
     * @return bool|object
     */
    public static function getConnection($name=null)
    {
        if(self::$dbhGroup==null) return false;

        if($name==null || count(self::$dbhGroup)==1){
            $db = array_values(self::$dbhGroup);
            self::$db = $db[0];
            return $db[0];
        }else
            if(!empty(self::$dbhGroup[$name])){
                return self::$dbhGroup[$name];
            } else
                return false;
    }
} 