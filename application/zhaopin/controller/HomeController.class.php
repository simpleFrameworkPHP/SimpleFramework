<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/10
 * Time: 上午9:44
 */

class HomeController extends Controller {

    public function index(){
        $template[] = $this->indexCityCP();
        $template[] = $this->indexWorkYearCP();

        $this->assign('template',$template);
        $this->display();
    }

    /**
     * 按照城市统计职位数与公司数的分析图
     * @return string
     */
    public function indexCityCP(){
        //$where['add_time'] = date('Y-m-d');
        $model = M('',0);
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
        //$where['add_time'] = date('Y-m-d');
        $Dworkyear = M('zhaopin/WorkYear',0);
        $data = $Dworkyear->select();
        foreach($data as $value){
            $workyearlist[$value['id']] = $value['title'];
        }
        $Dsalary = M('zhaopin/DicSalary',0);
        $data = $Dsalary->select();

        foreach($data as $value){
            $salary[$value['id']] = $value['title'];
            $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
        }
        $model = M('',0);
        $result = $model->table("model_position")
            //->where($where)
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
} 