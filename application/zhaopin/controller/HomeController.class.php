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
        $template[] = $this->indexWorkYearNP();

        $this->assign('template',$template);
        $this->display();
    }

    /**
     * 按照城市统计职位数与公司数的分析图
     * @return string
     */
    public function indexCityCP(){
        //$where['add_time'] = date('Y-m-d');
        $model = M('',3);
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
        $list[] = array("data"=>$cp,"name"=>"职位/公司数","type"=>"line","yAxisIndex"=>1);
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
    public function indexWorkYearNP(){
        //$where['add_time'] = date('Y-m-d');
        $model = M('',3);
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
        $list[] = array("data"=>$cp,"name"=>"职位/公司数","type"=>"line","yAxisIndex"=>1);
        $this->assign('json',JSON($list));
        $this->assign('xAxis',JSON($city));
        $this->assign('item',JSON(array("职位数","公司数","职位/公司数")));
        $this->assign('id','workyear_np');
        $this->assign('title','【数据挖掘】按照工作年限统计薪资分布');
        return $this->fetch('index_dy_echarts');

    }
} 