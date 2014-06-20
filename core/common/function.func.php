<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:26
 */
//加载目录内所有文件
function loadDirFile($path = '.'){
    $current_dir = is_dir($path) ? opendir($path) : opendir(CORE_PATH);    //opendir()返回一个目录句柄,失败返回false
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
    if(is_dir(__PATH__.'/application/'.$application.'/common'))
        loadDirFile(__PATH__.'/application/'.$application.'/common');
    $file_path = __PATH__.'/application/'.$application.'/controller/'.$controller.'Controller.class.php';
    if(!class_exists($controller.'Controller') && file_exists($file_path)){
        //加载请求模块需要的controller文件
        $str = require $file_path;// errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件错误',500);
    } else {
        errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件不存在',404);
        Log::write('ACT ERROR',$application.'/'.$controller.'类文件不存在');
    }
    $controller .= 'Controller';
    $result = new $controller();
    return $result;
}
//预留--日志打印
function sflog($str,$type,$mode){

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
        $path = explode('/',$path);
        $count_url = count($path);
        $fun = (isset($path[$count_url-1]) && $path[$count_url-1] <> '') ? $path[$count_url-1] :  C('SF_DEFAULT_FUN');
        $act = (isset($path[$count_url-2]) && $path[$count_url-2] <> '') ? $path[$count_url-2] :  C('SF_DEFAULT_ACT');
        $app = (isset($path[$count_url-3]) && $path[$count_url-3] <> '') ? $path[$count_url-3] :  C('SF_DEFAULT_APP');
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
function writeUrl($path='',$params=''){
    echo H($path,$params);
}

/** 缓存方法
 * @param $key
 * @param string $value
 * @param string $type  缓存的大类型
 * @param int $time     缓存过期时长
 * @return bool|mixed   返回缓存值   false为失败
 */
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
 * @return bool
 */
function addDir($file){
    $dir = is_dir($file) ? $file : dirname($file);
    $dir_array = array();
    $dir_array = explode('/',$dir);
    $count = count($dir_array);
    $idir = '';
    for($i=0;$i<$count;$i++){
        $idir .= $dir_array[$i] . '/';
        if(!is_dir($idir)){
            mkdir($idir,0755);
        }
    }
    return is_dir($dir);
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
    if(!is_dir($path)){
        return true;
    }
    //先删除目录下的文件：
    $dh=opendir($path);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$path."/".$file;echo $fullpath;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                removeDir($fullpath);
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
//获取稳健信息
function getFileContent($url){
    $opts = array('http'=>array('method'=>"GET",'timeout'=>5));
    $context = stream_context_create($opts);
    $data = file_get_contents($url, false, $context);
    return  $data;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}