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
            $_SESSION['uid'] = 123;
            redirect(H('admin/index/index'));
        } else {
            echo 'login';
        }
        $this->display();
    }

} 