<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/28
 * Time: 下午3:51
 */
class lagou{
    public static function addData(){
        $today = date('Y-m-d');
        $star_time = time();
        $db_num = 0;
        $citys = M('zhaopin/DicArea',$db_num)->fields('area_name')->where(array('type_name'=>'市'))->select();
        $big_city = array('北京','上海','深圳','广州','武汉','南京','成都','杭州');
        $hy = M('zhaopin/DicIndustry',$db_num)->fields('industry_title')->select();
        $hy = reIndexArray($hy,'industry_title');
        $hy = array_keys($hy);
        $count_city = count($citys);
        $i = 0;
        foreach($citys as $city){
            $i_city = str_replace('市','',$city['area_name']);
            $city_str = "<span class='red'>【{$count_city}:".$i++."】$i_city 数据处理中。。。。已用时</span>";
            webLongEcho("|".$city_str.(time() - $star_time));
            if(in_array($i_city,$big_city)){
                foreach($hy as $ihy){
                    webLongEcho("|".$city_str.(time() - $star_time));
                    $where = '&city='.$i_city.'&hy='.$ihy;
                    self::addDataByWhere($where);
                }
            } else {
                $i_city = '&city='.$i_city;
                self::addDataByWhere($i_city);
            }

        }
        $end_time = time();
        M('zhaopin/DataLog',$db_num)->add(array('type'=>'view_lagou_position','content'=>$today,'remark'=>'添加拉钩数据,运行时间：'.($end_time - $star_time)));
    }
    /**
     * 添加拉钩数据
     */
    public static function addDataByWhere($url_city){
        $today = date('Y-m-d');
        $num = 0;
        $i = 1;
        $json = array();
        $url_str = "http://www.lagou.com/jobs/positionAjax.json?px=new&first=false&pn=";
        $db_num = 0;
        $model = M('',$db_num);
//    webLongEcho("拉勾网数据处理中......");
        while(($json['success'] && !empty($json['content']['result'])) || $i == 1){
            $url = $url_str."$i";
            $url = $url . $url_city;
            $json = getHtmlData($url);
            $json = json_decode($json,true);
            $data = $json['content']['result'];
            foreach($data as $row){
                $row['companyLabelList'] = implode(' ',$row['companyLabelList']);
                foreach($row as $column=>$value){
                    $row[$column] = trim($value);
                }
                $row['addTime'] = $today;
                $model->table(array("view_lagou_position"))->addKeyUp($row);
            }
            $num += count($data);
            if(!($i%10)){
                webLongEcho('|处理页数:'.$i." 已数据处理:".$num."条");
                sleep(1);
            }
            if($i > 334){
                M('zhaopin/DataLog',$db_num)->add(array('type'=>'view_lagou_position','content'=>$url_city,'remark'=>"{$url_city}已经超过334页了，需要添加新维度重新分析一下"));
            }
            $i++;
        }
        echo "|数据拉取任务处理完毕";
    }

