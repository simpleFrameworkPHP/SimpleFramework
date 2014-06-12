<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */
//include CORE_PATH.'/class/TableInfo.class.php';
class Model {
    public static $db;//数据库链接数组
    public $option;//记录所有sql子句的数组，在执行时输出出来
    public $table = '';//model默认表名
    public $link_ID;//数据库链接ID，平时为0，多数据库链接时使用
    public $info;//array('表名'=>array('列名'))
    public function __construct($connect = '',$no = 0){
        $con = self::initDBConnect($connect,$no);
        if(isset($this->table) && $this->table != ''){
            //默认操作表
            $con->table = $this->table;
        }
        $this->link_ID = $no;
        return $con;
    }

    public static function initDBConnect($connect = '',$no = 0){
        if($connect == ''){
            $connect = C('SF_DB_CONNECT');
            $connect = $connect[$no];
        }
        if(!isset(self::$db[$no])){
            //创建对应的数据库链接；
            return self::$db[$no] = db::initDBCon($connect,$no);
        } else {
            return self::$db[$no];
        }
    }

    public function select($sql = ''){
        $data = array();
        if($sql == ''){
            $sql = $this->option;
        }

        $data = self::$db[$this->link_ID]->select($sql);
        return $data;
    }

    //获取sql语句中的列名
    public function getColumns(){
        if(isset(self::$db[$this->link_ID])){
            return self::$db[$this->link_ID]->columns;
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
            }
        }
        $TableInfo = new TableInfo($this->link_ID);
        foreach($table_list as $key=>$value){
            if(!$TableInfo->filterTable($value)){
                unset($table_list[$key]);
            }
        }
        $this->option['TABLE'] = implode(',',$table_list);
        return $this;
    }

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
                $TableInfo = new TableInfo($this->link_ID);
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
                    $field_v = $TableInfo->filterColumn($value,$table_array);
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
} 