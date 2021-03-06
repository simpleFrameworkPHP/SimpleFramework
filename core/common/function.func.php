<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:26
 */
//加载目录内所有文件
function loadDirFile($path = '.'){
    $current_dir = is_dir($path) ? $path : dirname($path);    //opendir()返回一个目录句柄,失败返回false
    $files = glob($current_dir.'/*');
    foreach($files as $file) {
        $sub_dir =  $file;    //构建子目录路径
        if(!is_file($sub_dir)) {    //如果是目录,进行递归
            loadDirFile($sub_dir);
        } else {    //如果是文件,直接输出
            $file_type = explode('.',$file);
            if(isset($file_type[2]) && strtolower($file_type[2]) == 'php'){
                if(isset($file_type[1]) && strtolower($file_type[1]) == 'class'){
                    $result[] = loadFile_once($sub_dir,basename($file_type[0]),'CLASS');
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
    $charset = C('DEFAULT_CHARSET');
    $contentType = C('TMPL_CONTENT_TYPE');
    @header('Content-Type:'.$contentType.'; charset='.$charset);
    $controller = ucfirst($controller);
    if(is_dir(__PATH__.'/application/'.$application.'/common'))
        loadDirFile(__PATH__.'/application/'.$application.'/common');
    $file_path = __PATH__.'/application/'.$application.'/controller/'.$controller.'Controller.class.php';
    if(file_exists($file_path)){
        if(!class_exists($controller.'Controller')){
            //加载请求模块需要的controller文件
            $str = require $file_path;// errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件错误',500);
        } else {
            errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件不存在',404);
            Log::write('CON ERROR',$application.'/'.$controller.'类文件不存在');
        }
        $controller .= 'Controller';
        $result = new $controller();
    } else {
        $file_path = __PATH__.'/application/'.$application.'/controller/EmptyController.class.php';
        if(file_exists($file_path)){
            if(!class_exists('EmptyController')){
                $str = require $file_path;
            } else {
                errorPage('又错了，下次认真点！',$application.'/'.$controller.'类文件不存在',404);
                Log::write('CON ERROR',$application.'/'.$controller.'类文件不存在');
            }
            $result = new EmptyController();
        } else {
            $result = null;
        }
    }

    return $result;
}
//预留--日志打印
function logWrite($title, $info, $file_prefix){
    if(!class_exists('Log')){
        include '../class/Log.class.php';
    }
    Log::write($title,$info,$file_prefix);
}
//创建模型方法（数据库处理）
function M($path='',$link_ID = 0){
    $table_name = '';
    if($path != ''){
        if(strstr($path,'/')){
            $path = explode('/',$path);
            $app = $path[0];
            $table_name = $path[1];
            $model = $path[1].'Model';
        } else {
            $app = $_REQUEST['a'];
            $table_name = $path;
            $model = $path.'Model';
        }
        loadFile_once(APP_PATH.'/'.$app.'/model/'.$model.'.class.php', $model, 'CLASS');
    } else {
        $table_name = $path;
        $model = 'Model';
    }
    $config = C('SF_DB_CONNECT');
    if(isset($config[$link_ID])){
        $link = $config[$link_ID];
        if(class_exists($model)){
            $model = new $model($link['DB_HOST'],$link['DB_USER'],$link['DB_PASS'],$link['DB_NAME'],$link['DB_PORT'],$link['DB_MODE'],$link_ID,C('SF_DB_CHARSET'));
            return $model;
        } else {
            $model = new Model($link['DB_HOST'],$link['DB_USER'],$link['DB_PASS'],$link['DB_NAME'],$link['DB_PORT'],$link['DB_MODE'],$link_ID,C('SF_DB_CHARSET'));
            $model->table($table_name);
            return $model;
        }
    }

}
//创建url以及跳转方法
function H($path='',$params='',$redirect = false){
    $url = '';
    if(is_string($path)){
        $url =  __ROOT__ . '/index.php?';
        $path = explode('/',$path);
        $fun = end($path) ? end($path) : $_REQUEST['f'];
        array_pop($path);
        $con = end($path) ? end($path) : $_REQUEST['c'] ;
        array_pop($path);
        $app = end($path) ? end($path) : $_REQUEST['a'] ;
        $url .='a='.$app.'&c='.$con.'&f='.$fun;
    }
    if(is_array($params)){
        foreach($params as $key =>$value){
            if(is_array($value)) $value = implode(',',$value);
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

/** 缓存方法
 * @param $super_key
 * @param string $value
 * @param int $time     缓存过期时长
 * @return bool|mixed   返回缓存值   false为失败
 */
function S($super_key,$value = '',$time = 86400){
    $cache = Cache::initCacheMode(C('SF_CACHE_MODE'));
    $result = false;
    if(strstr($super_key,'/')){
        $key_array = explode('/',$super_key);
        $key = end($key_array);
        $type = trim($super_key,'/'.$key);
    } else {
        $type = 'system';
    }
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
    $dir = substr($file,-1) == '/' ? $file : dirname($file);
    $dir_array = explode('/',$dir);
    $idir = '';
    foreach($dir_array as $value){
        $idir .= $value . '/';
        if(!is_dir($idir)){
            mkdir($idir,0777);
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
            $fullpath=$path."/".$file;echo "Clear file:".$fullpath."<br/>";
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
//获取文件信息
function getFileContent($url){
    $opts = array('http'=>array('method'=>"GET",'timeout'=>5));
    $context = stream_context_create($opts);
    $data = @file_get_contents($url, false, $context);
    return  $data;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function getClientIp($type = 0,$adv=false) {
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
//消除session
function logout(){
    session_unset();			//清空session
    session_destroy();			//删除session文件
    if (isset($_SESSION)) {
        unset($_SESSIONI);	//注销$_SESSION
    }
    //预留--换成页面来优化一下
    echo '您已经退出登录';
}
//执行所有任务
function runAllTask(){
    $lock_path = CACHE_PATH.'/lock';
    addDir($lock_path);
    $lock_task = $lock_path.'/Task.lock';
    if(file_exists($lock_task) && filemtime($lock_task) < nowTime() - 1800){
        //超过半小时未执行
        @unlink($lock_task);
    }
    if(!file_exists($lock_task)){
        //总任务锁
        @touch($lock_task);
        $task_array = S('TASK/TASK_LIST');
        if(!$task_array){
            $new = new Task();
            $task_array = $new->getAllTask();
            S('TASK/TASK_LIST',$task_array);
        }
        array_walk($task_array,'runTask',$lock_path);print_r($task_array);
        S('TASK/TASK_LIST',$task_array);
        @unlink($lock_task);
    }
}
//单个任务执行方法
function runTask(&$value, $key, $lock_path,$last_time = -1){
    $lock_file = $lock_path.'/'.$key.'.lock';
    $last_time = $last_time == -1 ? $value['last_time'] : $last_time;
    if((!file_exists($lock_file) || filemtime($lock_file) < nowTime() - $value['i_time']) && $last_time < nowTime()){
        //执行定时任务，建立单个任务锁
        @touch($lock_file);
        if(!class_exists($key))include $value['file'];
        $i_task = new $key();
        $i_task->run();
        //记录任务的执行时间并记录下一次执行时间
        $value['last_time'] = nowTime() + $value['i_time'];
        @unlink($lock_file);
    }
}
//引入其他模板的方法
function initH($file){
    if(!in_array($file,array('swfupload'))){
        $path = THEME_PATH . '/html/' . $file . '.html';
    } else {
        switch($file){
            case 'swfupload':
            $path = PL_PATH.'/swfupload/index.php';
        }
    }

    if(file_exists($path)){
        ob_start();
        include $path;
        $content = ob_get_clean();
        $view = new View();
        $content = $view->replaceContent($content);
        return $content;
    } else {
        errorPage('no page','没有找到'.$path.'这个模板');
    }
}

/**
 * 生成缩略图
 * @param string     源图绝对完整地址
 * @param string     目标图绝对完整地址
 * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
 * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
 * @param int        是否裁切{宽,高必须非0}
 * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
 * @return boolean
 */
function thumb($src_img, $dst_img = '', $width = 120, $height = 90, $cut, $proportion = '',$x = '', $y = '')
{
    $img = new Image($src_img,$dst_img);//cut == 1 时可以自动居中处理
    return $img->thumbImage($width,$height,$cut,$proportion,$x,$y);
}

function expendModel($model){
    $model = EXPEND_PATH.DIRECTORY_SEPARATOR.$model;
    $model_path = is_dir($model) ? $model : dirname($model);
    $o_model = opendir($model_path);
    while($file_name = readdir($o_model)){
        $i_path = $model_path.DIRECTORY_SEPARATOR.$file_name;
        if(!is_dir($i_path)){
            include $i_path;
        }
    }
}

function JSON($data){
    $str = json_encode($data);
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
        $str);
}

function webLongEcho($str){
    echo $str;
    echo str_repeat(' ',300);
    @ob_flush();@flush();
}

function reIndexArray($array,$key){
    foreach($array as $item){
        if(isset($item[$key]))
            $data[$item[$key]] = $item;
    }
    return $data;
}