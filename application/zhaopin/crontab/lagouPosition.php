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
//        sleep(1);
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
    $ind_company = M('zhaopin/MIndCom',0);
    //工作年限范围标准数组初始化
    $workyear = M('zhaopin/WorkYear',0);
    $workyear_list = $workyear->select();
    $min_work_list = array();
    foreach($workyear_list as $row){
        $min_work_list[$row['id']] = $row['min_workyear'];
    }
    //薪资范围标准数组初始化
    $salary = M('zhaopin/DicSalary',0);
    $min_salary_list = $salary->getMinSalaryList();
    //薪资范围标准数组初始化
    $size = M('zhaopin/DicCSize',0);
    $min_size_list = $size->gitMinSizeList();
    //公司层级标准数组初始化
    $level = M('zhaopin/DicCLevel',0);
    $evel_list = $level->select();
    $level_array = array();
    foreach($evel_list as $row){
        $level_array[$row['level_title']] = $row['id'];
    }

    //行业初始化
    $dic_industry = M('zhaopin/DicIndustry',0);
    $industry = $dic_industry->fields('id,industry_title')->select();
    foreach($industry as $i_ind){
        $industry_list[$i_ind['industry_title']] = $i_ind['id'];
    }
    $MPositionType = M('zhaopin/DicPositionType',0);
    while($i == 0 || !empty($data)){
        $data = $model->limit($limit,$i*$limit)->select();
        foreach($data as $row){
            //公司发展层级处理
            $level_id = isset($level_array[$row['financeStage']]) ? $level_array[$row['financeStage']] : 0 ;
            if(!$level_id){
                $level_id = $level->addKeyUp(array('title'=>$row['financeStage']));
                $level_array[$row['financeStage']] = $level_id;
            }

            //公司规模处理
            if(strstr($row['companySize'],'-')){
                $size_list = explode('-',$row['companySize']);
                $size_min = current($size_list);
            } else {
                $size_min = intval($row['companySize']);
            }
            foreach($min_size_list as $key=>$min_size){
                if($min_size <= $size_min){
                    $size_id = $key;
                }
            }

            //公司信息处理
            $i_company = array (
                'company_name' => $row['companyName'],
                'company_short_name' => $row['companyShortName'],
                'stage_level_id' => $level_id,
                'company_size_id' => $size_id,
                'company_label' => $row['companyLabelList']
            );
            $company_id = $MCompany->fields('id')->where(array('company_short_name'=>$row['companyShortName']))->simple();
            if(!$company_id){
                $company_id = $MCompany->addKeyUp($i_company);
            }

            //行业初始化
            if(strstr($row['industryField'],' · ')){
                $industrys = explode(' · ',$row['industryField']);
            } else {
                $industrys = array($row['industryField']);
            }
            $ind_ids = array();
            foreach($industrys as $v){
                $industry = array('industry_title'=>trim($v));
                $ind_id = $industry_list[trim($v)];
                if(!intval($ind_id)){
                    $ind_id = $dic_industry->addKeyUp($industry);
                }
                if($ind_id == 0){
                    var_dump(trim($v));
                    foreach($industry_list as $k=>$a){
                        var_dump($k,$a);
                    }

                    echo $v."\n";exit;
                }
                $ind_ids[] = $ind_id;
            }
            if(!empty($ind_ids)){
                foreach($ind_ids as $ind_id){
                    //添加公司&行业关系表数据
                    $ind_comp = array('industry_id'=>$ind_id,'company_id'=>$company_id);
                    $ind_company->addKeyUp($ind_comp);
                }
            }
            $i_position['company_id'] = $company_id;
            $i_position['education'] = $row['education'];
            $i_position['position_first_type_id'] = 0;
            if($row['positionFirstType'] <> ''){
                $i_position['position_first_type_id'] = $MPositionType->addKeyUp(array('pos_name'=>$row['positionFirstType'],'pos_type'=>2));
            }
            $i_position['position_type_id'] = $MPositionType->addKeyUp(array('pos_name'=>$row['positionType'],'pos_type'=>3,'pid'=>$i_position['position_first_type_id']));
            //工作年限处理
            $i_position['work_year'] = $row['workYear'];
            if(strstr($row['workYear'],'-')){
                $work_year = explode('-',$row['workYear']);
                $work_min = current($work_year);
            } else {
                $work_min = intval($row['workYear']);
            }
            foreach($min_work_list as $key=>$min_yaer){
                if($min_yaer <= $work_min){
                    $i_position['workyear_id'] = $key;
                }
            }

            //拉钩数据录入
            $i_position['position_id'] = $row['positionId'];
            $i_position['data_from'] = 'lagou';
            $i_position['city'] = $row['city'];
            $i_position['create_time'] = $row['createTime'];
            $i_position['position_name'] = $row['positionName'];
            $i_position['position_advantage'] = $row['positionAdvantage'];
            $i_position['job_nature'] = $row['jobNature'];
            $i_position['leader_name'] = $row['leaderName'];
            $i_position['add_time'] = date('Y-m-d');


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
            $id = $MPosition->addKeyUp($i_position);
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