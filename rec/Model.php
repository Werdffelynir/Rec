<?php

namespace rec;

class Model
{
    /** @var int  */
    public  static $connectionCount = 0;
    /** @var array  */
    private static $models = [];
    /** @var array  */
    private static $connectionStaticStack = [];

    /** @var string $table */
    public $table = null;
    /** @var string $primaryKey */
    public $primaryKey = 'id';

    public function __construct()
    {
        $this->init();
    }

    public function init(){}

    /**
     * @param $called
     * @return null|RPDO
     */
    public function __get($called)
    {
        if(!empty(self::$connectionStaticStack[$called]))
        {
            return self::$connectionStaticStack[$called]['rpdo'];
        }
        else if($connection = Rec::$connectionSettings[$called])
        {
            $dbh  = $connection['dbh'];
            $user = (isset($connection['user']))?$connection['user']:'';
            $pass = (isset($connection['pass']))?$connection['pass']:'';

            $this->calledTable();

            /** @var RPDO $rPDO */
            $rPDO = new RPDO($dbh, $user, $pass, $this->table, $this->primaryKey);

            self::$connectionStaticStack[$called] = [
                'dbh'=>$dbh,
                'user'=>$user,
                'pass'=>$pass,
                'rpdo'=>$rPDO,
            ];

            return $rPDO;

        } else {
            return null;
        }
    }

    private function calledTable()
    {
        if(!$this->table){
            $called = str_replace('\\', '\/', get_called_class() ) ;
            $table = strtolower(substr($called, strrpos($called, '/')+1));
            $this->table = $table;
        }
    }


    /**
     * Метод статического доступу к моделя, в дочернихм моделях, должет быть переопределен:
     *
     * <pre>
     *  public static function model($className = __CLASS__)
     *  {
     *      $model = parent::model($className);
     *      return $model;
     *  }
     * </pre>
     *
     * @param   string  $className  Имя класса модели
     * @return  mixed|Model
     */
    public static function model($className = __CLASS__)
    {
        if (isset(self::$models[$className])) {
            return self::$models[$className];
        } else {
            /** @var Model $model */
            $model = self::$models[$className] = new $className();
            return $model;
        }
    }


    /**
     * Установка статического соединение
     *
     * <pre>
     * //Примеры создание подключений:
     * $dbSqlite = self::setConnection('db_sqlite','sqlite:database\documentation.sqlite');
     * $dbMysql = self::setConnection('db_mysql','mysql:host=localhost;dbname=test','user','password');
     * <pre>
     *
     * @param string $connectionName Имя соединения, обращение к подключеню осуществляется по его имене через метод
     *                               getConnection(name)
     *
     * @param string $dbh Строка конфигурации соединения. Например:<br><br>
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
     *
     * @param string $user Имя логин к базе данных
     * @param string $pass Пароль к базе данных
     * @return bool|RPDO
     */
    public static function setConnection($connectionName, $dbh, $user='', $pass='')
    {
            /** @var RPDO $rpdo */
            $rpdo = new RPDO($dbh, $user, $pass);

            $connectionSettings = [
                'dbh'=>$dbh,
                'user'=>$user,
                'pass'=>$pass,
                'rpdo'=>$rpdo];

            self::$connectionStaticStack[$connectionName] = $connectionSettings;

            return $rpdo;
    }


    /**
     * Возвращает установленное соединение методом setConnection или с конфигурации
     *
     * <pre>
     * Пример:
     *
     * // Способ выборки статический с основного соединения
     * $db = getConnection('db_sqlite');
     * $products = $db->query('select * from products where buyPrice>100')->all();
     * <pre>
     *
     * @param  string $connectionName Имя соединение, установленное статическим методом setConnection
     * @return bool|RPDO
     */
    public static function getConnection($connectionName)
    {
        if(!empty(self::$connectionStaticStack[$connectionName]))
        {
            /** @var RPDO $rpdo */
            $rpdo = self::$connectionStaticStack[$connectionName]['rpdo'];
            return $rpdo;

        } else
            return false;
    }
} 