<?php


namespace rec;


class RPDO
{
    /** @var \PDO $DB */
    private static $DBH = null;
    /** @var \PDOStatement $STH */
    private static $STH = null;

    private $sql = null;
    private $config = null;
    private $user = null;
    private $password = null;

    public $table;
    public $primaryKey;

    public function __construct($config, $user, $password, $table, $primaryKey)
    {
        try {
            $this->init($config,$user,$password,$table,$primaryKey);
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }


    /**
     * Инициализация PDO
     *
     * @param string    $config
     * @param string    $user
     * @param string    $password
     * @param string    $table
     * @param string    $primaryKey
     */
    private function init($config,$user,$password,$table,$primaryKey)
    {
        self::$DBH = new \PDO($config,$user,$password);
        $this->config = $config;
        $this->user = $user;
        $this->password = $password;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }


    public function __get($name)
    {
        $name = strtolower($name);

        if($name=='dbh' || $name=='connect' || $name=='rpdo')
        {
            return self::$DBH;
        }
        else if($name=='sql')
        {
            return $this->sql;
        }

    }


    /**
     * Слипой не безопасный метод выполнения запросов
     *
     * @param string    $sql    SQL запрос
     * @return mixed            Колчество затронутых строк
     */
    public function exec($sql)
    {
        $this->checkConnect();
        $this->sql = $sql;
        $count = null;

        try {
            $count = self::$DBH->exec($sql);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        if(!$count) $this->checkConnect();
        return $count;
    }


    /**
     * Базовый метод запросов к базе данных.
     * Использует стандартный метод execute() через обертку, принимает sql запрос,
     * или если указан второй параметр происходит выполнение через метод prepare()
     * возвращает екземпляр обекта
     *
     * Запросы осуществляються:
     * <pre>
     * ->query( "INSERT INTO blog (title, article, date) values (:title, :article, :date)",
     *      array(
     *          'title' => $title,
     *          'article' => $article,
     *          'date' => time()
     *          )
     *      )
     *
     * ->query( "INSERT INTO blog (title, article, date) values (?, ?, ?)",
     *      array(
     *          $title,
     *          $article,
     *          $date
     *          )
     *      )
     * ->query( "SELECT title, article, date FROM blog WHERE id=:id",
     *      array('id'=> '215')
     *      )
     * ->row()
     * ->all()
     * <pre>
     * @param string $sql   Принимает открытый SQL запрос или безопасный
     * @param array  $data  Значения для безопасного запроса
     * @return $this        Возвращает екземпляр обекта
     */
    public function query($sql, array $data = null)
    {
        $this->checkConnect();
        $this->sql = $sql;

        if (is_null($data)) {
            self::$STH = self::$DBH->prepare($sql);
            if(!self::$STH)
                $this->checkConnect(true);
            self::$STH->execute();
        } else {
            self::$STH = self::$DBH->prepare($sql);
            if(!self::$STH)
                $this->checkConnect(true);
            self::$STH->execute($data);
        }
        return $this;
    }


    /**
     * Извлечь строку с запроса
     *
     * Выберает типы: assoc, class, obj
     *
     * @param  string   $type   использует FETCH_ASSOC, FETCH_CLASS, и FETCH_OBJ.
     * @return mixed
     */
    public function row($type = 'assoc')
    {
        if ($type == "assoc") self::$STH->setFetchMode(\PDO::FETCH_ASSOC);
        if ($type == "obj") self::$STH->setFetchMode(\PDO::FETCH_OBJ);
        if ($type == "class") self::$STH->setFetchMode(\PDO::FETCH_CLASS);
        return self::$STH->fetch();
    }

    /**
     * Извлечь строку с запроса
     *
     * Выберает типы: assoc, class, obj
     *
     * @param string $type
     * @return mixed
     */
    public function one($type = 'assoc')
    {
        return $this->row($type);
    }


    /**
     * Извлечь несколько строк
     *
     * Выберает типы: assoc, class, obj
     *
     * @param  $type
     * @return array
     */
    public function all($type = 'assoc')
    {
        if ($type == "assoc") self::$STH->setFetchMode(\PDO::FETCH_ASSOC);
        if ($type == "obj") self::$STH->setFetchMode(\PDO::FETCH_OBJ);
        if ($type == "class") self::$STH->setFetchMode(\PDO::FETCH_CLASS);

        $result = array();
        while ($rows = self::$STH->fetch()) {
            $result[] = $rows;
        };
        return $result;
    }


    /**
     * Обертка INSERT
     * <pre>
     * ->insert(
     *      array("title","link","content","datetime","author"),
     *      array(
     *          'title'     =>'SOME TITLE',
     *          'link'      =>'SOME LINK',
     *          'content'   =>'SOME CONTENT',
     *          'datetime'  =>'SOME DATETIME',
     *          'author'    =>'SOME AUTHOR',
     *      ));
     * С генерирует SQL запрос:
     * "INSERT INTO table (title,link,content,datetime,author)
     *      VALUES (:title,:link,:content,:datetime,:author)"
     * и подставит необходимые значения.
     * </pre>
     *
     * @param array $dataColumn - Масив названий колонок для обновлеия
     * @param array $dataValue - Массив значений для установленных $dataColumn
     * @return bool
     */
    public function insert(array $dataColumn, array $dataValue)
    {
        $table = $this->table;

        if (count($dataColumn) == count($dataValue)) {
            $constructSql = "INSERT INTO " . $table . " (";
            $constructSql .= implode(", ", $dataColumn);
            $constructSql .= ") VALUES (";
            $constructSql .= ':' . implode(", :", $dataColumn);
            $constructSql .= ")";

            $this->sql = $constructSql;
            self::$STH = self::$DBH->prepare($constructSql);
            $resultInsert = self::$STH->execute($dataValue);

            if($resultInsert)
                return self::$DBH->lastInsertId();
            else
                return $resultInsert;
        } else {
            die("Количество полей не соответствует количеству значений!");
        }
    }


    /**
     * Обертка UPDATE
     *
     * <pre>
     * ->update( array('column'),array('data'), 'id=50' || array('id=:id', array('id'=>50)) );
     *
     * ->update(
     *      array("type","link","category","title","content","datetime","author"),
     *      array(
     *          'type'     =>'SOME DATA TITLE',
     *          'link'     =>'SOME DATA LINK',
     *          'category' =>'SOME DATA CATEGORY',
     *          'title'    =>'SOME DATA TITLE',
     *          'content'  =>'SOME DATA CONTENT',
     *          'datetime' =>'SOME DATA TIME',
     *          'author'   =>'SOME DATA AUTHOR',
     *          ),
     *      "id=13"
     *  );
     *
     * ->update(
     *      array("type","link","category","title","content","datetime","author"),
     *      array(
     *          'type'     =>'SOME DATA TITLE',
     *          'link'     =>'SOME DATA LINK',
     *          'category' =>'SOME DATA CATEGORY',
     *          'title'    =>'SOME DATA TITLE',
     *          'content'  =>'SOME DATA CONTENT',
     *          'datetime' =>'SOME DATA TIME',
     *          'author'   =>'SOME DATA AUTHOR',
     *      ),
     *      array("id=:updId AND title=:updTitle", array('updId'=>13, 'updTitle'=>'SOME TITLE'))
     *  );
     * Сгенерирует: "UPDATE pages SET title=:title, type=:type, link=:link, category=:category, subcategory=:subcategory, content=:content, datetime=:datetime WHERE id=:updId AND title=:updTitle;"
     * </pre>
     *
     * @param array $dataColumn - Масив названий колонок для обновлеия
     * @param array $dataValue - Массив значений для установленных $dataColumn
     * @param $where - определение, строка НЕ безопасно "id=$id", или безопасный вариант array( "id=:updId", array('updId'=>$id))
     * @return bool
     */
    public function update(array $dataColumn, array $dataValue, $where = null)
    {
        $table = $this->table;

        if (count($dataColumn) == count($dataValue)) {
            $constructSql = "UPDATE " . $table . " SET ";

            for ($i = 0; $i < count($dataColumn); $i++) {
                if ($i < count($dataColumn) - 1) {
                    $constructSql .= $dataColumn[$i] . "=:" . $dataColumn[$i] . ", ";
                } else {
                    $constructSql .= $dataColumn[$i] . "=:" . $dataColumn[$i] . " ";
                }
            }

            if (is_string($where)) {
                $constructSql .= " WHERE " . $where;
            } elseif (is_array($where) AND is_array($where[1])) {
                $constructSql .= " WHERE " . $where[0];
                $dataValue = array_merge($dataValue, $where[1]);
            }

            $this->sql = $constructSql;

            self::$STH = self::$DBH->prepare($constructSql);
            if(self::$DBH && !self::$STH){
                if(Rec::$debug)
                    Rec::ExceptionError("Синтаксическая ошибка запроса",$constructSql);
                return null;
            }else{

                $resultUpdate = self::$STH->execute($dataValue);
                return $resultUpdate;
            }
        } else {
            if(Rec::$debug)
                Rec::ExceptionError("Количество полей не соответствует количеству значений!");
            die("Количество полей не соответствует количеству значений!");
        }
    }


    /**
     * Обертка DELETE
     *
     * <pre>
     * Например:
     * ->delete('key=val' || ['key=:key',['key'=>val]];
     * ->delete('id=21');
     * ->delete( ['id=:id', ['id'=>'21']]);
     * </pre>
     *
     * @param string $where    Часть запроса SQL where
     * @return mixed
     */
    public function delete($where = null)
    {
        $table = $this->table;
        $dataValue = null;
        $constructSql = "DELETE FROM " . $table;

        if (is_string($where)) {
            $constructSql .= " WHERE " . $where;
        } elseif (is_array($where) AND is_array($where[1])) {
            $constructSql .= " WHERE " . $where[0];
            $dataValue = $where[1];
        }

        $this->sql = $constructSql;

        self::$STH = self::$DBH->prepare($constructSql);
        $resultUpdate = self::$STH->execute($dataValue);

        return $resultUpdate;
    }



    private function checkConnect($checkSTH=false)
    {
        if (self::$DBH == null)
            die("Connection with DataBase closed!");

        if($checkSTH)
            if (self::$STH == null)
                die('Error SQL string! Check your query string, error can be names :<br><code style="color:red"><pre>'.$this->sql.'</pre></code>');
    }


    /**
     * Выбирает все записи с указанной таблицы.
     * Если указан второй аргумент выбирает только те поля что вказаны в нем
     *
     * <pre>
     * Например:
     *
     * ->getAll();
     *
     * ->getAll("title, content, author");
     *
     * ->getAll(array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * ->getAll(null, "category='some' and visibly=1");
     *
     * </pre>
     *
     * @param null|string|array $select если string через запятую, выберает указаные поля,
     *                                  если array по значених выберает указаные
     * @param string            $where  Часть запроса SQL where
     * @param string            $order  Часть запроса SQL order
     * @return mixed
     */
    public function getAll($select = null, $where = '', $order='')
    {
        $table = $this->table;
        $sql = '';
        if ($select == null) {
            $sql = "SELECT * FROM " . $table;
        } elseif (is_string($select)) {
            $sql = "SELECT " . $select . " FROM " . $table;
        } elseif (is_array($select)) {
            $column = implode(", ", $select);
            $sql = "SELECT " . $column . " FROM " . $table;
        }
        $sql .= (!empty($where)) ? ' WHERE ' . $where : '';
        $sql .= (!empty($order)) ? ' ORDER BY ' . $order : '';

        $this->sql = $sql;

        return $this->query($sql)->all();
    }


    /**
     * Выберает все с указаной таблицы по id
     * <pre>
     * Например:
     *
     * ->getById(215);
     *
     * ->getById(215, "title, content, author");
     *
     * ->getById(215, array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     * @param number        $id       id записи
     * @param string|array  $select     если string через запятую, выберает указаные,
     *                                  если array по значених выберает указаные
     * @return mixed
     */
    public function getById($id, $select = null)
    {
        $table = $this->table;
        $sql = '';

        if ($select == null) {
            $sql = "SELECT * FROM " . $table . " WHERE id='" . $id . "'";
        } elseif (is_string($select)) {
            $sql = "SELECT " . $select . " FROM " . $table . " WHERE id='" . $id . "'";
        } elseif (is_array($select)) {
            $column = implode(", ", $select);
            $sql = "SELECT " . $column . " FROM " . $table . " WHERE id='" . $id . "'";
        }

        $this->sql = $sql;

        return $this->query($sql)->row();
    }


    /**
     * Выберает одну запись с указаной таблицы по названию колонки
     *
     * <pre>
     * Например:
     *
     * ->getByAttr("column", "column_value");
     *
     * ->getByAttr("column", "column_value", "title, content, author");
     *
     * ->getByAttr("column", "column_value", array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * ->getByAttr("column", "column_value", null, "AND link='my_link'");
     *
     * </pre>
     *
     * @param string            $attr       название колонки
     * @param string            $attrVal    значение в колонке
     * @param string|array      $select     если string через запятую, выберает указаные, если array по значених выберает указаные
     * @param string            $andWhere   AND WHERE
     * @return array
     */
    public function getByAttr($attr, $attrVal, $select = null, $andWhere=null)
    {
        $table = $this->table;
        $setWhere = ($andWhere!=null) ? $andWhere : '';

        $sql = '';
        if ($select == null) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere." ";
        } elseif (is_string($select)) {
            $sql = "SELECT " . $select . " FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere."";
        } elseif (is_array($select)) {
            $column = implode(", ", $select);
            $sql = "SELECT " . $column . " FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere."";
        }

        $this->sql = $sql;

        return $this->query($sql)->row();
    }


    /**
     * Выберает все с указаной таблицы по названию колонки
     *
     * <pre>
     * Например:
     *
     * ->getAllByAttr("column", "column_value");
     *
     * ->getAllByAttr("column", "column_value", "title, content, author");
     *
     * ->getAllByAttr("column", "column_value", array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     * @param string        $attr       По атрибуту, колонке
     * @param string        $attrVal    Значение $attr по которому делается поиск
     * @param string        $andWhere
     * @param string|array  $select     Поля что  нужно выбрать
     *                                      если string через запятую, выберает указаные,
     *                                      если array по значених выберает указаные
     * @return mixed
     */
    public function getAllByAttr($attr, $attrVal, $select=null, $andWhere=null)
    {
        $table = $this->table;
        $setWhere = ($andWhere!=null) ? $andWhere : '';

        $sql = '';
        if ($select == null) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere." ";
        } elseif (is_string($select)) {
            $sql = "SELECT " . $select . " FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere." ";
        } elseif (is_array($select)) {
            $column = implode(",", $select);
            $sql = "SELECT " . $column . " FROM " . $table . " WHERE " . $attr . "='" . $attrVal . "' ".$setWhere." ";
        }

        $this->sql = $sql;

        return $this->query($sql)->all();
    }


