<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-15
 * Time: 下午3:49
 */

class UserModel extends Model {

    var $var_table = array('user'=>'sf_user');

    public function addUser(){
//        echo 'add';
    }

    public function setUser(){

    }

    public function login($where){
        $map['username'] = $where['username'];
        $map['password'] = $where['password'];
        $user = $this->where($map)->find();
        if($user['id']){
            $_SESSION['uid'] = $user['id'];
        }
        return $user;
    }
} 