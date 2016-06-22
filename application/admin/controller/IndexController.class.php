<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-17
 * Time: 下午3:54
 */

class IndexController extends AdminController {

    function index(){
        $this->assign('admin_menu',C('ADMIN_MENU'));
        $this->assign('sub_menu',C('ADMIN_SUB_MENU'));
        $this->display();
    }

    function home(){
        echo '欢迎光临';
    }

} 