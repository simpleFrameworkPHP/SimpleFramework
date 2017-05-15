<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-29
 * Time: 下午2:05
 */

class Upload {

    public function Upload(){
        return $this;
    }
//判断是否登录
    function islogin(){
        if(!$_SESSION['uid']){
            //没有登录
            $data = array('status'=>500,'message'=>'no login!','data'=>array());
        } else {
            $data = array();
        }
        return $data;
    }

    function masSize(){
        $POST_MAX_SIZE = ini_get('post_max_size');
        $unit = strtoupper(substr($POST_MAX_SIZE, -1));
        $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

        if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
            $data = array('status'=>500,'message'=>'POST exceeded maximum allowed size.','data'=>array());
        }
        return $data;
    }

    function setting($file_model){
        $this->save_path = __PATH__ . '/data/'.$file_model.'/'.date('Y/m/d/');
        addDir($this->save_path);
        $this->upload_name = "Filedata";
        $this->max_file_size_in_bytes = 2147483647;				// 2GB in bytes
        $this->extension_whitelist = array("doc", "txt", "jpg", "gif", "png","JPG");	//允许的文件
        $this->valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				//允许的文件名字符
        $this->MAX_FILENAME_LENGTH = 260;
    }

    function settingError(){
        if (!isset($_FILES[$this->upload_name])) {
            return array('status'=>500,'message'=>"No upload found in \$_FILES for " . $this->upload_name,'data'=>array());
        } else if (isset($_FILES[$this->upload_name]["error"]) && $_FILES[$this->upload_name]["error"] != 0) {
            return array('status'=>500,'message'=>$this->uploadErrors[$_FILES[$this->upload_name]["error"]],'data'=>array());
        } else if (!isset($_FILES[$this->upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$this->upload_name]["tmp_name"])) {
            return array('status'=>500,'message'=>"Upload failed is_uploaded_file test.",'data'=>array());
        } else if (!isset($_FILES[$this->upload_name]['name'])) {
            return array('status'=>500,'message'=>"File has no name.",'data'=>array());
        }
        $file_size = @filesize($_FILES[$this->upload_name]["tmp_name"]);
        if (!$file_size || $file_size > $this->max_file_size_in_bytes) {
            return array('status'=>500,'message'=>"File exceeds the maximum allowed size",'data'=>array());
        }

        if ($file_size <= 0) {
            return array('status'=>500,'message'=>"File size outside allowed lower bound",'data'=>array());
        }
    }

    public function uploadFile($model){
        //实现上传流程
        $data = $this->islogin();
        if($data['status'] == '500'){
            return $this->returnData($data,$model);
        }
        $data = $this->masSize();
        if($data['status'] == '500'){
            return $this->returnData($data,$model);
        }

// Settings
        if(!$_GET['t']){
            $type = 'image';
        } else {
            $type = $_GET['t'];
        }
        $this->setting($type);
// Other variables
        $this->uploadErrors = array(
            0=>"文件上传成功",
            1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置",
            2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置",
            3=>"上传的文件仅为部分文件",
            4=>"没有文件上传",
            6=>"缺少临时文件夹"
        );
        $data = $this->settingError();
        if($data['status'] == '500'){
            return $this->returnData($data,$model);
        }

        $file_name = preg_replace('/[^'.$this->valid_chars_regex.']|\.+$/i', "", basename($_FILES[$this->upload_name]['name']));
        if (strlen($file_name) == 0 || strlen($file_name) > $this->MAX_FILENAME_LENGTH) {
            return $this->returnData(array('status'=>500,'message'=>"Invalid file name",'data'=>array()),$model);
        }

        //存储文件名重写
        $file_name = explode('.',$file_name);
        $file_name = time().rand(0,99).'.'.$file_name[count($file_name) - 1];
        //注释掉文件名重复时返回错误信息
//        if (file_exists($this->save_path . $file_name)) {
//            return $this->returnData(array('status'=>500,'message'=>"File with this name already exists",'data'=>array()),$model);
//        }

// Validate file extension
        $path_info = pathinfo($_FILES[$this->upload_name]['name']);
        $file_extension = $path_info["extension"];
        $is_valid_extension = false;
        foreach ($this->extension_whitelist as $extension) {
            if (strcasecmp($file_extension, $extension) == 0) {
                $is_valid_extension = true;

                break;
            }
        }
        if (!$is_valid_extension) {
            return $this->returnData(array('status'=>500,'message'=>"Invalid file extension",'data'=>array()),$model);
        }

        if (!@move_uploaded_file($_FILES[$this->upload_name]["tmp_name"], $this->save_path.$file_name)) {
            $data = array('status'=>500,'message'=>"文件无法保存:".$this->save_path.$file_name,'data'=>array());
        }else{
            Log::write('UPLOAD SUCCESS',"文件已保存:".$this->save_path.$file_name,'file');
            Log::write('UPLOAD SUCCESS',"文件内容:".json_encode($_FILES),'file');
            $data = array('status'=>200,'message'=>"ok",'data'=>array('save_path'=>$this->save_path,'file_name'=>$file_name));
        }

        //实现数据回传整理
        return $this->returnData($data,$model);
    }

    function returnData($data,$model){
        switch($model){
            case 'swfload':$data = array('status'=>$data['status'],'message'=>$data['message'],'data'=>$data['data']);break;
            default:$data = array('status'=>$data['status'],'message'=>$data['message'],'data'=>$data['data']);
        }
        return $data;
    }
} 