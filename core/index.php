<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:19
 */

define('CORE_PATH',__ROOT__.'/core');
include_once CORE_PATH.'/common/define.inc.php';
include_once CORE_PATH.'/common/function.php';
//初值设置
global $config;
$config = loadConfig();
//自动加载基础类
loadDirFile(CORE_PATH.'/class');

$controller = runController($_REQUEST['app'],$_REQUEST['act']);
if(method_exists($controller,$_REQUEST['fun'])){
    $controller->$_REQUEST['fun']();
} else {
    errorPage('先创建好方法，我们再来一起玩耍吧！',$_REQUEST['app'].'/'.$_REQUEST['act'].'下----'.$_REQUEST['fun'].'方法不存在',500);
}