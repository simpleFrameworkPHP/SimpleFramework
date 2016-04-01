<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午8:44
 */

class PositionController extends BaseController {

    var $db_num = 0;

    public function index(){
        $where = $this->getWhere();
        $model = M('zhaopin/MPosition',$this->db_num);
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
            $list = M('zhaopin/MCompany',$this->db_num)->fields('id')->where($c_where)->select();
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
        $DicPT = M('zhaopin/DicPositionType',$this->db_num);
        $position_types = $DicPT->getPNameListInId($position_type_ids);
        $DicC = M('zhaopin/MCompany',0);
        $company_list = $DicC->getCNameListInId($company_ids);
        foreach($data as $key => $item){
            $row = array();
            $row['序号'] = $key;
            $row['职位名称'] = "<a href='".CommonController::getUrl($item['position_id'],$item['data_from'])."'>{$item['position_name']}</a>";
            $row['城市'] = $item['city'];
//            $row['职位类型'] = $position_types[$item['position_type_id']];
            $row['薪酬'] = $item['salary'];
            $row['工作年限'] = $item['work_year'];
            $row['学历'] = $item['education'];
            $row['创建时间'] = date('Y-m-d',strtotime($item['create_time']));

            $row['公司'] = $company_list[$item['company_id']];
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
        $mposition = M('zhaopin/MPosition',$this->db_num);
        $init_where['add_time'] = CommonController::getLastLogTime('model_position');
        $show = false;
        if($_REQUEST['position_id']){
            //本身职位的信息
            $where = array_merge($init_where,array('position_id'=>$_REQUEST['position_id']));
            $info = $mposition->where($where)->find();


            //工作年限列表
            $workyearlist = CommonController::showWorkYear();
            //薪酬范围&平均薪资列表
            $Dsalary = M('zhaopin/DicSalary',$this->db_num);
            $data = $Dsalary->fields(array('id','title','min_salary','max_salary'))->select();

            foreach($data as $value){
                $salary[$value['id']] = $value['title'];
                $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
            }
            //公司维度数据准备
            $where = $init_where;
            $where[] = "company_id={$info['company_id']} or city='{$info['city']}' or position_first_type_id={$info['position_first_type_id']}";
            $comp_list = $mposition->fields('company_id,city,position_first_type_id,salary_id,workyear_id')
                ->where($where)->select();
            //同公司薪酬分布数据准备
            $c_salary_cp = array();
            //同公司工作年限招聘数据准备
            $c_workyear_cp = array();
            //城市维度数据准备
            $ct_salary_cp = array();
            //同行业数据准备
            $ind_salary_cp = array();
            //同行业&&同城数据准备
            $ctind_salary_cp = array();
            //同公司&&同城数据准备
            $c_ct_salary_cp = array();
            foreach($comp_list as $row){
                if($row['company_id'] == $info['company_id']){
                    //同公司职位数分布
                    $c_salary_cp[$row['salary_id']]++;
                    $c_workyear_cp[$row['workyear_id']]++;
                }
                if($row['company_id'] == $info['company_id'] && $row['city'] == $info['city']){
                    //同城同公司职位数分布
                    $c_ct_salary_cp[$row['salary_id']]++;
                }
                if($row['city'] == $info['city'] && $row['position_first_type_id'] == $info['position_first_type_id']){
                    //同行业同城薪资分布
                    $ctind_salary_cp[$row['salary_id']]++;
                }
                if($row['city'] == $info['city']){
                    //同城职位数统计
                    $ct_salary_cp[$row['salary_id']]++;
                }
                if($row['position_first_type_id'] == $info['position_first_type_id']){
                    //同行业职位数统计
                    $ind_salary_cp[$row['salary_id']]++;
                }
            }

//            echo '<pre>';
//            var_dump($ct_salary_cp,$ind_salary_cp,$ctind_salary_cp);
//            print_r($info);exit;
            $template['pie1'] = $this->showPie1($c_workyear_cp, $workyearlist,$c_salary_cp, $salary);
            $template['pie2'] = $this->showPie2($c_ct_salary_cp,$ctind_salary_cp, $salary);
            $this->assign('template',$template);
            $this->assign('info',$this->showPositionInfo($info));
            $show = true;
        }
        $this->assign('show',$show);
        $this->display();
    }

    function showPie1($c_workyear_cp, $workyear,$c_salary_cp,$salary){
        $item_style = array('normal'=>array('label'=>array('show'=>false),'labelLine'=>array('show'=>false)));
        $init_style = array('type'=>'pie','roseType'=>'radius','width'=>'38%','max'=>40);
        $data = array();
        foreach($c_workyear_cp as $workyear_id => $cp){
            $data[] = array('name'=>$workyear[$workyear_id],'value'=>$cp);
            $item[] = $workyear[$workyear_id];
        }
        $list[] = array_merge($init_style,array('name'=>'年限分布','radius'=>array(20,100),'center'=>array('22%',180),'itemStyle'=>$item_style,'data'=>$data));
        $data = array();
        foreach($c_salary_cp as $salary_id => $cp){
            $data[] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item[] = $salary[$salary_id];
        }
        $list[] = array_merge($init_style,array('name'=>'薪酬分布','radius'=>array(30,100),'center'=>array('70%',180),'data'=>$data));
        return $this->showPieEcharts($list,$item,'pie1','【同公司】内部整体分析');
    }

    function showPie2($c_ct_salary_cp,$ctind_salary_cp, $salary){
        $item_style = array('normal'=>array('label'=>array('show'=>false),'labelLine'=>array('show'=>false)));
        $init_style = array('type'=>'pie','roseType'=>'radius','width'=>'38%','max'=>40);
        $data = array();
        foreach($c_ct_salary_cp as $salary_id => $cp){
            $data[] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item[] = $salary[$salary_id];
        }
        $list[] = array_merge($init_style,array('name'=>'同公司','radius'=>array(20,100),'center'=>array('22%',180),'itemStyle'=>$item_style,'data'=>$data));
        $data = array();
        foreach($ctind_salary_cp as $salary_id => $cp){
            $data[] = array('name'=>$salary[$salary_id],'value'=>$cp);
        }
        $list[] = array_merge($init_style,array('name'=>'同行业','radius'=>array(30,100),'center'=>array('70%',180),'data'=>$data));
        return $this->showPieEcharts($list,$item,'pie2','【同城】薪酬分布');
    }

    public function showPositionInfo($info){
        $mpt = M('zhaopin/DicPositionType',$this->db_num);
        $mpt_info = $mpt->where(array('id'=>array('in',$info['position_type_id'],$info['position_first_type_id'])))->select();
        $mpt_info = reIndexArray($mpt_info,'id');
        $info['position_type_id'] = $mpt_info[$info['position_type_id']]['pos_name'];
        $info['position_first_type_id'] = $mpt_info[$info['position_first_type_id']]['pos_name'];
        $info['url'] = CommonController::getUrl($info['position_id'],$info['data_from']);
        $info['data_from'] = CommonController::getFrom($info['data_from']);
//        echo '<pre>';var_dump($info);exit;
        return $info;
    }
} 