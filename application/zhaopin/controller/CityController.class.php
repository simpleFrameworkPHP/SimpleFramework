<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/4/15
 * Time: 13:07
 */

class CityController extends BaseController {

    public function index(){
        //工作年限列表
        $workyearlist = CommonController::showWorkYear();
        //薪酬范围&平均薪资列表
        $Dsalary = M('zhaopin/DicSalary',$this->db_num);
        $data = $Dsalary->fields(array('id','title','min_salary','max_salary'))->select();

        foreach($data as $value){
            $salary[$value['id']] = $value['title'];
            $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
        }
        //公司id=》行业id列表
        $industry_list = M('zhaopin/MIndCom',$this->db_num)->fields(array('company_id','industry_id'))->select();
        foreach($industry_list as $value){
            $industry_company[$value['company_id']] = $value['industry_id'];
        }
        unset($industry_list);
        //行业数组初始化
        $industrys = M('zhaopin/DicIndustry',$this->db_num)->fields('id,industry_title')->select();
        $ind_info_list = array();
        foreach($industrys as $item)
            $ind_info_list[$item['id']] = $item['industry_title'];

        //公司形态数据
        $company_list = M('zhaopin/MCompany',$this->db_num)->fields(array('id','stage_level_id'))->select();
        foreach($company_list as $item){
            $level_list[$item['id']] = $item['stage_level_id'];
        }

        $pft = M('zhaopin/DicPositionType',$this->db_num)->getPTListByPid();
        $this->assign('pft',$pft);

        $where = $this->getWhere();
        if($where['city']){
            $data = $this->initData($workyearlist, $salary,$level_list,$industry_company,$where);
            $template = array();
            $template[] = $this->initPie1($data['city']['pie1'], $workyearlist, $salary);
//        $template[] = $this->initLevel($data['Level'], $salary, $vagSalary,$level_list);
//            $template[] = $this->initIndustryCP($data['IndustryCP'],$salary, $vagSalary, $ind_info_list);
            $template[] = $this->initIndustryCP($data['city']['IndustryCP'],$salary, $vagSalary, $ind_info_list);
//        $template[] = $this->initIndustrySalary($data['IndustryCP'],$salary, $vagSalary, $industry_company, $ind_info_list);
        } else {
            $this->assign('error_info',"请选择需要查询的城市");
        }

        $this->showPositionList($where);

        $info = array();
        $info['ALLCP'] = $data['ALLCP'];
        $info['CITYCP'] = $data['CITYCP'];
        $this->assign('info',$info);
        $this->assign('template',$template);
        $this->assign('where',$_REQUEST);
        $this->display();
    }

    public function getWhere(){
        if($_REQUEST['city']){
            $city_where['city'] = trim($_REQUEST['city']);
        }
        if($_REQUEST['position_first_type_id']){
            $city_where['position_first_type_id'] = $_REQUEST['position_first_type_id'];
        }
        if($_REQUEST['position']){
            $city_where['position_name'] = array('like',"%{$_REQUEST['position']}%");
        }
        return $city_where;
    }

    public function initData($workyearlist, $salary,$level_list,$industry_company, $city_where = array()){
        $where = $city_where;
        $where['add_time'] = CommonController::getLastLogTime('model_position');

        $i = 0;
        $city_i = 0;
        $limit = 60000;
        $page = 0;
        $industry_list = array_flip($industry_company);
        $list['WorkYearCP'] = initArray($salary,$workyearlist);
        $list['Level'] = initArray($salary,$level_list);
        $list['IndustryCP'] = initArray($salary,$industry_list);
        while(!$i || !empty($result)){
            unset($result);
            $result = M('zhaopin/MPosition',$this->db_num)->fields(array('workyear_id','company_id','position_first_type_id','salary_id','city'))->where($where)->limit($limit,$limit * $page)->select();
            foreach($result as $row){
                if($row['city'] == $city_where['city']){
                    $list['city']['pie1']['WorkYearCP'][$row['workyear_id']]++;
                    $list['city']['pie1']['SalaryCP'][$row['salary_id']]++;
                    if($city_where['position_first_type_id'] && $city_where['position_first_type_id'] == $row['position_first_type_id']){
                        $list['city']['IndustryCP'][$row['salary_id']][$industry_company[$row['company_id']]]++;
                    } else {
                        $list['city']['IndustryCP'][$row['salary_id']][$industry_company[$row['company_id']]]++;
                    }
                    $city_i++;
                }
                $list['WorkYearCP'][$row['salary_id']][$row['workyear_id']]++;
                $list['Level'][$row['salary_id']][$level_list[$row['company_id']]]++;
                $list['IndustryCP'][$row['salary_id']][$industry_company[$row['company_id']]]++;
                $i++;
            }
            $page++;
        }
        $list['ALLCP'] = $i;
        $list['CITYCP'] = $city_i;
        return $list;
    }

