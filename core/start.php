<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午5:27
 */
error_reporting(E_ALL ^ E_NOTICE);
define('CORE_PATH',__PATH__.'/core');
/** 自动加载一次文件的方法
 * @param $path
 * @param $param
 * @param $type
 */
function loadFile_once($path,$param,$type){
    switch($type){
        case 'FUNCTION':$result = function_exists($param);break;
        case 'CLASS':$result = class_exists($param);break;
        case 'CONSTANT':$result = defined($param);break;
    }
    if(!$result && file_exists($path)){
        include $path;
    }
}
defined('CORE_PATH') or define('CORE_PATH',__PATH__.'/core');
loadFile_once(CORE_PATH.'/common/define.inc.php','ONE_DAY','CONSTANT');
loadFile_once(CORE_PATH.'/common/system.func.php','loadConfig','FUNCTION');
@register_shutdown_function('erroPage');
$debug = C('SF_DEBUG');
if($debug){
//    调试模式
    ini_set("display_errors",1);
}
if(C('SF_TIME_ZONE')){
    date_default_timezone_set(C('SF_TIME_ZONE'));
}
defined('__THEME__') or define('__THEME__',__PUBLIC__.'/'.C('SF_THEME_DEFAULT'));
defined('THEME_PATH') or define('THEME_PATH',__PATH__.'/public/'.C('SF_THEME_DEFAULT'));
define('DB_PREFIX',C('SF_DB_PREFIX'));
loadFile_once(CORE_PATH.'/common/function.func.php','loadDirFile','FUNCTION');
loadDirFile(__PATH__.'/common/');
@session_start();

//自动加载基础类
loadDirFile(CORE_PATH.'/class');