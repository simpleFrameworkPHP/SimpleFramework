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

defined('EXPEND_PATH') or define('EXPEND_PATH',CORE_PATH.'/expend');
defined('APP_PATH') or define('APP_PATH',__PATH__.'/application');
defined('CACHE_PATH') or define('CACHE_PATH',__PATH__.'/cache');
defined('DATA_PATH') or define('DATA_PATH',__PATH__.'/data');
defined('UPLOAD_PATH') or define('UPLOAD_PATH',DATA_PATH.'/upload');
$self =explode('/',$_SERVER['PHP_SELF']);
$root = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
$root .= $_SERVER['SERVER_PORT'] <> 80 ? ':'.$_SERVER['SERVER_PORT']:'';
if(count($self) == 3){
    $root .= '/' . $self[1];
}
defined('__ROOT__') or define('__ROOT__',$root);
defined('__PUBLIC__') or define('__PUBLIC__',__ROOT__.'/public');
defined('__JSROOT__') or define('__JSROOT__',__PUBLIC__.'/js');
defined('__PLROOT__') or define('__PLROOT__',__PUBLIC__.'/plugn');
defined('PL_PATH') or define('PL_PATH',__PATH__.'/plugn');
defined('DATA_ROOT') or define('DATA_ROOT',__ROOT__.'/data');
defined('UPLOAD_ROOT') or define('UPLOAD_ROOT',DATA_ROOT.'/upload');