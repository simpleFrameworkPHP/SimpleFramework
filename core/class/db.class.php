<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午4:58
 */
class db {
    public $con;
    public $link_ID;
    public $sql_str;
    public $result_rows;
    public $columns;
    public $table;
    public $select_db = false;
    public $select_sql = '%FIELD% %TABLE% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';
    public static  function initDBCon($host,$user,$pass,$db_name,$port,$mode,$no=0){

        switch($mode){
            case 'mysql':$db = new DBMysql($host,$user,$pass,$db_name,$port,$no);break;
            case 'mysqli':$db = new DBMysqli($host,$user,$pass,$db_name,$port,$no);break;
            default:$db = new DBMysqli($host,$user,$pass,$db_name,$port,$no);
        }
        return $db;
    }

    public function select($option){
        $data = array();
        if(is_array($option)){
            $sql = str_replace(array('%FIELD%','%TABLE%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'),
                array(
                    $this->replaceSql(empty($option['FIELD']) ? '*' : $option['FIELD'],'SELECT '),
                    $this->replaceSql(empty($option['TABLE']) ? $this->table : $option['TABLE'],' FROM '),
                    $this->replaceSql(empty($option['WHERE']) ? '' : $option['WHERE'],' HWERE '),
                    $this->replaceSql(empty($option['GROUP']) ? '' : $option['GROUP'],' GROPU BY '),
                    $this->replaceSql(empty($option['HAVING']) ? '' : $option['HAVING'],' HAVING '),
                    $this->replaceSql(empty($option['ORDER']) ? '' : $option['ORDER'],' ORDER BY '),
                    $this->replaceSql(empty($option['LIMIT']) ? '' : $option['LIMIT'],' LIMIT '),
            ),$this->select_sql);
            $data = $this->query($sql);
        } else {
            $data = $this->query($option);
        }
        return $data;
    }

    public function replaceSql($value = '',$mode){
        if($value == ''){
            return '';
        } else {
            return $mode.$value;
        }
    }

    public function sqlLog($str,$type){
        sflog($str,$type,'sql');
    }

    public function errorPage($msg,$info,$error_code){
        errorPage($msg,$info,$error_code);
    }
} 