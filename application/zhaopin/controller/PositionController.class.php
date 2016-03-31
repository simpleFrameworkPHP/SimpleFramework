<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午8:44
 */

class PositionController extends BaseController {

    public function index(){
        $where = $this->getWhere();
        $model = M('zhaopin/MPosition',0);
        $data = $model->where($where)->limit(1000)->select();
        if(!empty($data)){
            $data = $this->showData($data);
        }

        $this->display();
    }

    public function getWhere(){
        if($_REQUEST['city']){
            $where['city'] = array('like',"%{$_REQUEST['city']}%");
        }
        if($_REQUEST['position']){
            $where['position_name'] = array('like',"%{$_REQUEST['position']}%");
        }
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $this->assign('where',$_REQUEST);
        return $where;
    }

    public function showData($data){
        $columns = array();
        foreach($data as $item){
            $position_type_ids[] = $item['position_type_id'];
            $company_ids[] = $item['company_id'];
        }
        $DicPT = M('zhaopin/DicPositionType',0);
        $position_types = $DicPT->getPNameListInId($position_type_ids);
        $DicC = M('zhaopin/MCompany',0);
        $company_list = $DicC->getCNameListInId($company_ids);
        foreach($data as $key => $item){
            $row = array();
            $row['序号'] = $key;
            $row['职位名称'] = $item['position_name'];
            $row['城市'] = $item['city'];
            $row['职位类型'] = $position_types[$item['position_type_id']];
            $row['薪酬范围'] = $item['salary'];
            $row['工作年限'] = $item['work_year'];
            $row['学历'] = $item['education'];
            $row['创建时间'] = $item['create_time'];
            $row['公司'] = $company_list[$item['company_id']];
            $row['职位优势'] = $item['position_advantage'];
            $row['职位详情'] = "<a href='".CommonController::getUrl($item['position_id'],$item['data_from'])."'>点击查看详情</a>";
            $row['职位分析'] = "<a href='".H('zhaopin/position/parsing',array('position_id'=>$item['position_id']))."'>分析详情</a>";
//            $row['工作类型'] = $item['job_nature'];
//            $row['直属领导'] = $item['leader_name'];
            $list[$key] = $row;
        }
        $columns = array_keys(current($list));
        $this->assign('columns',$columns);
        $this->assign('data',$list);
    }

    public function parsing(){
        $mposition = M('zhaopin/MPosition',0);
        if($_REQUEST['position_id']){
            $data = $mposition->where(array('position_id'=>$_REQUEST['position_id']))->find();
            print_r($data);
        }
    }
} 