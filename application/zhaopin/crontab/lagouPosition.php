<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/24
 * Time: 上午9:14
 */

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/core/cron.php");
loadDirFile(dirname(dirname(__FILE__)).'/common');


switch($argv[1]){
    case "add":pullData();
        break;
    case "manage":manageData();
        break;
    case 3:getPositionByWorkYear();
        break;
    case "city":manageCity();
        break;
    default:
        manageData();
}


function pullData(){
    $today = date('Y-m-d');
    $i = 1;
    $json = array();
//$city = urlencode("大连");
    while(($json['success'] && !empty($json['content']['result'])) || $i == 1){
        $url = "http://www.lagou.com/jobs/positionAjax.json?px=new&first=false&pn=$i";
        $url = isset($city) ? $url."&city=$city" : $url;
        $json = getHtmlData($url);
        $json = json_decode($json,true);
        $data = $json['content']['result'];
        foreach($data as $row){
            $row['companyLabelList'] = implode(' ',$row['companyLabelList']);
            $row['addTime'] = $today;
            $model = M('',0);
            $model->table(array("view_lagou_position"))->addKeyUp($row);
        }
        echo  'page:'.$i." count:".count($data)."\n";
        sleep(1);
        $i++;
    }
    print_r($json);
}

function manageData(){
    $model = M('',0);
    $today = date('Y-m-d');
    $where = array();
    //$where['addTime'] = $today;
    $model->table(array("view_lagou_position"))->where($where);
    $i = 0;
    $limit = 100;
    $data = array();
    $db = M('',0);
    $db->table("model_position");
    while($i == 0 || !empty($data)){
        $data = $model->limit($limit,$i*$limit)->select();
        foreach($data as $row){
            //工作年限处理
            $row['minWorkYear'] = 0;
            $row['maxWorkYear'] = 0;
            if(strstr($row['workYear'],'-')){
                $work_year = explode('-',$row['workYear']);
                $row['minWorkYear'] = intval($work_year[0]);
                $row['maxWorkYear'] = intval($work_year[1]);
            }
            unset($row['workYear']);
            //薪资范围处理
            $row['minSalary'] = 0;
            $row['maxSalary'] = 0;
            if(strstr($row['salary'],'-')){
                $salary = explode('-',$row['salary']);
                $row['minSalary'] = strstr($salary[0],'k') ? intval($salary[0])*1000 : intval($salary[0]);
                $row['maxSalary'] = strstr($salary[1],'k') ? intval($salary[1])*1000 : intval($salary[1]);
            }
            unset($row['salary']);
            unset($row['addTime']);
            //数据来源
            $row['dataFrom'] = 'lagou';

            unset($row['id']);
//            print_r($row);exit;
            $id = $db->addKeyUp($row);
        }
        echo $i."\n";
        $i++;
    }
}

function getPositionByWorkYear(){
    $workYear = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0);
    $model = M('',0);
    $model->table("model_position");
    $bei = 2000;
    //$where['addTime'] = date('Y-m-d');
    foreach($workYear as $key=>&$count){
        $where['minSalary'] = array("<=",$key*$bei);
        $where['maxSalary'] = array(">=",$key*$bei);
        $row = $model->where($where)->fields("count(1)",false)->select();
        $data[$key*$bei] = $row;
    }
    print_r($data);
}

function manageCity(){
    $where['add_time'] = date('Y-m-d');
    $model = M('',0);
    $data = $model->table("view_lagou_position")
        ->where($where)
        ->group("city")
        ->fields("count(DISTINCT positionId) as countPosition,count(DISTINCT companyId) as countCompany,city",false)
        ->select();
    $model = M('',0);
    $model->table("model_city");
    foreach($data as $row){
        $row['addTime'] = $where['addTime'];
        $model->addKeyUp($row);
    }
    echo "end";
}