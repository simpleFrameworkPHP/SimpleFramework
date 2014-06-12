<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-11
 * Time: 下午4:52
 */

class TableInfo {

    public $tables;

    public function __construct(){
        $this->initTableInfo();
    }

    function initTableInfo($link_ID = 0){
        $sql = 'SHOW TABLES';
        $tables = M('',$link_ID)->select($sql);
        $dbname = C('sf_db_connect');
        $dbname = $dbname[0]['dbname'];
        foreach($tables as $value){
            $this->getColumnInfo($value['Tables_in_'.$dbname]);
        }
    }
    function getColumnInfo($table_name,$link_ID = 0){
        $sql = 'SHOW COLUMNS FROM '.$table_name;
        $columns = M('',$link_ID)->select($sql);
        foreach($columns as $value){
            $this->tables[$table_name][$value['Field']] = $value['Type'];
        }
    }

    /** 过滤表名----将不存在的表名滤除
     * @param string $table_name 表名，须填写表全称
     * @return string
     */
    public function filterTable($table_name){
        if(array_key_exists($table_name,$this->tables)){
            //该表名有效
            return $table_name;
        } else {
            //该表明无效
            return '';
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
                    $tables[$v] = $this->tables[$v];
                }
            } else {
                $tables = array();
            }
        } else {
            $tables = $this->tables;
        }
        foreach($tables as $table_name => $value){
            if(array_key_exists($column,$value)){
                //该列名有效
                $column = $table_name.'.'.$column;
                break;
            } else {
                //该列名无效
                $column = '';
            }
        }
        return $column;
    }
} 