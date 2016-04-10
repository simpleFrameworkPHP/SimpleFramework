<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午8:44
 */

class PositionController extends BaseController {

    var $db_num = 0;

    public function showData($data,$init_where){
        foreach($data as $item){
            $position_type_ids[] = $item['position_type_id'];
            $position_type_ids[] = $item['position_first_type_id'];
            $company_ids[] = $item['company_id'];
        }
        $DicPT = M('zhaopin/DicPositionType',$this->db_num);
        $position_types = $DicPT->getPNameListInId($position_type_ids);
        $DicC = M('zhaopin/MCompany',0);
        $company_list = $DicC->getCNameListInId($company_ids);
        $init_where['company_id'] = array_merge(array('in'),$company_ids);
        $company_cp_list = M('zhaopin/MPosition',$this->db_num)->fields('company_id,count(company_id)',false)->where($init_where)->group('company_id')->select();
        $company_cp = array();
        foreach($company_cp_list as $row)
            $company_cp[$row['company_id']] = $row['count(company_id)'];
        foreach($data as $key => $item){
            $row = array();
            $row['序号'] = $key;
            $row['职位名称'] = "<a class='a_position' title='{$item['position_name']}' target='_black' href='".CommonController::getUrl($item['position_id'],$item['data_from'])."'>{$item['position_name']}</a>";
            $row['城市'] = $item['city'];
            $row['职位类型'] = $position_types[$item['position_first_type_id']]."-".$position_types[$item['position_type_id']];
            $class_str = $item['salary_id'] > 2 ? "class='red'" : '';
            $row['薪酬'] = "<span {$class_str}>".$item['salary']."</span>";
            $row['工作年限'] = $item['work_year'];
            $row['学历'] = $item['education'];
            $row['创建时间'] = date('Y-m-d',strtotime($item['create_time']));
            $class_str = $company_cp[$item['company_id']] > 5 ? "class='red a_position'" : "class='a_position'";
            $c_url = CommonController::getCompanyUrl($item['company_id'],$item['data_from']);
            $s_url = H('',array('company_name'=>$company_list[$item['company_id']]));
            $row['公司'] = "<a target='_black' title='{$company_list[$item['company_id']]}' {$class_str} href='{$c_url}'>".$company_list[$item['company_id']]."</a><a target='_black' {$class_str} href='{$s_url}'>({$company_cp[$item['company_id']]})</a>";
            $row['职位分析'] = "<a href='".H('zhaopin/position/parsing',array('position_id'=>$item['position_id']))."'>分析详情</a>";
            $row['职位优势'] = "<span class='a_position' title='{$item['position_advantage']}' >{$item['position_advantage']}</span>";
//            $row['工作类型'] = $item['job_nature'];
            $row['直属领导'] = "<span class='s80'>{$item['leader_name']}</span>";
            $list[$key] = $row;
        }
        $columns = array_keys(current($list));
        $this->assign('columns',$columns);
        $this->assign('data',$list);
    }

    public function parsing(){
        $mposition = M('zhaopin/MPosition',$this->db_num);
        $init_where['add_time'] = CommonController::getLastLogTime('model_position');
        $show = false;
        if($_REQUEST['position_id']){
            //本身职位的信息
            $where = array_merge($init_where,array('position_id'=>$_REQUEST['position_id']));
            $info = $mposition->where($where)->find();


            //工作年限列表
            $workyearlist = CommonController::showWorkYear();
            //薪酬范围&平均薪资列表
            $Dsalary = M('zhaopin/DicSalary',$this->db_num);
            $data = $Dsalary->fields(array('id','title','min_salary','max_salary'))->select();

            foreach($data as $value){
                $salary[$value['id']] = $value['title'];
                $vagSalary[$value['id']] = ($value['min_salary'] + $value['max_salary']) / 2;
            }
            //公司维度数据准备
            $where = $init_where;
            $where[] = "company_id={$info['company_id']} or city='{$info['city']}' or position_first_type_id={$info['position_first_type_id']}";
            //同公司薪酬分布数据准备
            $c_salary_cp = array();
            //同公司工作年限招聘数据准备
            $c_workyear_cp = array();
//            //城市维度数据准备
//            $ct_salary_cp = array();
//            //同行业数据准备
//            $ind_salary_cp = array();
            //同行业&&同城数据准备
            $ctind_salary_cp = array();
            //同公司&&同城数据准备
            $c_ct_salary_cp = array();
            $i = 0;
            $page = 40000;
            while($i == 0 || !empty($comp_list)){
                $comp_list = $mposition->fields('company_id,city,position_first_type_id,salary_id,workyear_id,position_type_id')
                    ->where($where)->limit($page,$i * $page)->select();
                $i++;
                foreach($comp_list as $row){
                    if($row['company_id'] == $info['company_id']){
                        //同公司职位数分布
                        $c_salary_cp[$row['salary_id']]++;
                        $c_workyear_cp[$row['workyear_id']]++;
                    }
                    if($row['company_id'] == $info['company_id'] && $row['city'] == $info['city'] && $row['position_type_id'] == $info['position_type_id']){
                        //同城同公司职位数分布
                        $c_ct_salary_cp[$row['salary_id']]++;
                    }
                    if($row['city'] == $info['city'] && $row['position_type_id'] == $info['position_type_id']){
                        //同行业同城薪资分布
                        $ctind_salary_cp[$row['salary_id']]++;
                    }
//                if($row['city'] == $info['city']){
//                    //同城职位数统计
//                    $ct_salary_cp[$row['salary_id']]++;
//                }
//                if($row['position_first_type_id'] == $info['position_first_type_id']){
//                    //同行业职位数统计
//                    $ind_salary_cp[$row['salary_id']]++;
//                }
                }
            }


//            echo '<pre>';
//            var_dump($ct_salary_cp,$ind_salary_cp,$ctind_salary_cp);
//            print_r($info);exit;
            $template['pie1'] = $this->showPie1($c_workyear_cp, $workyearlist,$c_salary_cp, $salary);
            $template['pie2'] = $this->showPie2($c_ct_salary_cp,$ctind_salary_cp, $salary);
            $this->assign('template',$template);
            $this->assign('info',$this->showPositionInfo($info));
            $this->showPositionListByCid($info['company_id']);
            $show = true;
        }
        $this->assign('show',$show);
        $this->display();
    }

