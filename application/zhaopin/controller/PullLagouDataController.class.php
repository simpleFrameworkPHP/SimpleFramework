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
        lagou::addData();
    }

    public function initData(){
        $today = CommonController::getLastLogTime('view_lagou_position');
        lagou::initData($today);
    }
} 