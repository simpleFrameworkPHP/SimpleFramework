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
    $MPosition = M('zhaopin/MPosition',0);
    $MCompany = M('zhaopin/MCompany',0);
    $dic_industry = M('zhaopin/DicIndustry',0);
    //工作年限范围标准数组初始化
    $workyear = M('zhaopin/WorkYear',0);
    $workyear_list = $workyear->select();
    $min_work_list = array();
    foreach($workyear_list as $row){
        $min_work_list[$row['id']] = $row['min_workyear'];
    }
    //薪资范围标准数组初始化
    $salary = M('zhaopin/DicSalary',0);
    $salary_list = $salary->select();
    $min_salary_list = array();
    foreach($salary_list as $row){
        $min_salary_list[$row['id']] = $row['min_salary'];
    }

    //薪资范围标准数组初始化
    $size = M('zhaopin/DicCSize',0);
    $size_list = $size->select();
    $min_size_list = array();
    foreach($size_list as $row){
        $min_size_list[$row['id']] = $row['min_user'];
    }

    //公司层级标准数组初始化
    $level = M('zhaopin/DicCLevel',0);
    $evel_list = $level->select();
    $level_array = array();
    foreach($evel_list as $row){
        $level_array[$row['title']] = $row['id'];
    }

    while($i == 0 || !empty($data)){
        $data = $model->limit($limit,$i*$limit)->select();
        foreach($data as $row){
            var_export($row);
            $i_position = $MPosition->getColumns();
            $i_company = $MCompany->getColumns();
            //公司发展层级处理
            $level_id = isset($level_array[$row['financeStage']]) ? $level_array[$row['financeStage']] : 0 ;
            if(!$level_id){
                $level_id = $level->addKeyUp(array('title'=>$row['financeStage']));
            }

            //公司规模处理
            $i_position['work_year'] = $row['companySize'];
            if(strstr($row['workYear'],'-')){
                $work_year = explode('-',$row['workYear']);
                $work_min = current($work_year);
                foreach($min_work_list as $key=>$min_yaer){
                    if($min_yaer <= $work_min){
                        $i_position['workyear_id'] = $key;
                    }
                }
            }//=======================================
            //公司信息处理
            $i_company = array (
                'company_name' => $row['companyName'],
                'company_short_name' => $row['companyShortName'],
                'stage_level_id' => $level_id,
                'company_size_id' => 'int',
                'company_label' => $row['companyLabelList'],
                'add_time' => '',
            );
            var_export($i_company);
            //工作年限处理
            $i_position['work_year'] = $row['workYear'];
            if(strstr($row['workYear'],'-')){
                $work_year = explode('-',$row['workYear']);
                $work_min = current($work_year);
                foreach($min_work_list as $key=>$min_yaer){
                    if($min_yaer <= $work_min){
                        $i_position['workyear_id'] = $key;
                    }
                }
            }
            unset($row['workYear']);

            //拉钩数据录入
            $i_position['position_id'] = $row['positionId'];
            $i_position['data_from'] = 'lagou';

            //薪资范围处理
            $i_position['salary'] = $row['salary'];
            if(strstr($row['salary'],'-')){
                $salary = explode('-',$row['salary']);
                $min_salary = strstr(current($salary),'k') ? intval(current($salary))*1000 : intval(current($salary));
            } else {
                $min_salary = strstr(current($salary),'k') ? intval(current($salary))*1000 : intval(current($salary));
            }
            foreach($min_salary_list as $key=>$isalary){
                if($isalary <= $min_salary){
                    $i_position['salary_id'] = $key;
                }
            }
//            print_r($row);exit;

            if(strstr($row['industryField'],' · ')){
                $industrys = explode(' · ',$row['industryField']);
            } else {
                $industrys = array($row['industryField']);
            }

            foreach($industrys as $v){
                $industry = array('industry_title'=>$v);
                $ind_id = $dic_industry->fields('id')->where($industry)->simple();
                if(!intval($ind_id)){
                    $ind_id = $dic_industry->addKeyUp($industry);
                }
            }
            var_export($i_position);echo "\n";exit;
//            $id = $db->addKeyUp($row);
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