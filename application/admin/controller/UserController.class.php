<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-10-13
 * Time: 上午9:43
 */

class UserController extends AdminController {

    public function selectUser(){
        $data = M('User')->select();
        $this->assign('data',$data);
        $this->display();
    }
} 