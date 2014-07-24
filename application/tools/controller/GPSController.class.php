<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午4:59
 */

class GPSController extends Controller {

    public function getClient(){
//        $client_ip = getClientIp();print_r($client_ip);
        $client_ip = '203.208.49.180';
        $url = 'http://api.map.baidu.com/location/ip?ak=omi69HPHpl5luMtrjFzXn9df&ip='.$client_ip.'&coor=bd09ll';
        $content = CURL::get($url);
        print_r('百度地图示例');
        print_r(json_decode($content,true));
    }

    public function thumb(){
        $this->display();
    }
} 