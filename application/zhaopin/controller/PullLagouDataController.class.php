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
        addData('北京');
        addData('大连');
        addData('杭州');
    }

    public function initData(){
        $today = $this->getLastLogTime('view_lagou_position');
        initData($today);
    }
} 