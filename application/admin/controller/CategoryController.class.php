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
        $data = M('Category')->getListByPage(array('cate_status'=>1));
        $this->assign('data',$data);
        $this->display('index');
    }

    public function add(){
        if(!empty($_POST)){
            if($_POST['category_name']){
                $data['category_name'] = trim($_POST['category_name']);
                $data['category_sid'] = intval($_POST['category_sid']);
                $data['category_str'] = trim($_POST['category_str']);
                $data['def_template'] = trim($_POST['def_template']);
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

    public function set(){
        if(!empty($_POST)){
            $where['id'] = intval($_POST['id']);
            if($where['id']){
                $data['category_name'] = trim($_POST['category_name']);
                $data['category_sid'] = intval($_POST['category_sid']);
                $data['category_str'] = trim($_POST['category_str']);
                $data['def_template'] = trim($_POST['def_template']);
                $result = M('Category')->where($where)->set($data);
                if($result){
                    $this->index();
                } else {
                    $where['id'] = intval($_GET['id']);
                    $category = M('Category')->getFristCategory();
                    $this->assign('category',$category);
                    $data = M('Category')->where($where)->find();
                    $this->assign('data',$data);
                    $this->display('add');
                }
            }
        } else {
            $where['id'] = intval($_GET['id']);
            $category = M('Category')->getFristCategory();
            $this->assign('category',$category);
            $data = M('Category')->where($where)->find();
            $this->assign('data',$data);
            $this->display('add');
        }

    }

    public function delete(){
        $where['id'] = intval($_GET['id']);
        $data['cate_status'] = 0;
        $result = M('Category')->where($where)->set($data);
        if($result){
            $this->index();
        }
    }
}