    /**
     * 处理拉钩数据
     * @param $today
     */
    public static function initData($today){
        $star_time = time();
        webLongEcho('正在处理拉勾网数据。。。。');
        $db_num = 0;
        $model = M('',$db_num);
        $where = array();
        $where['addTime'] = $today;
        $model->table(array("view_lagou_position"));
        $i = 0;
        $num = 0;
        $limit = 1000;
        $data = array();
        $MPosition = M('zhaopin/MPosition',$db_num);
        $MCompany = M('zhaopin/MCompany',$db_num);
        $ind_company = M('zhaopin/MIndCom',$db_num);
        //工作年限范围标准数组初始化
        $workyear = M('zhaopin/WorkYear',$db_num);
        $workyear_list = $workyear->select();
        $min_work_list = array();
        foreach($workyear_list as $row){
            $min_work_list[$row['id']] = $row['min_workyear'];
        }
        //薪资范围标准数组初始化
        $salary = M('zhaopin/DicSalary',$db_num);
        $min_salary_list = $salary->getMinSalaryList();
        //薪资范围标准数组初始化
        $size = M('zhaopin/DicCSize',$db_num);
        $min_size_list = $size->gitMinSizeList();
        //公司层级标准数组初始化
        $level = M('zhaopin/DicCLevel',$db_num);
        $evel_list = $level->select();
        $level_array = array();
        foreach($evel_list as $row){
            $level_array[$row['level_title']] = $row['id'];
        }

        //行业初始化
        $dic_industry = M('zhaopin/DicIndustry',$db_num);
        $industry = $dic_industry->fields('id,industry_title')->select();
        foreach($industry as $i_ind){
            $industry_list[$i_ind['industry_title']] = $i_ind['id'];
        }
        $MPositionType = M('zhaopin/DicPositionType',$db_num);
        $p_type_list = $MPositionType->getIdListByType(3);
        $p_first_type_list = $MPositionType->getIdListByType(2);
//    webLongEcho(var_dump($p_first_type_list).var_dump($p_type_list));exit;
        while($i == 0 || !empty($data)){
            $data = $model->where($where)->limit($limit,$i*$limit)->select();
            foreach($data as $row){
                $num++;
                //公司发展层级处理
                $level_id = isset($level_array[$row['financeStage']]) ? $level_array[$row['financeStage']] : 11 ;//默认不需要融资
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
                    $ind_id = isset($industry_list[trim($v)]) ? $industry_list[trim($v)] : 0;
                    if(!intval($ind_id) && $v != ''){
                        //添加新行业
                        $ind_id = $dic_industry->addKeyUp($industry);
                        $industry_list[trim($v)] = $ind_id;
                    }
                    $ind_ids[] = $ind_id;
                }
                if(!empty($ind_ids)){
                    foreach($ind_ids as $ind_id){
                        //添加公司&行业关系表数据
                        $ind_comp = array('industry_id'=>$ind_id,'company_id'=>$company_id);
                        $find = $ind_company->where($ind_comp)->find();
                        if(empty($find)){
                            $ind_company->addKeyUp($ind_comp);
                        }
                    }
                }
                $i_position['company_id'] = $company_id;
                $i_position['education'] = $row['education'];
                $i_position['position_first_type_id'] = 0;
                if($row['positionFirstType'] <> ''){
                    $ipt = array('pos_name'=>$row['positionFirstType'],'pos_type'=>2);
//                $i_position['position_first_type_id'] = $MPositionType->fields('id')->where($ipt)->simple();
                    if(!isset($p_first_type_list[$row['positionFirstType']])){
                        $i_position['position_first_type_id'] = $MPositionType->addKeyUp($ipt);
                        $p_first_type_list[$row['positionFirstType']] = $i_position['position_first_type_id'];
                    }
                    $i_position['position_first_type_id'] = $p_first_type_list[$row['positionFirstType']];
                }
                $i_position['position_type_id'] = 0;
                if($row['positionType'] <> ''){
                    $ipt = array('pos_name'=>$row['positionType'],'pid'=>$i_position['position_first_type_id'],'pos_type'=>3);
//                $i_position['position_type_id'] = $MPositionType->fields('id')->where($ipt)->simple();
                    if(!isset($p_type_list[$row['positionType']])){
                        $i_position['position_type_id'] = $MPositionType->addKeyUp($ipt);
                        $p_type_list[$row['positionType']] = $i_position['position_type_id'];
                    }
//                webLongEcho(var_dump($p_first_type_list).var_dump($p_type_list));exit;
                    $i_position['position_type_id'] = $p_type_list[$row['positionType']];
                }
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
                    $max_salary = strstr(current($salary),'k') ? intval(end($salary))*1000 : intval(end($salary));
                } else {
                    preg_match_all('/[0-9]+k?/',$row['salary'],$salary);
                    $salary = current(current($salary));
                    $min_salary = strstr($salary,'k') ? intval($salary)*1000 : intval($salary);
                }
                foreach($min_salary_list as $key=>$isalary){
                    if($isalary <= $min_salary){
                        $i_position['salary_id'] = $key;
                    } else if ($isalary <= $max_salary) {

                    }
                }
                $id = $MPosition->addKeyUp($i_position);
            }
            webLongEcho("|已处理".$num."条数据。。");
            $i++;
        }
        webLongEcho("|处理完毕");
        $run_time = time() - $star_time;
        M('zhaopin/DataLog',$db_num)->add(array('type'=>'model_position','content'=>$today,'remark'=>'初始化职位数据,运行时间:'.$run_time.'(秒)'));
    }
}
