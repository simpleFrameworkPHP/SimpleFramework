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
            $data[$key]['task'] = S('TASK_'.$key.'/task');
        }
        $this->assign('data',$data);
        $this->display();
    }

    public function addUser(){
        $taskUser = S('TASK/TaskUsers');
        $taskUser['user'][$_POST['key']] = $_POST['user'];
        S('TASK/TaskUsers',$taskUser,0);
        header('Location: '.H('showUsers'));
    }

    public function addTask(){
        $task = S('TASK_'.$_POST['key'].'/task');
        $task[] = array('name'=>$_POST['task_name'],'remark'=>$_POST['task_remark'],'end_time'=>strtotime($_POST['end_date']),'start_time'=>strtotime($_POST['start_date']));
        S('TASK_'.$_POST['key'].'/task',$task,0);
        header('Location: '.H('showUsers'));
    }

    public function editTask(){
        $task = S('TASK_'.$_POST['key'].'/task');
        foreach($_POST['finish'] as $value){
            $task[$value]['finish'] = true;
        }
        S('TASK_'.$_POST['key'].'/task',$task,0);
        header('Location: '.H('showUsers'));
    }
} 