    /**
     * Подсчет количества записй в таблице
     *
     * @param string    $where
     * @return bool|number
     */
    public function countRows($where=null)
    {
        $table = $this->table;
        if($where!=null)
            $where = 'WHERE '.$where;

        $this->sql = "SELECT COUNT(*) as counter FROM $table ".$where;

        $result = $this->query($this->sql)->all();

        if(isset($result[0]['counter']))
            return $result[0]['counter'];
        else
            return false;
    }


    /**
     * Определение последнего ID в таблице
     *
     * @param string    $primaryKey     имя столбца для подсчета
     * @return number
     */
    public function lastId($primaryKey ='id')
    {
        $table = $this->table;
        $this->sql = "SELECT $primaryKey FROM $table ORDER BY $primaryKey DESC ";

        $result = $this->query($this->sql)->all();
        if(isset($result[0][$primaryKey]))
            return $result[0][$primaryKey];
        else
            return 0;
    }

    public function lastInsertId()
    {
        return self::$DBH->lastInsertId();
    }

    /**
     * Закрыть соединение
     */
    public function close()
    {
        self::$STH = null;
        self::$DBH = null;
        $this->sql = null;
        $this->config = null;
        $this->user = null;
        $this->password = null;
        unset(self::$DBH);
    }


    /**
     * Реконект
     */
    public function reset()
    {
        $this->close();
        try {
            $this->init($this->config,$this->user,$this->password,$this->table,$this->primaryKey);
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

} 