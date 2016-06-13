<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-15
 * Time: 下午3:05
 */

class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    public function login(){
        if($_POST){
            $where['username'] = $_POST['username'];
            $where['password'] = $_POST['password'];
            $user = M('admin/User')->login($where);
            if($user['id']){
                redirect(H('home/index/index'));
            } else {
                echo '用户名或密码错误';
            }
        }
        $this->display();
    }

    public function register(){

    }

    public function logout(){
        Log::write('LOGOUT',$_SESSION['username'].'logout ','login');
        M('admin/User')->logout();
    }
} 