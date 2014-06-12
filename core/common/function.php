<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:26
 */
//加载目录内所有文件
function loadDirFile($path = '.'){
    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if($file == '.' || $file == '..') {
            continue;
        } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
            loadDirFile($sub_dir);
        } else {    //如果是文件,直接输出
            require $path . DIRECTORY_SEPARATOR . $file;
        }
    }
}
//加载controller
function runController($application,$controller){
    loadDirFile(__PATH__.'/application/'.$application.'/common');
    $file_path = __PATH__.'/application/'.$application.'/controller/'.$controller.'Controller.class.php';
    if(!class_exists($controller.'Controller') && file_exists($file_path)){
        //加载请求模块需要的controller文件
        $str = require $file_path;// errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件错误',500);
    } else {
        errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件不存在',404);
    }
    $controller .= 'Controller';
    $result = new $controller();
    return $result;
}
//错误页面显示
function errorPage($msg,$info,$error_code = 404,$path=''){
    if($path == ''){
        $path = CORE_PATH.'/pages/errorPage.html';
    }
    if(file_exists($path)){
        include $path;
    } else {
        echo $info;
    }
}
//日志打印
function sflog($str,$type,$mode){

}
//加载config
function loadConfig(){
    $config = array();
    $config = loadConfigFile(CORE_PATH.'/conf');
    $config = loadConfigFile(__PATH__.'/conf',$config);
    if(!$_REQUEST['app']){
        $_REQUEST['app'] = $config['sf_default_app'];
    }
    if(!$_REQUEST['act']){
        $_REQUEST['act'] = $config['sf_default_act'];
    }
    if(!$_REQUEST['fun']){
        $_REQUEST['fun'] = $config['sf_default_fun'];
    }
    $config = loadConfigFile(__PATH__.'/application/'.$_REQUEST['app'].'/conf',$config);
    return $config;
}
//加载config文件
function loadConfigFile($path,$config = array()){
    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if($file == '.' || $file == '..') {
            continue;
        } else {    //如果是文件,直接输出
            $config = array_merge($config,include $path . DIRECTORY_SEPARATOR . $file);
        }
    }
    return $config;
}
//获取和设置config参数
function C($name,$value = ''){
    global $config;
    if($value == ''){
        if($name != ''){
            $result = $config[$name];
        } else if($config['sf_debug'] == true) {
            $result =  $config;
        }
    } else {
        $config[$name] = $value;
        $result = $value;
    }
    return $result;
}
//创建模型方法（数据库处理）
function M($path='',$link_ID = 0){
    if($path != ''){
        if(strstr($path,'/')){
            $path = explode('/',$path);
            $app = $path[0];
            $model = $path[1].'Model.class.php';
        } else {
            $app = $_REQUEST['app'];
            $model = $path.'Model';
        }
        class_exists($model) or require(APP_PATH.'/'.$app.'/model/'.$model.'.class.php');
    } else {
        $model = 'Model';
    }
    $config = C('sf_db_connect');
    if(class_exists($model)){
        return new $model($config[$link_ID],$link_ID);
    } else {
        return false;
    }
}
//创建url以及跳转方法
function H($path='',$params='',$redirect = false){
    $url = '';
    if(is_string($path)){
        $url = 'http://'. __ROOT__ . '/index.php?';
        //如果path为‘’即可直接使用
        $fun = $_REQUEST['fun'] ? $_REQUEST['fun'] : C('sf_default_fun');
        $act = $_REQUEST['act'] ? $_REQUEST['act'] : C('sf_default_act');
        $app = $_REQUEST['app'] ? $_REQUEST['app'] : C('sf_default_app');
        if(strstr($path,'/')) {
            $path = explode('/',$path);
            $fun = $path[0] ? $path[0] : C('sf_default_fun');
            $act = $_REQUEST['act'] ? $_REQUEST['act'] : C('sf_default_act');
            $app = $_REQUEST['app'] ? $_REQUEST['app'] : C('sf_default_app');
        }
        $url .='app='.$app.'&act='.$act.'&fun='.$fun;
    }
    if(is_array($params)){
        foreach($params as $key =>$value){
            $url .= '&'.$key.'='.$value;
        }
    } else {
        $url .= $params;
    }
    if($redirect){
        http_redirect($url);
    } else {
        return $url;
    }
}
//预留---缓存方法
function S($key,$value = '',$type='system',$time=85400){
    $cache = Cache::initCacheMode(C('sf_cache_mode'));
    $result = '';
    if($value == ''){
        $result = $cache->getParam($key,$type,$time);
    } else {
        $cache->setParam($key,$value,$type,$time);
    }
    return $result;
}
//新建文件夹
function addDir($path){
    $dir = dirname($path);
    $dir = str_replace(__PATH__.'/','',$dir);
    $dir_array = array();
    $dir_array = explode('/',$dir);
    $count = count($dir_array);
    $idir = __PATH__;
    for($i=0;$i<$count;$i++){
        $idir .= '/'.$dir_array[$i];
        if(!file_exists($idir)){
            mkdir($idir,0755);
        }
    }
}
//获取当前时间
function nowTime(){
    //优化--当多服务器设置时应获取数据库时间或时间服务器的时间或者是请求时间
    return time();
}
//删除文件
function removeFile($path){
    //优化--
    unlink($path);
}
//删除文件夹
function removeDir($path) {
    //先删除目录下的文件：
    $dh=opendir($path);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$path."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }

    closedir($dh);
    //删除当前文件夹：
    if(rmdir($path)) {
        return true;
    } else {
        return false;
    }
}