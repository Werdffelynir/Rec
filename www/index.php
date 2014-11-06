<?php
define('START_TIMER', microtime(true));


require_once('../rec/Rec.php');
use rec\Rec;

$R = new Rec('app');

$R->setApplicationName('Rec 0.3. Default Web Application');

$R->urlDefault('Main');
$R->urlNotFound('Main/error404');

/**
 * Create connection with database
 */
$R->connection(
    [
        'db' => [
            'dbh' => 'sqlite:../www/app/Database/documentation.sqlite',
        ],
        'dbMysql' => [
            'dbh' => 'mysql:host=localhost;dbname=test',
            'user' => 'root',
            'pass' => ''
        ],
    ]
);

/**
 * Alias to controllers and roles
 *      urlAdd(' Class/Method ', ' url/{!p}/{n} ')
 *
 *  {n} {!n} number [0-9]
 *  {w} {!w} words [a-z]
 *  {p} {!p} symbol [a-z0-9_-]
 *
 * Заметка: Class не должен вызывать метод одноименный себе, например класс Page->page() "Page/page"
 */

$R->urlAdd('Main/home', 'home');

$R->run();