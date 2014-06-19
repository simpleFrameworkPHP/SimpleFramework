<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午5:28
 */


$controller = runController($_REQUEST['app'],$_REQUEST['act']);
if(method_exists($controller,$_REQUEST['fun'])){
    $controller->$_REQUEST['fun']();
} else {
    errorPage('先创建好方法，我们再来一起玩耍吧！',$_REQUEST['app'].'/'.$_REQUEST['act'].'下----'.$_REQUEST['fun'].'方法不存在',500);
    Log::write('ATC ERROR',$_REQUEST['app'].'/'.$_REQUEST['act'].'下----'.$_REQUEST['fun'].'方法不存在');
}