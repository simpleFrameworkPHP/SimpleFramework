<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-8-31
 * Time: 下午4:36
 */

class ImageInfoController extends Controller {

    public function getImageGPS(){
        $p = $_GET['path']?$_GET['path']:'2014/10/08/';
        $path = DATA_PATH.'/image/'.$p;
        $url = './data/image/'.$p;
        $files = glob($path.'*');
        if(count($files)){
            foreach($files as $file){
                $image = new Image($file);
                $ipoint = $image->getGPSInfo();
                if($ipoint){
                    $ipoint['path'] = $url.basename($file);
                    $point[$image->info['FILE']['FileDateTime']] = $ipoint;
                }
            }
        }
        ksort($point);
        echo json_encode($point);
    }

    public function getGPSInfo(){
        $p = $_GET['path'];
        $path = DATA_PATH.'/image/'.$p;
        $url = './data/image/'.$p;print_r($path);
        $files = glob($path.'*.*');
        $point = array();
        if(count($files)){
            foreach($files as $file){
                $image = new Image($file);
                $ipoint = $image->getGPSInfo();
                if($ipoint){
                    $ipoint['path'] = $url.basename($file);
                    $ipoint['time'] = $image->info['FileDateTime'];
                    $point[] = $ipoint;
                }
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
