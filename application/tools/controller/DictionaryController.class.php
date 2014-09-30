<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:52
 */

class DictionaryController extends Controller {

    public function index(){
        if(!isset($_REQUEST['t'])){
            $_REQUEST['t'] =  'all';
        }
        if(!isset($_REQUEST['db'])){
            $_REQUEST['db'] =  1;
        }
        $datalist = C('SF_DB_CONNECT');
        foreach($datalist as $k=>$v){
            $database[] = array('name'=>$v['DB_NAME'],'value'=>$k);
        }
        C('db',$database);
        $remark = C('remark');
        $con1 = M('',$_REQUEST['db']);
        $data = array();
        if($_REQUEST['t'] == 'table'){
            $data = $this->getAllTable($con1, $remark);
        } else {
            $relate_table = C('relate_table');
            $relate_table_key = array_keys($relate_table);
            if(in_array($_REQUEST['t'],$relate_table_key)){
                if($relate_table[$_REQUEST['t']] <> array('*')){
                    $table_array = $relate_table[$_REQUEST['t']];
                } else {
                    $table_list = $con1->select('SHOW TABLE STATUS FROM '.$con1->db_name);
                    foreach($table_list as $row){
                        $table_array[] = array('name'=>$row['Name'],'commont'=>$row['Commont']);
                    }

                }
                $data = $this->getRelateTable($con1,$remark,$table_array);
            }
        }
        $this->assign('data',$data);
        $this->relateSqlData($con1);
        $this->display();
    }

    public function getAllTable($model,$remark){
        $data = $model->select('SHOW TABLE STATUS FROM '.$model->db_name);
        $table_data = array();
        if($data){
            foreach($data as $key=>$value){
                $i_data['Update_time'] = strtotime($value['Update_time']);
                $i_data['表名'] = $value['Name'];
                $i_data['注释'] = $value['Comment'] ? $value['Comment']:(isset($remark[$value['Name']][0])?$remark[$value['Name']][0]:'');
                $i_data['数据行数'] = $value['Rows'];
                $i_data['最后更新'] = $value['Update_time'];
                $result[] = $i_data;
            }
            $table['title'] = '表的列表';
            $table['remark'] = '以下是“'.$model->db_name.'”库中的所有表的信息';
            $table['data'] = $result;
            $table['columns'] = array_keys($i_data);
            $table['rule'] = array('数据行数'=>array('>','10000','red'),'Update_time'=>array('<',nowTime()-ONE_DAY*31,'yellow'));
            $table_data[] = $table;
        }
        $table_data['test'] = '<table style="float: right;position: fixed;right: 150px;top: 300px;"><tr><th>图例</th></tr><tr class="red"><td>数据行数>10000</td></tr><tr class="yellow"><td>最后更新时间大于31天</td></tr><tr><td>正常使用表格</td></tr></table>';
        $this->assign('start',1);
        return $table_data;
    }

    public function relateSqlData($model){
        $relate_sql = C('relate_sql');
        if(isset($relate_sql[$_REQUEST['t']]) && is_array($relate_sql[$_REQUEST['t']])){
            foreach($relate_sql[$_REQUEST['t']] as $key =>$sql){
                $i_data['title'] = $key;
                $i_data['remark'] = $sql;
                $i_data['data'] = $model->select($sql);
                if(is_array($i_data['data']) && !empty($i_data['data'])){
                    $i_data['columns'] = $model->getColumns();
                }
                $relate_data[] = $i_data;
            }
            $this->assign('relate_data',$relate_data);
        }
    }

    public function getRelateTable($model,$remark,$relate_table=array()){
        $result = array();
        foreach($relate_table as $key => $table){
            if(is_array($table)){
                $i_table_name = $table['name'];
                $i_table_reamrk = $table['commont'];
            } else {
                $i_table_name = $table;
            }
            $i_remark = $remark[$i_table_name];
            $i_table_reamrk = $i_table_reamrk ? $i_table_reamrk : $i_remark[0];
            $sql = 'SHOW FULL COLUMNS FROM '.$i_table_name;
            $data = $model->select($sql);
            $list = array();
            if(is_array($data)){
                foreach($data as $row){
                    $i_data['列名'] = $row['Field'];
                    $i_data['Null'] = $row['Null'];
                    $i_data['类型'] = $row['Type'];
                    $i_data['注释'] = $row['Comment'] ? $row['Comment']:(isset($i_remark[$row['Field']])?$i_remark[$row['Field']]:'');
                    $list[] = $i_data;
                }
            }

            $result[$key]['title'] = $i_table_name.'('.$i_table_reamrk.')';
            $result[$key]['data'] = $list;
            $result[$key]['columns'] = is_array($i_data)?array_keys($i_data):array();
        }
        $this->assign('start',0);
        return $result;
    }
} 