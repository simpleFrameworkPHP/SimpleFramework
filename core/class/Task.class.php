<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-24
 * Time: 下午2:30
 */

class Task {

    var $lock_file;
    var $last_time;
    var $next_time;
    var $i_time = 3600;
    var $task_name;
    var $task_list_path;


    public function run(){

    }

    public function getAllTask(){
        $task_array = array();
        if(is_dir(APP_PATH)){
            $app_dir = opendir(APP_PATH);
            while(($i_app = readdir($app_dir)) !== false) {
                $i_path = APP_PATH.'/'.$i_app.'/task/';
                if(is_dir(APP_PATH.'/'.$i_app) && is_dir($i_path)){
                    $i_task = opendir($i_path);
                    while(($i_file = readdir($i_task)) !== false){
                        if ( $i_file != '.' && $i_file != '..' && !is_dir($i_path.$i_file) ){
                            $name = explode('.',$i_file);
                            include $i_path.$i_file;
                            $i_class = new $name[0]();
                            $task_array[$name[0]] = array('file'=>$i_path.$i_file,'i_time'=>$i_class->getITime());
                        }
                    }
                }
            }
        }
        return $task_array;
    }

    public function getITime(){
        return $this->i_time;
    }
} 