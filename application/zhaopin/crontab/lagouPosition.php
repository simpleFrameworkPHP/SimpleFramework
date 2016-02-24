<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/24
 * Time: 上午9:14
 */

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/core/cron.php");
loadDirFile(dirname(dirname(__FILE__)).'/common');

print_r($argv);

switch($argv[1]){
    case 1:pullData();
        break;
    case 2:manageData();
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
            $model = M('',3);
            $model->table(array("view_lagou_position"))->addKeyUp($row);
        }
        echo  'page:'.$i."\n";
        sleep(3);
        $i++;
    }
}

function manageData(){
    $model = M('',3);
    $today = date('Y-m-d');
    $where['addTime'] = $today;
    $model->table(array("view_lagou_position"))->where($where);
    $i = 0;
    $limit = 100;
    $data = array();
    while($i == 0 || !empty($data)){
        $data = $model->limit($limit,$i*$limit)->select();
        foreach($data as $row){
            //工作年限处理
            $row['MinWorkYear'] = 0;
            $row['MaxWorkYear'] = 0;
            if(strstr($row['workYear'],'-')){
                $work_year = explode('-',$row['workYear']);
                $row['MinWorkYear'] = intval($work_year[0]);
                $row['MaxWorkYear'] = intval($work_year[1]);
            }
            unset($row['workYear']);
            //薪资范围处理
            $row['MinSalary'] = 0;
            $row['MaxSalary'] = 0;
            if(strstr($row['salary'],'-')){
                $salary = explode('-',$row['salary']);
                $row['MinSalary'] = strstr($salary[0],'k') ? intval($salary[0])*1000 : intval($salary[0]);
                $row['MaxSalary'] = strstr($salary[1],'k') ? intval($salary[1])*1000 : intval($salary[1]);
            }
            unset($row['salary']);

            print_r($row);exit;
        }
    }
}