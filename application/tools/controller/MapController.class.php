<?php

/**
 * Project:     单点登录
 * File:        .php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package 
 * @subpackage 
 * @author 李晨阳 <lichenyang@soufun.com>
 * @copyright 2014 Soufun, Inc.
 */
class MapController extends Controller{
    
    public function index() {
        $this->display('tools/map/index');
    }
    
    public function markPost(){
        $pointArr = array(
            0 => array(
                'x'=>'116.448142',
                'y'=>'39.956935'
            ),
            1 => array(
                'x'=>'116.325685',
                'y'=>'39.902492'
            )
        );
        $pointjson = json_encode($pointArr);
        var_dump($pointjson);
        $this->assign('pointjson', $pointjson);
        $this->display('tools/map/markpost');
    }
}
