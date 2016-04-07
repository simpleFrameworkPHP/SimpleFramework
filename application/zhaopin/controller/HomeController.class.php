<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/10
 * Time: 上午9:44
 */

class HomeController extends BaseController {

    var $db_num = 0;

    public function index(){
//        $template[] = $this->indexCityCP();
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

        $data = $this->initData($workyearlist, $salary,$level_list,$industry_company);

        $template[] = $this->indexWorkYearCP($data['WorkYearCP'], $workyearlist, $salary, $vagSalary);
        $template[] = $this->initLevel($data['Level'], $salary, $vagSalary,$level_list);
        $template[] = $this->initIndustryCP($data['IndustryCP'],$salary, $vagSalary, $industry_company, $ind_info_list);
        $template[] = $this->initIndustrySalary($data['IndustryCP'],$salary, $vagSalary, $industry_company, $ind_info_list);


        $this->assign('template',$template);
        $this->display();
    }

    public function initData($workyearlist, $salary,$level_list,$industry_company){
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $i = 0;
        $page = 60000;
        $industry_list = array_flip($industry_company);
        $list['WorkYearCP'] = initArray($salary,$workyearlist);
        $list['Level'] = initArray($salary,$level_list);
        $list['IndustryCP'] = initArray($salary,$industry_list);
        while(!$i || !empty($result)){
            unset($result);
            $result = M('zhaopin/MPosition',$this->db_num)->fields(array('workyear_id','company_id','salary_id'))->where($where)->limit($page,$i * $page)->select();
            foreach($result as $row){
                $list['WorkYearCP'][$row['salary_id']][$row['workyear_id']]++;
                $list['Level'][$row['salary_id']][$level_list[$row['company_id']]]++;
                $list['IndustryCP'][$row['salary_id']][$industry_company[$row['company_id']]]++;
            }
            $i++;
        }
        return $list;
    }

    /**
     * 按照年限统计薪资分布
     */
    public function indexWorkYearCP($list, $workyearlist, $salary, $vagSalary){
        foreach($list as $ksalary=>$value){
            $itemkey[] = $ksalary;
            ksort($list[$ksalary]);
        }
        sort($itemkey);
        $data = array();
        $item = array();
        foreach($itemkey as $value){
            $data[] = array("data"=>array_values($list[$value]),"name"=>$salary[$value],"type"=>"bar");
            $item[] = $salary[$value];
        }
        $xAxis = array_values($workyearlist);


        //工作年限&cp
        $iyear = array();
        //工作年限&薪资
        $isalary = array();
        foreach($list as $salary_id=>$value){
            foreach($value as $year=>$cp){
                $iyear[$year] += $cp;
                $isalary[$year] += $cp*$vagSalary[$salary_id];
            }
        }
        $avgSalary = array();
        foreach($workyearlist as $wid=>$w_title){
            $avgSalary[$wid]= 0 ;
        }
        foreach($iyear as $year => $value){
            $avgSalary[$year] = number_format($isalary[$year] / $iyear[$year],2,'.','');
        }
        $data[] = array("data"=>array_values($avgSalary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';

        return $this->showDyEcharts($data,$xAxis,$item,'workyear_np','【数据挖掘】按照工作年限统计薪资分布');
    }

    public function initLevel($list, $salary, $vagSalary,$level_list){

        //level数据
        $level = M('zhaopin/DicCLevel',$this->db_num)->fields(array('id','level_title'))->select();
        foreach($level as $item){
            $level_info[$item['id']] = $item['level_title'];
        }

        //职位统计

//        $list = initArray($salary,$level_list);
//        foreach($position_list as $i_position){
//            $list[$i_position['salary_id']][$level_list[$i_position['company_id']]]++;
//        }

        $vagcp = array();
        $vag_salary = array();
        foreach($list as $salary_id=>$value){
            $itemkey[] = $salary_id;
            foreach($value as $level_id => $cp){
                $vagcp[$level_id] += $cp;
                $vag_salary[$level_id] += $cp * $vagSalary[$salary_id];
            }
            ksort($list[$salary_id]);
        }
        //核算平均工资
        foreach($vag_salary as $level_id=>$i_salary){
            $salary_list[$level_id] = $i_salary ? number_format($i_salary / $vagcp[$level_id],2,'.','') : 0;
        }
        ksort($salary_list);
        sort($itemkey);
        $data = array();
        $item = array();
        foreach($itemkey as $value){
            $data[] = array("data"=>array_values($list[$value]),"name"=>$salary[$value],"type"=>"bar");
            $item[] = $salary[$value];
        }
        $data[] = array("data"=>array_values($salary_list),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';
//        ksort($vagcp);
//        $data[] = array("data"=>array_values($vagcp),"name"=>'总职位数',"type"=>"line");
//        $item[] = '总职位数';
        ksort($level_info);
        $xAxis = array_values($level_info);

        return $this->showDyEcharts($data,$xAxis,$item,'level_cp','【数据挖掘】按照公司融资阶段统计薪资分布',20);
    }

    public function initIndustryCP($list,$salary, $vagSalary,$industry_company, $industry){

        //职位统计
//        $industry_list = array_flip($industry_company);
//        $list = initArray($salary,$industry_list);
//        foreach($position_list as $i_position){
//            $list[$i_position['salary_id']][$industry_company[$i_position['company_id']]]++;
//        }

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
            foreach($list[$ksalary] as $industry_id=>$cp){
                if(!in_array($industry_id,$industry_ids))
                    unset($list[$ksalary][$industry_id]);
            }
            ksort($list[$ksalary]);
        }
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

    public function initIndustrySalary($list, $salary, $vagSalary, $industry_company, $industry){

        //职位统计
//        $industry_list = array_flip($industry_company);
//        $list = initArray($salary,$industry_list);
//        foreach($position_list as $i_position){
//            $list[$i_position['salary_id']][$industry_company[$i_position['company_id']]]++;
//        }

        //计算各个行业平均工资(找出top5)
        $icp = array();
        $isalary = array();
        foreach($list as $salary_id=>$item){
            foreach($item as $industry_id=>$cp){
                $icp[$industry_id] += $cp;
                $isalary[$industry_id] += $cp * $vagSalary[$salary_id];
            }
        }
        foreach($isalary as $industry_id => $salary_sum){
            $cp_list[$salary_sum] = $industry_id;
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
            foreach($list[$ksalary] as $industry_id=>$cp){
                if(!in_array($industry_id,$industry_ids))
                    unset($list[$ksalary][$industry_id]);
            }
            ksort($list[$ksalary]);
        }
        sort($itemkey);
        $data = array();
        $item = array();
        foreach($itemkey as $value){
            $data[] = array("data"=>array_values($list[$value]),"name"=>$salary[$value],"type"=>"bar");
            $item[] = $salary[$value];
        }

        //行业数组初始化
        foreach($industry_ids as $ind_id){
            $industry_info[$ind_id] = isset($industry[$ind_id]) ? $industry[$ind_id] : '';
            $avg_salary[$ind_id] = number_format($isalary[$ind_id] / $icp[$ind_id],2,'.','');
        }
        ksort($industry_info);
        $xAxis = array_values($industry_info);

        $data[] = array("data"=>array_values($avg_salary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';
        return $this->showDyEcharts($data,$xAxis,$item,'industry_cp1','【数据挖掘】按照行业薪资总额top5统计薪资分布');
    }
} 