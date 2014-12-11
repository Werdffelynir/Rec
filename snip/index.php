<?php

define('DEBUG', true);
define('START_TIMER', microtime(true));
if(DEBUG){ ini_set("display_errors",1); error_reporting(E_ALL); }
else{ ini_set("display_errors",0); error_reporting(0);}

require_once('../rec/Rec.php');

$R = new rec\Rec('app', DEBUG);

/**
 * Default Language
 */
$R->setLanguage('ru');

/**
 * Default Application name
 */
$R->setApplicationName('Application');

/**
 * Create connection with database
 */
$R->setConnection(
    [
        'db' =>
        [
            'dbh' => 'sqlite:../www/app/Database/snippets.sqlite',
        ],
        'dbMysql'=>
        [
            'dbh' => 'mysql:host=localhost;dbname=test',
            'user' => 'root',
            'pass' => ''
        ]
    ]
);

/**
 * Application some config params
 */
$R->setConf(
    [
        'email'=>'admin@admin.com',
    ]
);

/**
 * Alias to controllers and roles
 *      urlAdd(' Class/Method ', ' url/{!p}/{n} ')
 *
 *  {n} {!n} number [0-9]
 *  {w} {!w} words [a-z]
 *  {p} {!p} symbol [a-z0-9_-]
 *  {*} all
 *  product/{w:category}/{n:id}
 *
 * Заметка: Class не должен вызывать метод одноименный себе, например класс Page->page() "Page/page"
 */
$R->routeDefault('Index');
$R->routePageNotFound('Index/error404');

$R->route('Index/cat', 'cat/{p:id}');
$R->route('Index/snippet', 'snippet/{p:id}');

$R->route('Space/index', 'space');
$R->route('Space/profile', 'profile');
$R->route('Space/edit', 'edit/{p:id}');



/**
 * Run application
 */
$R->run();