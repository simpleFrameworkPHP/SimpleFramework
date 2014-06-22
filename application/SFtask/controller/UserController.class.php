<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-6-22
 * Time: 10:07
 */

class UserController extends Controller {

    public function showUsers(){
        $this->display();
    }

    public function addUsers(){
        $taskUser = S('TaskUsers');
        $taskUser[$_REQUEST['key']] = $_REQUEST['user'];
        S('TaskUsers',$taskUser);
        $this->assign('taskUser',$taskUser);
        $this->display();
    }
} 