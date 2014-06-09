<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */

class Model {
    public static $db;
    public $option;
    public $table = '';
    public $link_ID;
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
            $connect = C('sf_db_connect');
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
    /**设置查询的列
     * @params str|array option 列名参数，当它是数组时key不是数字的时候key为输出列名
     */
    public function fields($option){
        $this->removeMark($option);
        if(strstr($option,',')){
            $option = explode(',',$option);
        }
        if(is_array($option)){
            foreach($option as $key => $value){
                if(is_int($key)){
                    $this->rField($value);
                } else {
                    $this->rField($value.' as '.$key);
                }
            }
        } else {
            $this->rField($option);
        }
        return $this;
    }

    public function  rField($option){
        //自动判断列是否存在所查表中
        $columns = $this->getAllColumns();
        if($no = strpos($option,'.')){
            $table = substr($option,0,$no);
            if(!isset($columns[$table])){
                //表名不符合
            }
            $option = explode('.',$option);
            if(in_array($option[1],$columns[$table])){
                $result = $table.$option[1];
            }
        } elseif($option == '*'){
            $result = $option;
        } else {
            foreach($columns as $key => $column_array){
                //处理as的情况
                $no = strpos($option,' ');
                $field = substr($option,0,$no);
                if(in_array($field,$column_array)){
                    //取第一张表中的列
                    $result = $key.'.'.$option;
                    break;
                }
            }
        }
        if($this->option['FIELD'] != ''){
            $this->option['FIELD'] .= ','.$result;
        } else {
            $this->option['FIELD'] = $result;
        }
    }

    //获取sql语句中的列名
    public function getColumns(){
        if(isset(self::$db[$this->link_ID])){
            return self::$db[$this->link_ID]->columns;
        } else {
            return false;
        }
    }

    /**
     * 标准化sql表
     * @param string|array $option = array('表简称'=>'表名')
     */
    public function tables($option = ''){
        $this->removeMark($option);
        if($this->table !=''){
            $this->rTable($this->table);
        }
        if($option != ''){
            if(strstr($option,',')){
                $option = explode(',',$option);
            }
            if(is_array($option)){
                foreach($option as $key => $value){
                    if(is_int($key)){
                        $this->rTable($value);
                    } else {
                        $this->rTable($value.' as '.$key);
                    }
                }
            } else {
                $this->rTable($option);
            }
        }
        return $this;
    }

    public function rTable($option){
        $result = '';
        //辨别是否为有效表
        if(self::$db[$this->link_ID]->select_db){
            //该sql语句是存在于数据库内部
            $result = $option;
        } else {
            //该sql语句可以跨越相同链接的数据库执行，需要在表明前面添加库名
            $result = $option;
        }
        $this->option['TABLE'] .= ($this->option['TABLE'] != '') ? ','.$result : $result;
    }

    //获取所有关联表中的列名
    public function getAllColumns(){
        if(!isset($this->option['TABLE'])){
            $this->tables();
        }
        if(strstr($this->option['TABLE'],',')){
            $tables = explode(',',$this->option['TABLE']);
            foreach($tables as $k=>$v){
                if(strstr($v,'as')){
                    $tables[$k] = explode(' as ',$v)[0];
                }
            }
        } else {
            if(strstr($this->option['TABLE'],'as')){
                $tables = explode(' as ',$this->option['TABLE'])[0];
            } else {
                $tables = $this->option['TABLE'];
            }
        }
        $table_column = S('table_column');
        if(is_array($tables)){
            foreach($tables as $v){
                if(isset($table_column[$v])){
                    $columns[$v] = $table_column[$v];
                } else {
                    //预留---根据表来获取列
                    $columns[$v] = self::$db[$this->link_ID]->getColumns($v);
                    $table_column[$v] = $columns[$v];
                    S('table_column',$table_column);
                }
            }
        } else {
            if(isset($table_column[$tables])){
                $columns[$tables] = $table_column[$tables];
            } else {
                //预留---根据表来获取列
                $columns[$tables] = self::$db[$this->link_ID]->getColumns($tables);
                $table_column[$tables] = $columns[$tables];
                S('table_column',$table_column);
            }
        }
        return $columns;
    }

    function removeMark($option,$mark = ';'){
        //去掉不能存在的符号——目前仅知道；为不可存在的符号
        if($i = strpos($option,$mark)){
            $option = substr($option,0,$i);
        }
        return $option;
    }
} 