    function showPie1($c_workyear_cp, $workyear,$c_salary_cp,$salary){
        $item_style = array('normal'=>array('label'=>array('show'=>false),'labelLine'=>array('show'=>false)));
        $init_style = array('type'=>'pie','roseType'=>'radius','width'=>'38%','max'=>40);
        $data = array();
        foreach($c_workyear_cp as $workyear_id => $cp){
            $data[$workyear_id] = array('name'=>$workyear[$workyear_id],'value'=>$cp);
            $item1[$workyear_id] = $workyear[$workyear_id];
        }
        ksort($data);
        ksort($item1);
        $list[] = array_merge($init_style,array('name'=>'年限分布','radius'=>array(20,100),'center'=>array('22%',180),'itemStyle'=>$item_style,'data'=>array_values($data)));
        $data = array();
        foreach($c_salary_cp as $salary_id => $cp){
            $data[$salary_id] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item2[$salary_id] = $salary[$salary_id];
        }
        ksort($data);
        ksort($item2);
        $item = array_merge($item1,$item2);
        $list[] = array_merge($init_style,array('name'=>'薪酬分布','radius'=>array(30,100),'center'=>array('70%',180),'data'=>array_values($data)));
        return $this->showPieEcharts($list,$item,'pie1','【同公司】公司内部整体分析');
    }

    function showPie2($c_ct_salary_cp,$ctind_salary_cp, $salary){
        $item_style = array('normal'=>array('label'=>array('show'=>false),'labelLine'=>array('show'=>false)));
        $init_style = array('type'=>'pie','roseType'=>'radius','width'=>'38%','max'=>40);
        $data = array();
        foreach($c_ct_salary_cp as $salary_id => $cp){
            $data[$salary_id] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item[$salary_id] = $salary[$salary_id];
        }
        ksort($data);
        $list[] = array_merge($init_style,array('name'=>'同公司','radius'=>array(20,100),'center'=>array('22%',180),'itemStyle'=>$item_style,'data'=>array_values($data)));
        $data = array();
        foreach($ctind_salary_cp as $salary_id => $cp){
            $data[$salary_id] = array('name'=>$salary[$salary_id],'value'=>$cp);
            $item[$salary_id] = $salary[$salary_id];
        }
        ksort($data);
        ksort($item);
        $list[] = array_merge($init_style,array('name'=>'同行业','radius'=>array(30,100),'center'=>array('70%',180),'data'=>array_values($data)));
        $item = array_values(array_unique($item));
        return $this->showPieEcharts($list,$item,'pie2','【同城】薪酬分布');
    }

    public function showPositionInfo($info){
        $mpt = M('zhaopin/DicPositionType',$this->db_num);
        $mpt_info = $mpt->where(array('id'=>array('in',$info['position_type_id'],$info['position_first_type_id'])))->select();
        $mpt_info = reIndexArray($mpt_info,'id');
        $info['position_type_id'] = $mpt_info[$info['position_type_id']]['pos_name'];
        $info['position_first_type_id'] = $mpt_info[$info['position_first_type_id']]['pos_name'];
        $info['url'] = CommonController::getUrl($info['position_id'],$info['data_from']);
        $info['data_from'] = CommonController::getFrom($info['data_from']);
        $mc = M('zhaopin/MCompany',$this->db_num);
        $mc_info = $mc->where(array('id'=>$info['company_id']))->find();
        $info['company_name'] = $mc_info['company_name'];
        $info['company_short_name'] = $mc_info['company_short_name'];
        $info['company_label'] = $mc_info['company_label'];
        $info['stage_level'] = M('zhaopin/DicCLevel',$this->db_num)->fields('level_title')->where(array('id'=>$mc_info['stage_level_id']))->simple();
        $info['company_size'] = M('zhaopin/DicCSize',$this->db_num)->fields('size_title')->where(array('id'=>$mc_info['company_size_id']))->simple();
//        echo '<pre>';var_dump($mc_info);exit;
        return $info;
    }

    public function showPositionListByCid($cid){
        //获取最后一次抓取数据的时间
        $where['add_time'] = CommonController::getLastLogTime('model_position');
        $where['company_id'] = $cid;
        $model = M('zhaopin/MPosition',$this->db_num);
        $data = $model->where($where)->limit(1000)->select();
        if(!empty($data)){
            $init_where['add_time'] = $where['add_time'];
            $this->showData($data,$init_where);
        }
    }
} 