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
        $category_arr = M('Category')->getAllCategory();
        $category = array();
        foreach($category_arr as $row){
            $category[$row['id']] = $row['category_name'];
        }
        $this->assign('category',$category);
        $this->display('index');
    }

    public function add(){
        $result = $id = 0;
        if(!empty($_POST)){
            if($_POST['title']){
                $data['title'] = trim($_POST['title']);
                $data['digest'] = trim($_POST['digest']);
                $data['category_id'] = intval($_POST['category_id']);
                $data['template_id'] = intval($_POST['template_id']);
                $data['add_time'] = date('Y-m-d H:i:s');
                $data['cn_status'] = 1;
                $result = M('Content')->add($data);
                if($result){
                    $value['con_id'] = $result;
                    $value['con_value'] = trim($_POST['content']);
                    $id = M('ContentValue')->add($value);
                    //附件溯源
                    $this->returnAttach($value['con_value'],$value['con_id'],'document');
                    if($id){
                        $this->index();
                    }
                }
            }
        }
        if(empty($_POST) || !$result || !$id){
            $category = M('Category')->getAllCategory();
            $this->assign('category',$category);
            $this->display('add');
        }
    }

    public function edit(){
        $where['id'] = $wheres['con_id'] = intval($_GET['id']);
        if(!empty($_POST)){
            if($_POST['title']){
                $where['id'] = $wheres['con_id'] = intval($_POST['id']);
                $data['title'] = trim($_POST['title']);
                $data['digest'] = trim($_POST['digest']);
                $data['category_id'] = intval($_POST['category_id']);
                $data['template_id'] = intval($_POST['template_id']);
                $data['add_time'] = date('Y-m-d H:i:s');
                $data['cn_status'] = 2;
                $result = M('Content')->where($where)->set($data);
                $datas['con_value'] = trim($_POST['content']);
                $results = M('ContentValue')->where($wheres)->set($datas);
                //附件溯源
                $this->returnAttach($datas['con_value'],$wheres['con_id'],'document');
                if($result){
                    $this->index();
                } else {
                    $category = M('Category')->getAllCategory();
                    $this->assign('category',$category);
                    $this->display();
                }
            }
        } else {
            $data = M('Content')->where($where)->find();
            $content = M('ContentValue')->where($wheres)->find();
            $data['content'] = $content['con_value'];
            $this->assign('data',$data);
            $category = M('Category')->getAllCategory();
            $this->assign('category',$category);
            $this->display('add');
        }
    }

    public function returnAttach($content,$from_id,$type){
        expendModel('htmlTree');
        $dom = str_get_html($content);
        foreach($dom->find("img") as $find){
            $attach_list[] = $find->src;
        }
        $ids = array();
        if(count($attach_list) > 0){
            $where['file_path'] = array('in',$attach_list);
            $where['from_id'] = array('IS NULL');
            $model = M('Attachment');
            $ids = $model->where($where)->fields('id')->select();
        }
        if(!empty($ids)){
            $ids = array_keys(reIndexArray($ids,'id'));
            $where_set['id'] = array('in',$ids);
            $set['from_id'] = $from_id;
            $set['file_from'] = $type;
            M('Attachment')->where($where_set)->set($set);
        }
    }

    /**
     * 发布流程
     * 添加文章id是为了后期批量发布和定时发布做准备
     * @param int $id
     */
    public function publish($id = 0){
        $where['id'] = $id == 0 ? intval($_GET['id']) : $id ;
        $content = M('Content')->where($where)->find();
        $where_value['con_id'] = $where['id'];
        $content_value = M('ContentValue')->where($where_value)->find();
        $content['content'] = $content_value['con_value'];
        $where_cate['id'] = $content['category_id'];
        $cate = M('Category')->where($where_cate)->find();
        $content['add_path'] = date('/Y/m/d/',strtotime($content['add_time']));
        $content['add_path'] .= mb_strcut(md5($content['add_time'].$content['id']).'.html',-15);
        $document_path = '/' . trim($cate['category_str'] , '/') . $content['add_path'];
        $path = DATA_PATH . $document_path;
        $temp_info = M('Template')->where(array('id'=>$content['template_id']))->find();
        $template_file = 'abc';$content['template_id'];
        $this->assign('cate',$cate);
        $this->assign('document',$content);
        $content = $this->initHtml($temp_info['temp_file'],'template/'.$temp_info['temp_theme']);
        // 模板阵列变量分解成为独立变量
        extract($this->params, EXTR_OVERWRITE);
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        eval('?>'.$content);
        // 获取并清空缓存
        $content = ob_get_clean();
        addDir($path);
        file_put_contents($path,$content);
        $data['cn_status'] = 99;
        $data['path'] = $document_path;
        $result = M('Content')->where($where)->set($data);
        //添加日志
//        $log['type'] = 'create_content';
//        $log['remark'] = $data['path'];
//        M('SystemLog')->add($log);
        if($result){
            $json_str = array('error_code'=>0);
        } else {
            $json_str = array('error_code'=>1);
        }
        echo json_encode($json_str);
    }

    public function delete(){
        $where['id'] = intval($_GET['id']);
        $data['cn_status'] = 0;
        $result = M('Content')->where($where)->set($data);
        if($result){
            $this->index();
        }
    }
}