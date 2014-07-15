<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-15
 * Time: 下午3:05
 */

class IndexController extends Controller {

    public function index(){
        $user_model = M('admin/User');
        $user_model->addUser();
        $this->display();
    }

    public function login(){

    }

    public function register(){

    }

    public function logout(){
        Log::write('LOGOUT',$_SESSION['username'].'logout ','login');
        logout();
    }
} 