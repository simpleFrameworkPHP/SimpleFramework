<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午8:44
 */

class PositionListController extends BaseController {

    var $db_num = 0;

    public function index(){
        $where = $this->getWhere();
        //获取最后一次抓取数据的时间
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $model = M('zhaopin/MPosition',$this->db_num);
        $data = $model->where($where)->limit(1000)->select();
        if(!empty($data)){
            $init_where['add_time'] = $where['add_time'];
            $this->showData($data,$init_where);
        }

        $this->display();
    }

    public function ajaxPT(){
        $pid = $_REQUEST['pid'];
        $pt = array();
        if(intval($pid)){
            $pt = M('zhaopin/DicPositionType',$this->db_num)->getPTListByPid($pid);
        }
        echo JSON($pt);
    }

    public function getWhere(){
        if($_REQUEST['city']){
            $where['city'] = array('like',"%{$_REQUEST['city']}%");
        }
        if($_REQUEST['position']){
            $where['position_name'] = array('like',"%{$_REQUEST['position']}%");
        }
        //公司条件搜索
        if($_REQUEST['company_name']){
            $c_where['company_name'] = array('like',"%{$_REQUEST['company_name']}%");
            $list = M('zhaopin/MCompany',$this->db_num)->fields('id')->where($c_where)->select();
            $cids = array();
            if(!empty($list)){
                $cids = reIndexArray($list,'id');
                $cids = array_keys($cids);
            }
            $where['company_id'] = array('in');
            $where['company_id'] = array_merge($where['company_id'],$cids);
        }
        $pft = M('zhaopin/DicPositionType',$this->db_num)->getPTListByPid();
        $this->assign('pft',$pft);
        $pid = current($pft)['id'];
        if($_REQUEST['position_first_type_id']){
            foreach($pft as $row){
                if($row['id'] == intval($_REQUEST['position_first_type_id'])){
                    $pid = $row['id'];
                }
            }
            $where['position_first_type_id'] = $_REQUEST['position_first_type_id'];
        }
        if($_REQUEST['position_type_id']){
            $where['position_type_id'] = $_REQUEST['position_type_id'];
        }
        $pt = M('zhaopin/DicPositionType',$this->db_num)->getPTListByPid($pid);
        $this->assign('pt',$pt);
        $this->assign('where',$_REQUEST);
        return $where;
    }

    public function showData($data,$init_where){
        foreach($data as $item){
            $position_type_ids[] = $item['position_type_id'];
            $position_type_ids[] = $item['position_first_type_id'];
            $company_ids[] = $item['company_id'];
        }
        $DicPT = M('zhaopin/DicPositionType',$this->db_num);
        $position_types = $DicPT->getPNameListInId($position_type_ids);
        $DicC = M('zhaopin/MCompany',0);
        $company_list = $DicC->getCNameListInId($company_ids);
        $init_where['company_id'] = array_merge(array('in'),$company_ids);
        $company_cp_list = M('zhaopin/MPosition',$this->db_num)->fields('company_id,count(company_id)',false)->where($init_where)->group('company_id')->select();
        $company_cp = array();
        foreach($company_cp_list as $row)
            $company_cp[$row['company_id']] = $row['count(company_id)'];
        foreach($data as $key => $item){
            $row = array();
            $row['序号'] = $key;
            $row['职位名称'] = "<a class='a_position' title='{$item['position_name']}' target='_black' href='".CommonController::getUrl($item['position_id'],$item['data_from'])."'>{$item['position_name']}</a>";
            $row['城市'] = $item['city'];
            $row['职位类型'] = $position_types[$item['position_first_type_id']]."-".$position_types[$item['position_type_id']];
            $class_str = $item['salary_id'] > 2 ? "class='red'" : '';
            $row['薪酬'] = "<span {$class_str}>".$item['salary']."</span>";
            $row['工作年限'] = $item['work_year'];
            $row['学历'] = $item['education'];
            $row['创建时间'] = date('Y-m-d',strtotime($item['create_time']));
            $class_str = $company_cp[$item['company_id']] > 5 ? "class='red a_position'" : "class='a_position'";
            $c_url = CommonController::getCompanyUrl($item['company_id'],$item['data_from']);
            $s_url = H('',array('company_name'=>$company_list[$item['company_id']]));
            $row['公司'] = "<a target='_black' title='{$company_list[$item['company_id']]}' {$class_str} href='{$c_url}'>".$company_list[$item['company_id']]."</a><a target='_black' {$class_str} href='{$s_url}'>({$company_cp[$item['company_id']]})</a>";
            $row['职位分析'] = "<a href='".H('zhaopin/position/parsing',array('position_id'=>$item['position_id']))."'>分析详情</a>";
            $row['职位优势'] = "<span class='a_position' title='{$item['position_advantage']}' >{$item['position_advantage']}</span>";
//            $row['工作类型'] = $item['job_nature'];
//            $row['直属领导'] = "<span class='s80'>{$item['leader_name']}</span>";
            $list[$key] = $row;
        }
        $columns = array_keys(current($list));
        $this->assign('columns',$columns);
        $this->assign('data',$list);
    }
}