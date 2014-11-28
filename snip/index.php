<?php
define('DEBUG', true);
define('START_TIMER', microtime(true));
if(DEBUG){ ini_set("display_errors",1); error_reporting(E_ALL); }
else{ ini_set("display_errors",0); error_reporting(0);}

require_once('../rec/Rec.php');

$R = new rec\Rec('app', DEBUG);

$R->setApplicationName('Snippets Notes');

/**
 * Create connection with database
 */
$R->setConnection(
    [
        'db' =>
            [
                'dbh' => 'sqlite:../snip/app/Database/snippets.sqlite',
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
$R->urlDefault('Index');
$R->urlNotFound('Index/error404');

$R->run();