<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-8-31
 * Time: 下午4:36
 */

class ImageInfoController extends Controller {

    public function getGPSInfo(){
        $path = DATA_PATH.'/image/2014/08/31/';
        $url = './data/image/2014/08/31/';
        $dir = opendir($path);
        while($file = readdir($dir)){
            if(!in_array($file,array('.','..'))){
                $image = new Image($path.$file);
                $ipoint = $image->getGPSInfo();
                $ipoint['path'] = $url.$file;
                $point[] = $ipoint;
            }
        }
        $this->assign('point',$point);
        $this->display();
    }
} 