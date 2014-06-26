<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-6-22
 * Time: 10:07
 */

class UserController extends Controller {

    public function showUsers(){
        $user = S('TASK/TaskUsers');
        foreach($user['user'] as $key=>$value){
            $data[$key]['name'] = $value;
        }
        $this->assign('data',$data);
        $this->display();
    }

    public function addUser(){
        $taskUser = S('TASK/TaskUsers');
        $taskUser['user'][$_POST['key']] = $_POST['user'];
        S('TASK/TaskUsers',$taskUser);
        $this->showUsers();
    }
} 