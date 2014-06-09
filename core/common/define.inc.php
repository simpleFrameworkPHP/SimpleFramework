<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-30
 * Time: 上午11:09
 */
defined('APP_PATH') or define('APP_PATH',__PATH__.'/application');
defined('CACHE_PATH') or define('CACHE_PATH',__PATH__.'/cache');
$self =explode('/',$_SERVER['PHP_SELF']);
defined('__ROOT__') or define('__ROOT__',count($self) == 3 ? $_SERVER['SERVER_NAME'] . '/' . $self[1] : $_SERVER['SERVER_NAME']);
return array();