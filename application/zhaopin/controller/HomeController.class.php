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
        $template[] = $this->indexWorkYearCP();
        $template[] = $this->initIndustry();
        $template[] = $this->initLevel();

        $this->assign('template',$template);
        $this->display();
    }

    /**
     * 按照城市统计职位数与公司数的分析图
     * @return string
     */
    public function indexCityCP(){
        //$where['add_time'] = date('Y-m-d');
        $model = M('',$this->db_num);
        $data = $model->table("model_city")
            //->where($where)
            ->limit(5)
            ->order(array("countPosition"=>"DESC"))
            ->select();
        foreach($data as $row){
            $city[] = $row['city'];
            $positions[] = $row['countPosition'];
            $companys[] = $row['countCompany'];
            $cp[] = $row['countPosition']/$row['countCompany'];
        }
        $list[] = array("data"=>$positions,"name"=>"职位数","type"=>"bar");
        $list[] = array("data"=>$companys,"name"=>"公司数","type"=>"bar");
        $list[] = array("data"=>$cp,"name"=>"职位数/公司数","type"=>"line","yAxisIndex"=>1);
        $this->assign('json',JSON($list));
        $this->assign('xAxis',JSON($city));
        $this->assign('item',JSON(array("职位数","公司数","职位数/公司数")));
        $this->assign('id','city_cp');
        $this->assign('title','【数据挖掘】职位数与公司数的城市分布');
        return $this->fetch('index_dy_echarts');
    }

    /**
     * 按照年限统计薪资分布
     */
    public function indexWorkYearCP(){
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $Dworkyear = M('zhaopin/WorkYear',$this->db_num);
        $data = $Dworkyear->select();
        foreach($data as $value){
            $workyearlist[$value['id']] = $value['title'];
        }
        $Dsalary = M('zhaopin/DicSalary',$this->db_num);
        $data = $Dsalary->select();

        foreach($data as $value){
            $salary[$value['id']] = $value['title'];
            $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
        }
        $model = M('',$this->db_num);
        $result = $model->table("model_position")
            ->where($where)
            ->fields('workyear_id,salary_id')
            ->select();
        $list = array();
        foreach($result as $row){
              $list[$row['salary_id']][$row['workyear_id']]++;
        }
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
        foreach($iyear as $year => $value){
            $avgSalary[$year] = number_format($isalary[$year] / $iyear[$year],2,'.','');
        }
        $data[] = array("data"=>array_values($avgSalary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';

        $this->assign('json',JSON($data));
        $this->assign('xAxis',JSON($xAxis));
        $this->assign('item',JSON($item));
        $this->assign('id','workyear_np');
        $this->assign('title','【数据挖掘】按照工作年限统计薪资分布');
        return $this->fetch('index_dy_echarts');

    }

    public function initIndustry(){
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        //薪资初始化
        $Dsalary = M('zhaopin/DicSalary',$this->db_num);
        $data = $Dsalary->select();

        foreach($data as $value){
            $salary[$value['id']] = $value['title'];
            $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
        }

        //公司id=》行业id列表
        $industry_list = M('zhaopin/MIndCom',$this->db_num)->select();
        foreach($industry_list as $value){
            $industry_company[$value['company_id']] = $value['industry_id'];
        }
        //职位统计
        $position_list = M('zhaopin/MPosition',$this->db_num)->fields('company_id,salary_id')->where($where)->select();
        $list = array();
        foreach($position_list as $i_position){
            $list[$i_position['salary_id']][$industry_company[$i_position['company_id']]]++;
        }

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
            if(count($industry_ids) < 6){
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
        $industrys = M('zhaopin/DicIndustry',$this->db_num)->select();
        foreach($industrys as $industry){
            if(in_array($industry['id'],$industry_ids)){
                $industry_info[$industry['id']] = $industry['industry_title'];
                $avg_salary[$industry['id']] = number_format($isalary[$industry['id']] / $icp[$industry['id']],2,'.','');
            }
        }
        ksort($industry_info);
        $xAxis = array_values($industry_info);

        $data[] = array("data"=>array_values($avg_salary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);
        $item[] = '平均薪资';

        $this->assign('json',JSON($data));
        $this->assign('xAxis',JSON($xAxis));
        $this->assign('item',JSON($item));
        $this->assign('id','industry_cp');
        $this->assign('title','【数据挖掘】按照行业统计薪资分布');
        return $this->fetch('index_dy_echarts');
    }

    public function initLevel(){
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        //薪资初始化
        $Dsalary = M('zhaopin/DicSalary',$this->db_num);
        $data = $Dsalary->select();

        foreach($data as $value){
            $salary[$value['id']] = $value['title'];
            $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
        }
        //公司形态数据
        $company_list = M('zhaopin/MCompany',$this->db_num)->fields('id,stage_level_id')->select();
        foreach($company_list as $item){
            $level_list[$item['id']] = $item['stage_level_id'];
        }

        //level数据
        $level = M('zhaopin/DicCLevel',$this->db_num)->select();
        foreach($level as $item){
            $level_info[$item['id']] = $item['level_title'];
        }

        //职位统计
        $position_list = M('zhaopin/MPosition',$this->db_num)->fields('company_id,salary_id')->where($where)->select();
        $list = array();
        foreach($position_list as $i_position){
            $list[$i_position['salary_id']][$level_list[$i_position['company_id']]]++;
        }

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
            $salary_list[$level_id] = number_format($i_salary / $vagcp[$level_id],2,'.','');
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
        ksort($vagcp);
        $data[] = array("data"=>array_values($vagcp),"name"=>'总职位数',"type"=>"line");
        $item[] = '总职位数';
        ksort($level_info);
        $xAxis = array_values($level_info);

        $this->assign('json',JSON($data));
        $this->assign('xAxis',JSON($xAxis));
        $this->assign('item',JSON($item));
        $this->assign('id','level_cp');
        $this->assign('x_degree',20);
        $this->assign('title','【数据挖掘】按照公司融资阶段统计薪资分布');
        return $this->fetch('index_dy_echarts');
    }

} 