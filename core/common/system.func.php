<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-13
 * Time: 上午11:59
 * Remark: The file is not suitable for modification.
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
    if(!isset($_REQUEST['a'])){
        $_REQUEST['a'] = $config['SF_DEFAULT_APP'];
    }
    if(file_exists(__PATH__.'/application/'.$_REQUEST['a'].'/conf')){
        $config = loadConfigFile(__PATH__.'/application/'.$_REQUEST['a'].'/conf',$config);
    }
    if(!isset($_REQUEST['c'])){
        $_REQUEST['c'] = $config['SF_DEFAULT_CON'];
    }
    if(!isset($_REQUEST['f'])){
        $_REQUEST['f'] = $config['SF_DEFAULT_FUN'];
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
//获取和设置config参数
function C($name,$value = ''){
    global $sf_config;
    if(empty($sf_config)){
        $sf_config = loadConfig();
    }
    if($value == ''){
        if($name != ''){
            $result = $sf_config[$name];
        } else if($sf_config['SF_DEBUG'] == true) {
            $result =  $sf_config;
        }
    } else {
        $sf_config[$name] = $value;
        $result = $value;
    }
    return $result;
}
//请求重定向
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if ( empty($msg) )
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}
//错误页面显示
function errorPage($msg = '',$info = '',$error_code = 404,$path=''){
    if(function_exists('C')){
        $show_page = C('SF_DEBUG');
    } else {
        $show_page = true;
    }
    if($show_page){
        //参数默认时初始化参数
        if($msg == ''){
            $error = error_get_last();
            $msg = 'ERROR';
            $info = $error['message'] . ' in <b>' . $error['file'] . '</b> on line ' . $error['line'];
            $error_code = 500;
        }
        if($path == ''){ var_dump(__THEME__);
            $path[] = __THEME__.'/pages/errorPage.html';
            $path[] = CORE_PATH.'/pages/errorPage.html';
        }
        $echo_info = true;
        foreach($path as $i_path){
            if(file_exists($i_path)){
                $echo_info = false;
                include $i_path;
            }
        }
        if($echo_info){
            echo $info;
        }
    } else {
        redirect(H(C('SF_DEFAULT_APP').'/'.C('SF_DEFAULT_CON').'/'.C('SF_DEFAULT_FUN')));
    }
    Log::write('PAGE ERROR',$info);
}