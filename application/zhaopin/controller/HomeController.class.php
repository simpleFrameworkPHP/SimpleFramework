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
        $workYear = array(1,2,3,4,5,6);
        $salary = array(1,2,3,4,6,8,10);
        $bei = 5000;
        foreach($salary as $key=>$value){
            $salary[$key] = $value * $bei;
        }
        $salary = array_values($salary);
        $model = M('',0);
        $result = $model->table("model_position")
            //->where($where)
            ->fields('positionId,minWorkYear,maxWorkYear,minSalary,maxSalary')
            ->select();
        $item = $salary;// array_merge(array('年限平均工资'),$salary);
        $item[] = '平均薪资';
        $list = array();
        foreach($result as $row){
            foreach($salary as $skey=>$svalue){
                if($row['minSalary'] <= $svalue){
                    if($row['maxSalary'] >= $svalue){
                        foreach($workYear as $nkey=>$nvalue){
                            if($row['minWorkYear'] <= $nvalue){
                                if($row['maxWorkYear'] >= $nvalue){
                                    $list[$svalue][$nvalue]++;
                                }
                            }
                        }
                    }
                }
            }
        }
        foreach($workYear as $value){
            $xAxis[] = $value.'年';
        }

        foreach($list as $key => $value){
            $data[] = array("data"=>array_values($value),"name"=>$key,"type"=>"bar");
        }

        //工作年限&cp
        $iyear = array();
        //工作年限&薪资
        $isalary = array();
        foreach($list as $salary=>$value){
            foreach($value as $year=>$cp){
                $iyear[$year] += $cp;
                $isalary[$year] += $cp*$salary;
            }
        }
        $avgSalary = array();
        foreach($iyear as $year => $value){
            $avgSalary[$year] = number_format($isalary[$year] / $iyear[$year],2,'.','');
        }
        $data[] = array("data"=>array_values($avgSalary),"name"=>'平均薪资',"type"=>"line","yAxisIndex"=>1);

        $this->assign('json',JSON($data));
        $this->assign('xAxis',JSON($xAxis));
        $this->assign('item',JSON($item));
        $this->assign('id','workyear_np');
        $this->assign('title','【数据挖掘】按照工作年限统计薪资分布');
        return $this->fetch('index_dy_echarts');

    }
} 