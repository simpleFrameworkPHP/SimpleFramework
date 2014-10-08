<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-8-31
 * Time: 下午4:36
 */

class ImageInfoController extends Controller {

    public function getGPSInfo(){
        $path = DATA_PATH.'/image/2014/10/08/';
        $url = './data/image/2014/10/08/';
        $dir = opendir($path);
        while($file = readdir($dir)){
            if(!in_array($file,array('.','..'))){
                $image = new Image($path.$file);
                $ipoint = $image->getGPSInfo();
                $ipoint['path'] = $url.$file;
                $ipoint['time'] = $image->info['FileDateTime'];
                $point[] = $ipoint;
            }
        }
        foreach($point as $key=>$value){
            foreach($point as $key1=>$value1){
                if($value['time'] > $value1['time']){
                    $i = $point[$key];
                    $point[$key] = $point[$key1];
                    $point[$key1] = $i;
                }
            }
        }
        $this->assign('point',$point);
        $this->display();
    }
} 