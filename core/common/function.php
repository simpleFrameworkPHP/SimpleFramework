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
            include_once $path . DIRECTORY_SEPARATOR . $file;
        }
    }
}
//加载controller
function runController($application,$controller){
    loadDirFile(__ROOT__.'/application/'.$application.'/conf');
    loadDirFile(__ROOT__.'/application/'.$application.'/common');
    $controller .= 'Controller';
    //加载请求模块需要的controller文件
   $str = require_once __ROOT__.'/application/'.$application.'/controller/'.$controller.'.class.php';
    $result = new $controller();
    return $result;
}
//加载config
function loadConfig(){
    $config = include_once CORE_PATH.'/conf/config.inc.php';
    $config = array_merge($config,include_once __ROOT__.'/conf/config.inc.php');
    if(!$_REQUEST['app']){
        $_REQUEST['app'] = $config['default_app'];
    }
    if(!$_REQUEST['act']){
        $_REQUEST['act'] = $config['default_act'];
    }
    if(!$_REQUEST['fun']){
        $_REQUEST['fun'] = $config['default_fun'];
    }
    $config = array_merge($config,include_once __ROOT__.'/application/'.$_REQUEST['app'].'/conf/config.inc.php');
    return $config;
}
//新建文件夹
function addDir($path){
    $dir = dirname($path);
    $dir = str_replace(__ROOT__.'/','',$dir);
    $dir_array = array();
    $dir_array = explode('/',$dir);
    $count = count($dir_array);
    $idir = __ROOT__;
    for($i=0;$i<$count;$i++){
        $idir .= '/'.$dir_array[$i];
        if(!file_exists($idir)){
            mkdir($idir,0755);
        }
    }
}
//获取和设置配置项
function C($key,$value =''){
    global $config;
    if($value != ''){
        $config[$key] = $value;
    }
    return $config[$key];
}