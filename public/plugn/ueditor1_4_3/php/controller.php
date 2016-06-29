<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
//==添加后台
$_REQUEST['a'] = 'admin';
define("__PATH__",str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once(__PATH__.'/core/start.php');
$conf = C('EDITOR_CONF');
if(!empty($conf)){
    foreach($conf as $key=>$value){
        $CONFIG[$key] = $value;
    }
}
defined('PATH_EXP') or define('PATH_EXP',$self[array_search('public',$self) - 1]);//相对路径获取
$CONFIG['imagePathFormat'] = "/" . PATH_EXP . $CONFIG['imagePathFormat'];

$action = $_GET['action'];

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
//    {"state":"SUCCESS","url":"\/data\/image\/2016\/06\/28\/1467094364194202.jpg","title":"1467094364194202.jpg","original":"98.jpg","type":".jpg","size":39520}
        $info = json_decode($result,true);
        if(!empty($info) && isset($info['url'])){

        }
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}