    public function initPie1($city_data, $work_year, $salary){
        $item_style = array('normal'=>array('label'=>array('show'=>false),'labelLine'=>array('show'=>false)));
        $init_style = array('type'=>'pie','roseType'=>'radius','width'=>'38%','max'=>40);
        $data = array();
        foreach($city_data['WorkYearCP'] as $workyear_id => $cp){
            $data[$workyear_id] = array('name'=>$work_year[$workyear_id],'value'=>$cp);
            $item1[$workyear_id] = $work_year[$workyear_id];
        }
        ksort($data);
        ksort($item1);
        $list[] = array_merge($init_style,array('name'=>'年限分布','radius'=>array(20,100),'center'=>array('22%',180),'itemStyle'=>$item_style,'data'=>array_values($data)));
        $data = array();
        foreach($city_data['SalaryCP'] as $salary_id => $cp){
            $data[$salary_id] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item2[$salary_id] = $salary[$salary_id];
        }
        ksort($data);
        ksort($item2);
        $item = array_merge($item1,$item2);
        $list[] = array_merge($init_style,array('name'=>'薪酬分布','radius'=>array(30,100),'center'=>array('70%',180),'data'=>array_values($data)));
        return $this->showPieEcharts($list,$item,'pie1','【同城】整体分析');
    }
    public function initIndustryCP($list,$salary, $vagSalary, $industry){

        //计算各个行业平均工资(找出top5)
        $icp = array();
        $isalary = array();
        foreach($list as $salary_id=>$item){
            foreach($item as $industry_id=>$cp){
                $icp[$industry_id] += $cp;
                $isalary[$industry_id] += $cp * $vagSalary[$salary_id];
            }
        }
        foreach($icp as $industry_id => $cp){
            $cp_list[$icp[$industry_id]] = $industry_id;
        }
        krsort($cp_list);
        $industry_ids = array();
        foreach($cp_list as $industry_id){
            if(count($industry_ids) < 5){
                $industry_ids[] = $industry_id;
            }
        }

        //数据筛选
        foreach($list as $ksalary=>$value){
            $itemkey[] = $ksalary;
            foreach($industry_ids as $id){
                $new_list[$ksalary][] = isset($list[$ksalary][$id]) ? $list[$ksalary][$id] : 0;
            }
        }
        $list = $new_list;
        sort($itemkey);
        $data = array();
        $item = array();
        foreach($itemkey as $value){
            $data[] = array("data"=>array_values($list[$value]),"name"=>$salary[$value],"type"=>"bar");
            $item[] = $salary[$value];
        }

        foreach($industry_ids as $ind_id){
            $industry_info[$ind_id] = isset($industry[$ind_id]) ? $industry[$ind_id] : '';
            $avg_salary[$ind_id] = number_format($isalary[$ind_id] / $icp[$ind_id],2,'.','');
        }
        ksort($industry_info);
        $xAxis = array_values($industry_info);

        $data[] = array("data"=>array_values($avg_salary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';

        return $this->showDyEcharts($data,$xAxis,$item,'industry_cp','【数据挖掘】按照行业需求职位top5统计薪资分布');
    }

    public function showPositionList($where){
        //获取最后一次抓取数据的时间
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $model = M('zhaopin/MPosition',$this->db_num);
        $data = $model->where($where)->limit(1000)->select();
        $count = $model->fields('count(id)',false)->where($where)->simple();
        if(!empty($data)){
            $init_where['add_time'] = $where['add_time'];
            $this->showData($data,$init_where);
        }
        $this->assign('cp',$count);
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