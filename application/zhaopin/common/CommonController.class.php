<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/31
 * Time: 下午1:40
 */

class CommonController extends Controller {

    public static function getLastLogTime($type){
        $time = date('Y-m-d');
        $data = M('DataLog',0)->getLastLog($type);
        if(!empty($data)){
            $time = $data['content'];
        }
        return $time;
    }

    public static function getUrl($pid,$from_type){
        $url = '';
        switch($from_type){
            case 'lagou':$url = "http://www.lagou.com/jobs/".$pid.".html";break;
        }
        return $url;
    }
} 