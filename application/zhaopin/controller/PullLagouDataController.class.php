<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/19
 * Time: 上午10:55
 */

class PullLagouDataController extends Controller {

    public function writePosition(){
        $today = date('Y-m-d');
        $i = 1;
        $json = array();
        $city = urlencode("大连");
        while(($json['success'] && !empty($json['content']['result'])) || $i == 1){
            $url = "http://www.lagou.com/jobs/positionAjax.json?px=new&first=false&pn=$i";
            $url = isset($city) ? $url."&city=$city" : $url;
            $json = getHtmlData($url);
            $json = json_decode($json,true);
            $data = $json['content']['result'];
            foreach($data as $row){
                $row['companyLabelList'] = implode(' ',$row['companyLabelList']);
                $row['addTime'] = $today;
                $model = M('',3);
                $model->table(array("view_lagou_position"))->addKeyUp($row);
            }
            echo  'page:'.$i."<br/>";
            sleep(3);
            $i++;
        }
    }

    public function showPosition(){
        $model = M('',3);
        print_r($_REQUEST);
        if($_REQUEST['city']){
            $where['city'] = $_REQUEST['city'];
        }
        //$where['city'] = "大连";
        $where['positionName'] = array("LIKE","%php%");
        $data = $model->table(array("view_lagou_position"))->where($where)->order(array('createTime'=>'DESC'))->select();

//        echo "<pre>";
//        print_r($data);exit;
        $columns = array_keys($data[0]);//array('id','positionId','workYear','createTime','positionName','companyShortName','positionAdvantage','salary','companyName','financeStage','industryField','companySize','companyLabelList');
        //array_keys($data[0]);
        foreach($data as $key => &$item){
            $item['positionId'] = "<a href='http://www.lagou.com/jobs/".$item['positionId'].".html'>点击查看</a>";
            $item['id'] = $key;
        }
        $this->assign('columns',$columns);
        $this->assign('data',$data);
        $this->display();
    }

    public function showSalaryPositionByCity(){
        $workYear = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0);
        $model = M('',3);
        $bei = 5000;
        $xAxis = array();
        $json = array();
        $all_city = $model->table("model_position")->fields("distinct city",false)->select();
        $all_city = reIndexArray($all_city,'city');
        $all_city = array_keys($all_city);
        $where_city = $_REQUEST['city'] == '' ? array() : explode(",",$_REQUEST['city']);
        $where = '';
        $select_city = array();
        if(!empty($where_city)){
            $where['city'] = array_merge(array('in'),$where_city);
            $select_city = $where_city;
        }


        $city = $model->table("model_position")->fields("distinct city",false)->where($where)->select();
        $city = reIndexArray($city,'city');
        $city = array_keys($city);
        foreach($workYear as $key=>&$count){
            $salary = $key*$bei;
            $where['minSalary'] = array("<=",$salary);
            $where['maxSalary'] = array(">=",$salary);
            $row = $model->table("model_position")->where($where)->fields("count(1),city",false)->group("city")->select();
            $data[$salary] = empty($row)? array(0=>array("count(1)"=>0,"city"=>"")) : $row;
            $row = reIndexArray($row,'city');
            $xAxis[] = $salary;
            $all_salary = 0;
            foreach($city as $icity){
                $count = isset($row[$icity]) && $row[$icity]['count(1)'] ? $row[$icity]['count(1)'] : 0;
                $all_salary += $count;
                $json[$icity]['data'][] = $count;
            }
            $json['all']['data'][] = $all_salary;
        }
        foreach($json as $icity=>$value){
            $idata = array();
            foreach($value['data'] as $item){
                $idata[] = $item;
            }
            $value['data'] = $idata;
            $value['name'] = $icity;
            $value['type'] = 'line';
            $positions[] = $value;
        }
        $city[] = 'all';
        $all_city = array_merge(array("all"),$all_city);
        $this->assign('select_city',$select_city);
        $this->assign('all_city',$all_city);
        $this->assign('city',$city);
        $this->assign('json',json_encode($positions));
        $this->assign('xAxis',json_encode($xAxis));
        $columns = array_keys($data[0][0]);
        $this->assign('columns',$columns);
        $this->assign('data',$data);
        $this->display();
    }

} 