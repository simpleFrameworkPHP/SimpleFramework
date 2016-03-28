<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/19
 * Time: 上午10:55
 */

class PullLagouDataController extends BaseController {

    public function index(){
        $this->display();
    }

    public function addData(){
        addData();
//        print_r($json);
    }

    public function initData(){
        $today = date('H-m-d');
        initData($today);
    }

    public function showPosition(){
        $model = M('',0);
        if($_REQUEST['city']){
            $where['city'] = $_REQUEST['city'];
        }
        $where['city'] = "大连";
        $where['positionName'] = array("LIKE","%php%");
        $data = $model->table(array("view_lagou_position"))->where($where)->order(array('createTime'=>'DESC'))->select();

//        echo "<pre>";
//        print_r($data);exit;
        $columns = array_keys($data[0]);//array('id','positionId','workYear','createTime','positionName','companyShortName','positionAdvantage','salary','companyName','financeStage','industryField','companySize','companyLabelList');
        //array_keys($data[0]);
        foreach($data as $key => &$item){
            $item['positionId'] = "<a href='http://www.lagou.com/jobs/".$item['positionId'].".html'>点击查看</a>";
            $item['id'] = $key;
        }
        $this->assign('columns',$columns);
        $this->assign('data',$data);
        $this->display();
    }

} 