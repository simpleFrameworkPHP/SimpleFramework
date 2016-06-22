<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/6
 * Time: 16:26
 */
class ContentController extends AdminController
{
    public function index(){
        $data = M('Content')->getListByPage();
        $this->assign('data',$data);
        $con_status = M('Dic')->getDicByType('content_status');
        $this->assign('con_status',$con_status);
        $this->display('index');
    }

    public function add(){
        if(!empty($_POST)){
            if($_POST['title']){
                $data['title'] = trim($_POST['title']);
                $data['digest'] = trim($_POST['digest']);
                $data['content'] = trim($_POST['content']);
                $data['category_id'] = intval($_POST['category_id']);
                $data['template_id'] = intval($_POST['template_id']);
                $data['add_time'] = date('Y-m-d H:i:s');
                $data['cn_status'] = 1;
                $result = M('Content')->add($data);
                if($result){
                    $this->index();
                } else {
                    $category = M('Category')->getAllCategory();
                    $this->assign('category',$category);
                    $this->display();
                }
            }
        } else {
            $category = M('Category')->getAllCategory();
            $this->assign('category',$category);
            $this->display('add');
        }
    }

    public function edit(){
        if(!empty($_POST)){
            if($_POST['title']){
                $where['id'] = intval($_POST['id']);
                $data['title'] = trim($_POST['title']);
                $data['digest'] = trim($_POST['digest']);
                $data['content'] = trim($_POST['content']);
                $data['category_id'] = intval($_POST['category_id']);
                $data['template_id'] = intval($_POST['template_id']);
                $data['add_time'] = date('Y-m-d H:i:s');
                $data['cn_status'] = 1;
                $result = M('Content')->where($where)->set($data);
                if($result){
                    $this->index();
                } else {
                    $category = M('Category')->getAllCategory();
                    $this->assign('category',$category);
                    $this->display();
                }
            }
        } else {
            $category = M('Category')->getAllCategory();
            $this->assign('category',$category);
            $this->display('add');
        }
    }
}