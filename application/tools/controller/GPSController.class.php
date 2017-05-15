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
        $client_ip = '123.103.9.9';
        $url = 'http://api.map.baidu.com/location/ip?ak=omi69HPHpl5luMtrjFzXn9df&ip='.$client_ip.'&coor=bd09ll';
        $content = CURL::get($url);
        $content = json_decode($content,true);
        $this->assign('point',array($content['content']['point']));
        print_r('百度地图示例');
        print_r($content);echo "<hr/>";

        $url = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=".$content['content']['point']['x']."&y=".$content['content']['point']['y'];
        $content = CURL::get($url);
        $content = json_decode($content,true);
        $content['x'] = base64_decode($content['x']);
        $content['y'] = base64_decode($content['y']);
        print_r($content);
        $this->assign('point',array($content));
        $this->display('gps/index');
    }

    public function thumb(){
        $this->display();
    }
} 