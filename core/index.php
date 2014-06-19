<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:19
 */
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
loadFile_once(CORE_PATH.'/common/define.inc.php','ONE_DAY','CONSTANT');
loadFile_once(CORE_PATH.'/common/system.func.php','loadDirFile','FUNCTION');
//初值设置
global $config;
$config = loadConfig();
if($config['SF_DEBUG']){
    //调试模式
    ini_set("display_errors",1);
}
if(C('SF_TIME_ZONE')){
    date_default_timezone_set(C('SF_TIME_ZONE'));
}
loadFile_once(CORE_PATH.'/common/function.func.php','loadDirFile','FUNCTION');

defined('__THEME__') or define('__THEME__',__ROOT__.'/public/'.C('SF_THEME_DEFAULT'));
define('DB_PREFIX',C('SF_DB_PREFIX'));
//自动加载基础类
loadDirFile(CORE_PATH.'/class');

$controller = runController($_REQUEST['app'],$_REQUEST['act']);
if(method_exists($controller,$_REQUEST['fun'])){
    $controller->$_REQUEST['fun']();
} else {
    errorPage('先创建好方法，我们再来一起玩耍吧！',$_REQUEST['app'].'/'.$_REQUEST['act'].'下----'.$_REQUEST['fun'].'方法不存在',500);
    Log::write('ATC ERROR',$_REQUEST['app'].'/'.$_REQUEST['act'].'下----'.$_REQUEST['fun'].'方法不存在');
}