<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-17
 * Time: 下午4:38
 */

class HomeController extends Controller {

    function login(){
        if($_POST){
            $where['username'] = $_POST['username'];
            $where['password'] = $_POST['password'];
            $user = M('admin/User')->login($where);
            if($user['id']){
                redirect(H('admin/index/index'));
            } else {
                echo '用户名或密码错误';
            }
        }
        $this->display();
    }

} 