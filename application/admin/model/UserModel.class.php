<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-15
 * Time: 下午3:49
 */

class UserModel extends Model {

    var $var_table = array('user'=>'n_user');

    public function addUser($user_name,$password,$nike_name = ''){
        $add['user_name'] = $user_name;
        $add['password'] = $this->getPass($password);
        $add['nike_name'] = $nike_name;
        $uid = $this->add($add);
        return $uid;
    }

    public function setUser(){

    }

    public function login($where,$admin = 0){
        $map['user_name'] = $where['username'];
        $map['password'] = $this->getPass($where['password']);
        $map['user_status'] = 1;
        if(intval($admin)){
            //管理员登录
            $map['role_id'] = intval($admin);
        }
        $user = $this->where($map)->find();
        if(isset($user['id'])){
            $_SESSION['uid'] = $user['id'];
            $_SESSION['nike_name'] = $user['nike_name'];
            if(intval($admin)){
                $_SESSION['admin'] = $user['role_id'];
            }
        }
        return $user;
    }

    public function logout(){
        logout();
    }

    private function getPass($password){
        return md5($password.'note');
    }
} 