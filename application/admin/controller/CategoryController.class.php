<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/6
 * Time: 11:22
 */
class CategoryController extends AdminController
{


    public function index(){
        $data = M('Category')->getListByPage();
        $this->assign('data',$data);
        $this->display();
    }
}