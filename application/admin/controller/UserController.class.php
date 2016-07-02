<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-10-13
 * Time: 上午9:43
 */

class UserController extends AdminController {

    public function index(){
        $data = M('User')->select();
        $this->assign('data',$data);
        $this->display('index');
    }

    public function add(){
        if(!empty($_POST)){
            if($_POST['user_name']){
                $username = trim($_POST['user_name']);
                $password = trim($_POST['password']);
                $nike_name = trim($_POST['nike_name']);
                $result = M('admin/User')->addUser($username,$password,$nike_name);
                if($result){
                    $this->index();
                } else {
                    $this->display();
                }
            }
        } else {
            $this->display();
        }
    }

    public function edit(){
        if(!empty($_POST)){
            $where['id'] = intval($_POST['id']);
            if($where['id']){
                $data['user_name'] = trim($_POST['user_name']);
                if(trim($_POST['password']) != ''){
                    $data['password'] = trim($_POST['password']);
                }
                $data['nike_name'] = trim($_POST['nike_name']);
                $result = M('User')->where($where)->set($data);
                if($result){
                    $this->index();
                } else {
                    $data = M('User')->where($where)->find();
                    $this->assign('data',$data);
                    $this->display('add');
                }
            }
        } else {
            $where['id'] = intval($_GET['id']);
            $data = M('User')->where($where)->find();
            $this->assign('data',$data);
            $this->display('add');
        }
    }

    public function delete(){

    }
} 