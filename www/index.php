<?php

define('DEBUG', true);
define('START_TIMER', microtime(true));
if(DEBUG){ ini_set("display_errors",1); error_reporting(E_ALL); }
else{ ini_set("display_errors",0); error_reporting(0);}

require_once('../rec/Rec.php');

$R = new rec\Rec('app', DEBUG);

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
/*
$R->urlDefault('Site');
$R->urlNotFound('Site/error404');

$R->urlAdd('Site/index', 'index');
$R->urlAdd('Site/downloads', 'downloads');
$R->urlAdd('Site/contacts', 'contacts');
$R->urlAdd('Blog/index', 'blog');
$R->urlAdd('Blog/page', 'page/{p}');
$R->urlAdd('Blog/list', 'list');
$R->urlAdd('Blog/article', 'article');
*/

$R->routeDefault('Site');
$R->routePageNotFound('Site/error404');

$R->route('Site/index', 'index');
$R->route('Site/login', 'login/{p:user}/{p:pass}');
$R->route('Site/register', 'register');
$R->route('Site/downloads', 'downloads');
$R->route('Site/contacts', 'contacts');

$R->route('Blog');




//$R->route('Blog','{:actions}');

/**
 * Run application
 */
$R->run();



















