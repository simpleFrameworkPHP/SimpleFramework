<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/28
 * Time: 10:03
 */
class AttachmentController extends AdminController
{

    public function index(){
        $data = M('Attachment')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function ueditorAdd($info){
        $id = 0;
        if(isset($info['state']) && $info['state'] == 'SUCCESS'){
            $data = array();
            $data['file_path'] = $info['url'];
            $data['file_name'] = $info['original'];
            $data['file_type'] = trim($info['type'],'.');
            $id = M('Attachment')->add($data);
        }
        return $id;
    }
}