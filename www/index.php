<?php
define('START_TIMER', microtime(true));


require_once('../rec/Rec.php');
use rec\Rec;

$R = new Rec('app');

$R->setApplicationName('Rec 0.3. Default Web Application');

$R->urlDefault('Main');
$R->urlNotFound('Main/error404');


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

$R->urlAdd('Main', 'index');
$R->urlAdd('Main/test', 'test');
$R->urlAdd('Main/page', 'page');
$R->urlAdd('Main/main', 'main');

$R->run();