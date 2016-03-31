<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/19
 * Time: 上午10:55
 */

class PullLagouDataController extends BaseController {

    public function index(){
        $this->display();
    }

    public function addData(){
        $citys = M('zhaopin/DicArea',0)->fields('area_name')->where(array('type_name'=>'市'))->select();
        foreach($citys as $city){
            $i_city = str_replace('市','',$city['area_name']);
            webLongEcho("|$i_city 数据处理中。。。。");
            addData($i_city);
        }
    }

    public function initData(){
        $today = CommonController::getLastLogTime('view_lagou_position');
        initData($today);
    }
} 