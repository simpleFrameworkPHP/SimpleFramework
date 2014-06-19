<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-30
 * Time: 上午11:09
 */
//一天的秒数
defined('ONE_DAY') or define('ONE_DAY',86400);
//多结果查询
defined('CLIENT_MULTI_RESULTS') or define('CLIENT_MULTI_RESULTS',131072);
defined('APP_PATH') or define('APP_PATH',__PATH__.'/application');
defined('CACHE_PATH') or define('CACHE_PATH',__PATH__.'/cache');
$self =explode('/',$_SERVER['PHP_SELF']);
defined('__ROOT__') or define('__ROOT__',count($self) == 3 ? 'http://'.$_SERVER['SERVER_NAME'] . '/' . $self[1] : 'http://'.$_SERVER['SERVER_NAME']);
defined('__PUBLIC__') or define('__PUBLIC__',__ROOT__.'/public');
defined('__JSROOT__') or define('__JSROOT__',__PUBLIC__.'/js');