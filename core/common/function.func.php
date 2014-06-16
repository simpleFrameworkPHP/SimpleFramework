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
            $file_type = explode('.',$file);
            if(isset($file_type[2]) && strtolower($file_type[2]) == 'php'){
                if(isset($file_type[1]) && strtolower($file_type[1]) == 'class'){
                    $result[] = loadFile_once($sub_dir,$file_type[0],'CLASS');
                } else {
                    $result[] = require $sub_dir;
                }
            } else {
               $result[] = include $sub_dir;
            }
        }
    }
    return $result;
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
//预留--日志打印
function sflog($str,$type,$mode){

}
//获取和设置config参数
function C($name,$value = ''){
    global $config;
    if($value == ''){
        if($name != ''){
            $result = $config[$name];
        } else if($config['SF_DEBUG'] == true) {
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
            $model = $path[1].'Model';
        } else {
            $app = $_REQUEST['app'];
            $model = $path.'Model';
        }
        loadFile_once(APP_PATH.'/'.$app.'/model/'.$model.'.class.php', $model, 'CLASS');
    } else {
        $model = 'Model';
    }
    $config = C('SF_DB_CONNECT');
    if(class_exists($model) && isset($config[$link_ID])){
        return new $model($config[$link_ID]['DB_HOST'],$config[$link_ID]['DB_USER'],$config[$link_ID]['DB_PASS'],
            $config[$link_ID]['DB_NAME'],$config[$link_ID]['DB_PORT'],$config[$link_ID]['DB_MODE'],$link_ID);
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
        $fun = $_REQUEST['fun'] ? $_REQUEST['fun'] : C('SF_DEFAULT_FUN');
        $act = $_REQUEST['act'] ? $_REQUEST['act'] : C('SF_DEFAULT_ACT');
        $app = $_REQUEST['app'] ? $_REQUEST['app'] : C('SF_DEFAULT_APP');
        if(strstr($path,'/')) {
            $path = explode('/',$path);
            $fun = $path[0] ? $path[0] : C('SF_DEFAULT_FUN');
            $act = $_REQUEST['act'] ? $_REQUEST['act'] : C('SF_DEFAULT_ACT');
            $app = $_REQUEST['app'] ? $_REQUEST['app'] : C('SF_DEFAULT_APP');
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
    $cache = Cache::initCacheMode(C('SF_CACHE_MODE'));
    $result = false;
    if($value == ''){
        $result = $cache->getParam($key,$type);
    } else {
        $cache->setParam($key,$value,$type,$time);
    }
    return $result;
}

/**自动创建文件目录
 * @param $file 带目录的文件名
 */
function addDir($file){
    $dir = dirname($file);
    $dir = str_replace(__PATH__.'/','',$dir);
    $dir_array = array();
    $dir_array = explode('/',$dir);
    $count = count($dir_array);
    $idir = __PATH__.'/';
    if(!is_dir($idir)){
        mkdir($idir,0755);
    }
    for($i=0;$i<$count;$i++){
        $idir .= $dir_array[$i] . '/';
        if(!is_dir($idir)){
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