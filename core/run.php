<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午5:28
 */


$controller = runController($_REQUEST['a'],$_REQUEST['c']);
if(method_exists($controller,$_REQUEST['f'])){
    $str = strval($_REQUEST['f']);
	$controller->$str();
} else {
	if(method_exists($controller,'_empty')){
		$controller->_empty();
	} else {
		errorPage('先创建好方法，我们再来一起玩耍吧！',$_REQUEST['a'].'/'.$_REQUEST['c'].'下----'.$_REQUEST['f'].'方法不存在',500);
		Log::write('ATC ERROR',$_REQUEST['a'].'/'.$_REQUEST['c'].'下----'.$_REQUEST['f'].'方法不存在');
	}
}