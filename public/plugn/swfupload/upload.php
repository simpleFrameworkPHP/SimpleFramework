<?php
$path = str_replace('\\','/',getcwd());
$path = explode('/',$path);
unset($path[count($path)-1]);
unset($path[count($path)-1]);
unset($path[count($path)-1]);
$path = implode('/',$path);
define("__PATH__",$path);
include_once(__PATH__.'/core/start.php');
if(empty($charset))  $charset = C('DEFAULT_CHARSET');
if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
// 网页字符编码
@header('Content-Type:'.$contentType.'; charset='.$charset);
// Code for Session Cookie workaround
if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
} else if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
}

session_start();

$upload = new Upload();
$data = $upload->uploadFile('swfload');
if($data['status'] == 200){
    echo 'File Received';
    //存储文件(流程待修改)
    M('home/Attach')->addFile($data['data']['save_path'],$data['data']['file_name'],'swfuploader');
} else {
    HandleError($data);
}
//$POST_MAX_SIZE = ini_get('post_max_size');
//$unit = strtoupper(substr($POST_MAX_SIZE, -1));
//$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
//
//if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
//    header("HTTP/1.1 500 Internal Server Error");
//    echo "POST exceeded maximum allowed size.";
//    exit(0);
//}
//
//// Settings
//if(!$_GET['t']){
//    $type = 'image';
//} else {
//    $type = $_GET['t'];
//}
//$save_path = __PATH__ . '/data/'.$type.'/'.date('Y/m/d/');
//addDir($save_path);
//$upload_name = "Filedata";
//$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
//$extension_whitelist = array("doc", "txt", "jpg", "gif", "png","JPG");	//允许的文件
//$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				//允许的文件名字符
//
//// Other variables
//$MAX_FILENAME_LENGTH = 260;
//$file_name = "";
//$file_extension = "";
//$uploadErrors = array(
//    0=>"文件上传成功",
//    1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置",
//    2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置",
//    3=>"上传的文件仅为部分文件",
//    4=>"没有文件上传",
//    6=>"缺少临时文件夹"
//);
//
//if (!isset($_FILES[$upload_name])) {
//    HandleError("No upload found in \$_FILES for " . $upload_name);
//    exit(0);
//} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
//    HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
//    exit(0);
//} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
//    HandleError("Upload failed is_uploaded_file test.");
//    exit(0);
//} else if (!isset($_FILES[$upload_name]['name'])) {
//    HandleError("File has no name.");
//    exit(0);
//}
//
//$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
//if (!$file_size || $file_size > $max_file_size_in_bytes) {
//    HandleError("File exceeds the maximum allowed size");
//    exit(0);
//}
//
//if ($file_size <= 0) {
//    HandleError("File size outside allowed lower bound");
//    exit(0);
//}
//
//
//$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
//if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
//    HandleError("Invalid file name");
//    exit(0);
//}
//
//
//if (file_exists($save_path . $file_name)) {
//    HandleError("File with this name already exists");
//    exit(0);
//}
//
//// Validate file extension
//$path_info = pathinfo($_FILES[$upload_name]['name']);
//$file_extension = $path_info["extension"];
//$is_valid_extension = false;
//foreach ($extension_whitelist as $extension) {
//    if (strcasecmp($file_extension, $extension) == 0) {
//        $is_valid_extension = true;
//
//        break;
//    }
//}
//if (!$is_valid_extension) {
//    HandleError("Invalid file extension");
//    exit(0);
//}
//
//
//if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
//    HandleError("文件无法保存:".$save_path.$file_name);
//    exit(0);
//}else{
//
//}

//echo "File Received";
//exit(0);


function HandleError($message) {
    header("HTTP/1.1 500 Internal Server Error");
    $str = date('Y-m-d H:i:s').$message['message']."\r\n";
    $str .= 'max_execution_time = ' . ini_get('max_execution_time')."\r\n";
    $str .=  'memory_limit = ' . ini_get('memory_limit')."\r\n";
    $str .=  'upload_max_filesize = ' . ini_get('upload_max_filesize')."\r\n";
    $str .=  'post_max_size = ' . ini_get('post_max_size') . "/n";
    $str .=  'post_max_size+1 = ' . (ini_get('post_max_size')+1) . "/n";
    $str .=  'post_max_size in bytes = ' . return_bytes(ini_get('post_max_size'))."\r\n";
    Log::write('UPLOAD ERROR',$str,'file');
    echo $message['message'];
}
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
?>