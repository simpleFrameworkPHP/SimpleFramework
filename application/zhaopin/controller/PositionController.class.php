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
        //公司条件搜索
        if($_REQUEST['company_name']){
            $c_where['company_name'] = array('like',"%{$_REQUEST['company_name']}%");
            $list = M('zhaopin/MCompany',0)->fields('id')->where($c_where)->select();
            $cids = array();
            if(!empty($list)){
                $cids = reIndexArray($list,'id');
                $cids = array_keys($cids);
            }
            $where['company_id'] = array('in');
            $where['company_id'] = array_merge($where['company_id'],$cids);
        }
        //获取最后一次抓取数据的时间
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
//            $row['职位类型'] = $position_types[$item['position_type_id']];
            $row['薪酬'] = $item['salary'];
            $row['工作年限'] = $item['work_year'];
            $row['学历'] = $item['education'];
            $row['创建时间'] = date('Y-m-d',strtotime($item['create_time']));

            $row['公司'] = $company_list[$item['company_id']];
            $row['职位详情'] = "<a href='".CommonController::getUrl($item['position_id'],$item['data_from'])."'>查看详情</a>";
            $row['职位分析'] = "<a href='".H('zhaopin/position/parsing',array('position_id'=>$item['position_id']))."'>分析详情</a>";
            $row['职位优势'] = $item['position_advantage'];
//            $row['工作类型'] = $item['job_nature'];
            $row['直属领导'] = $item['leader_name'];
            $list[$key] = $row;
        }
        $columns = array_keys(current($list));
        $this->assign('columns',$columns);
        $this->assign('data',$list);
    }

    public function parsing(){
        $mposition = M('zhaopin/MPosition',0);
        $init_where['add_time'] = CommonController::getLastLogTime('model_position');
        if($_REQUEST['position_id']){
            $where = array_merge($init_where,array('position_id'=>$_REQUEST['position_id']));
            $info = $mposition->where($where)->find();
            //公司维度数据准备
            $where = array_merge($init_where,array('company_id'=>$info['company_id']));
            $comp_list = $mposition->fields('salary,salary_id,work_year,workyear_id')
                ->where($where)->select();
            $c_salary_cp = array();
            $c_workyear_cp = array();
            foreach($comp_list as $row){
                $c_salary_cp[$row['salary_id']]++;
                $c_workyear_cp[$row['salary_id']][$row['workyear_id']]++;
            }

            //城市维度数据准备
            $where = array_merge($init_where,array('city'=>$info['city']));
            $city_list = $mposition->fields('salary,salary_id,work_year,workyear_id')
                ->where($where)->select();
            $ct_salary_cp = array();
            foreach($city_list as $row){
                $ct_salary_cp[$row['salary_id']]++;
            }

            //同行业数据准备
            $where = array_merge($init_where,array('position_first_type_id'=>$info['position_first_type_id'],'city'=>$info['city']));
            $city_list = $mposition->fields('salary,salary_id,work_year,workyear_id')
                ->where($where)->select();
            $ind_salary_cp = array();
            foreach($city_list as $row){
                $ind_salary_cp[$row['salary_id']]++;
            }

            //同城同行业数据准备
            $where = array_merge($init_where,array('position_first_type_id'=>$info['position_first_type_id']));
            $city_list = $mposition->fields('salary,salary_id,work_year,workyear_id')
                ->where($where)->select();
            $ctind_salary_cp = array();
            foreach($city_list as $row){
                $ctind_salary_cp[$row['salary_id']]++;
            }

            echo '<pre>';
            var_dump($c_salary_cp,$c_workyear_cp,$ct_salary_cp,$ind_salary_cp,$ctind_salary_cp);
            print_r($info);
        } else {

        }
    }
} 