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
    public static  function initDBCon($connect,$no=0){
        switch($connect['DBMODE']){
            case 'mysql':$db = new mysql($connect,$no=0);break;
            case 'mysqli':$db = new mysqli($connect,$no=0);break;
            default:$db = new mysql($connect,$no=0);
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