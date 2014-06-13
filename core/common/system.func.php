<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-13
 * Time: 上午11:59
 */
//加载config
function loadConfig(){
    $config = array();
    if(file_exists(CORE_PATH.'/conf')){
        $config = loadConfigFile(CORE_PATH.'/conf');
    }
    if(file_exists(__PATH__.'/conf')){
        $config = loadConfigFile(__PATH__.'/conf',$config);
    }
    if(!isset($_REQUEST['app'])){
        $_REQUEST['app'] = $config['SF_DEFAULT_APP'];
    }
    if(!isset($_REQUEST['act'])){
        $_REQUEST['act'] = $config['SF_DEFAULT_ACT'];
    }
    if(!isset($_REQUEST['fun'])){
        $_REQUEST['fun'] = $config['SF_DEFAULT_FUN'];
    }
    if(file_exists(__PATH__.'/application/'.$_REQUEST['app'].'/conf')){
        $config = loadConfigFile(__PATH__.'/application/'.$_REQUEST['app'].'/conf',$config);
    }
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
            $config = array_merge($config,include $sub_dir);
        }
    }
    return $config;
}