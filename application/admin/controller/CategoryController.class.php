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
        $this->display('index');
    }

    public function add(){
        if(!empty($_POST)){
            if($_POST['category_name']){
                $data['category_name'] = trim($_POST['category_name']);
                $data['category_sid'] = intval($_POST['category_sid']);
                $data['category_str'] = trim($_POST['category_str']);
                $result = M('Category')->add($data);
                if($result){
                    $this->index();
                } else {
                    $category = M('Category')->getFristCategory();
                    $this->assign('category',$category);
                    $this->display();
                }
            }
        } else {
            $category = M('Category')->getFristCategory();
            $this->assign('category',$category);
            $this->display();
        }
    }

    public function delete(){
        $where['id'] = intval($_GET['id']);
        $result = M('Category')->where($where)->delete();
        if($result){
            $this->index();
        }
    }
}