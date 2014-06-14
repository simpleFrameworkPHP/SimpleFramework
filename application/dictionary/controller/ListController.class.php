<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:52
 */

class ListController extends Controller {

    public function index(){
        if(!isset($_REQUEST['t'])){
            $_REQUEST['t'] =  'all';
        }
        M('',1)->table('zl_document')->where(array('content'=>' '))->select();
        $this->display();
    }
} 