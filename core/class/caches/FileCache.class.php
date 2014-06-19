<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-12
 * Time: 上午10:55
 */
class FileCache extends Cache {

    public $cache_path = '';
    public $file_time = '';

    public function __construct($cache_path,$file_time){
        $this->cache_path = $cache_path;
        $this->file_time = $file_time;
    }

    /**
     * @param $key 缓存键值
     */
    public function getParam($key,$type = 'system'){
        $result = false;
        $path = $this->cache_path.'/'.$type.'.ch';
        if(file_exists($path)){
            $json = getFileContent($path);
            $result = json_decode($json,true);
            if($result){
                if(!isset($result[$key])){
                    $result = false;
                } elseif($result['end_time']<nowTime()){
                    //缓存文件数据过期
                    removeFile($path);
                } else {
                    //缓存文件数据未过期
                    if($result[$key]['end_time']<nowTime()){
                        //缓存数据过期，清除数据
                        $this->removeParam($key,$type);
                    } else {
                        $result = $result[$key]['data'];
                    }
                }
            }
        }
        return $result;
    }

    public function setParam($key,$data,$type = 'system',$cache_time = ONE_DAY){
        $save_data = array('end_time'=>nowTime()+ONE_DAY*7,$key=>array('end_time'=>nowTime()+$cache_time,'data'=>$data));
        $path = $this->cache_path.'/'.$type.'.ch';
        if(file_exists($path)){
            $file_data = json_decode(getFileContent($path),true);
            $save_data = array_merge($file_data,$save_data);
        }
        $this->writeParam($path,json_encode($save_data));
    }

    function writeParam($file,$content){
        if(!file_exists($file)){
            //创建缓存文件
            addDir($file);
        }
        file_put_contents($file,$content);
    }

    public function removeParam($key,$type){
        $result = null;
        $path = $this->cache_path.'/'.$type.'.ch';
        if(file_exists($path)){
            $json = getFileContent($path);
            $result = json_decode($json,true);
            //优化--该处时间获取可以优化
            unset($result[$key]);
            file_put_contents($path,json_encode($result));
        }
    }

    public function clearCache(){
        removeDir(dirname($this->cache_path));
    }
}