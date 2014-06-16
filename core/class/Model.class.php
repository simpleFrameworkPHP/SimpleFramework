<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */
class Model{
    public $db;//数据库链接数组
    public $option;//记录所有sql子句的数组，在执行时输出出来
    public $table = '';//model默认表名
    public $link_ID;//数据库链接ID，平时为0，多数据库链接时使用
    public $tables_info;//array('表名'=>array('列名'))
    public $db_name;

    /**
     * @param $host 数据库域名或ip
     * @param $user 用户名
     * @param $pass 密码
     * @param $db_name  数据库名
     * @param $port 端口号
     * @param $mode 链接模式
     * @param int $no   链接id
     */
    public function __construct($host,$user,$pass,$db_name,$port,$mode,$no = 0){
        $con = $this->initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no);
        if(isset($this->table) && $this->table != ''){
            //默认操作表
            $con->table = $this->table;
        }
        $this->db_name = $db_name;
        $this->link_ID = $no;
        if($this->tables_info = S('DB_INFO','','DB_INFO_'.$no))
            ;
        else
            $this->initTableInfo($no);
        return $con;
    }

    public function initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no = 0){
            //创建对应的数据库链接；
            return $this->db = Db::initDBCon($host,$user,$pass,$db_name,$port,$mode,$no);
    }

    public function select($sql = ''){
        $data = array();
        if($sql == ''){
            $sql = $this->option;
        }
        $data = $this->db->select($sql);
        return $data;
    }


    function initTableInfo($link_ID = 0){
        $sql = 'SHOW TABLES';
        $tables = $this->db->select($sql);
        if(is_array($tables) && isset($this->db_name)){
            foreach($tables as $value){
                $this->getColumnInfo($value['Tables_in_'.$this->db_name]);
            }
            //存储数据结构
            S('DB_INFO',$this->tables_info,'DB_INFO_'.$link_ID);
        }
    }
    function getColumnInfo($table_name,$link_ID = 0){
        $sql = 'SHOW COLUMNS FROM '.$table_name;
        $columns = $this->db->select($sql);
        if($columns && is_array($columns)){
            foreach($columns as $value){
                $this->tables_info[$table_name][$value['Field']] = substr($value['Type'],0,strpos($value['Type'],'('));
            }
        }
    }

    /** 过滤表名----将不存在的表名滤除
     * @param string $table_name 表名，须填写表全称
     * @return string
     */
    public function filterTable($table_name){
        if(is_array($this->tables_info) && array_key_exists($table_name,$this->tables_info)){
            //该表名有效
            return $table_name;
        } else {
            //该表明无效
            return false;
        }
    }

    /** 过滤列名----将不存在的列名滤除
     * @param $column 列名
     * @param array $table_list 表名数组
     * @return mixed
     */
    public function filterColumn($column,$table_list = array()){
        //获取查询的表
        if(count($table_list)){
            if(is_array($table_list)){
                foreach($table_list as $v){
                    $tables[$v] = $this->tables_info[$v];
                }
            } else {
                $tables = array();
            }
        } else {
            $tables = $this->tables_info;
        }
        foreach($tables as $table_name => $value){
            if(array_key_exists($column,$value)){
                //该列名有效
                $column = $table_name.'.'.$column;
                break;
            } else {
                //该列名无效
                $column = false;
            }
        }
        return $column;
    }

    //获取sql语句中的列名
    public function getColumns(){
        if(isset($this->db->columns)){
            return $this->db->columns;
        } else {
            return false;
        }
    }

    public function table($tables = ''){
        $table_list = array();
        if($tables == ''){
            $table_list[] = $this->table;
        } else {
            if(!is_array($tables)){
                if(strstr($tables,',')){
                    $table_list = explode(',',$tables);
                } else {
                    $table_list[] = $tables;
                }
                $table_list[] = $this->table;
            }
        }
        foreach($table_list as $key=>$value){
            if(!$this->filterTable($value)){
                unset($table_list[$key]);
            }
        }
        $this->option['TABLE'] = implode(',',$table_list);
        return $this;
    }

    //优化--as情况未考虑
    public function fields($fields = '',$auto_check = true){
        if($auto_check){
            if($fields == '' || $fields == '*'){
                $this->option['FIELD'] = '*';
            } else{
                $field_list = array();
                if(!is_array($fields)){
                    if(strstr($fields,',')){
                        $field_list = explode(',',$fields);
                    } else {
                        $field_list[] = $fields;
                    }
                } else {
                    $field_list = $fields;
                }
                if(!isset($this->option['TABLE'])){
                    if($this->table <> ''){
                        $table_array[] = $this->table;
                    } else {
                        errorPage('没有表格啦','你需要先使用table方法或设置Model中的$table值来设置查询的表格');
                    }
                } else {
                    if(strstr($this->option['TABLE'],',')){
                        $table_array = explode(',',$this->option['TABLE']);
                    } else {
                        $table_array[] = $this->option['TABLE'];
                    }
                }
                $field_array = array();
                foreach($field_list as $key=>$value){
                    //优化--在此处考虑 as 渲染
                    $field_v = $this->filterColumn($value,$table_array);
                    if($field_v){
                        $field_array[] = $field_v;
                    }
                }
                if(count($field_array)){
                    $this->option['FIELD'] = implode(',',$field_array);
                } else {
                    return false;
                }
            }
        } else {
            $this->option['FIELD'] = is_array($fields) ? implode(',',$fields) : $fields;
        }
        return $this;
    }

    //优化--列是否有效有待确认
    public function where($where){
        if(is_array($where)){
            $where_array = array();
            foreach($where as $key => $value){
                if(is_int($key)){
                    //该情况说明表达式直接写在$value中
                    $where_array[] = $value;
                } else {
                    if(is_array($value)){
                        if(in_array($value[0],array('<>','!=','<','>','<=','>='))){
                            $where_array[] = $key .$value[0].$this->replaceValue($key, $value[1]);
                        } else if(in_array( strtoupper($value[0]),array('IN','NOT IN','NOT NULL'))) {
                            $term = strtoupper($value[0]);
                            unset($value[0]);
                            $where_array[] = $key.' '.strtoupper($value[0]).' ('.implode(' , ',$this->replaceValue($key,$value)).')';
                        }
                    } else {
                        //预留--条件不符合
                    }
                }
            }
            $where_str = implode(' AND ',$where_array);
        } else {
            $where_str = $where;
        }
        $this->option['WHERE'] = $where_str;
        return $this;
    }

    public function replaceValue($field,$value){
        if(is_string($value)){
            $value = '\''. addslashes($value) .'\'';
        } else if(is_array($value)) {
            $value = array_map('replaceValue',$value);
        }
        return $value;
    }
} 