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
        switch($connect['dbmode']){
            case 'mysql':$db = &new mysql($connect,$no=0);break;
            case 'mysqli':$db = &new mysqli($connect,$no=0);break;
            default:$db = &new mysql($connect,$no=0);
        }
        return $db;
    }

    public function select($option){
        $data = array();
        if(is_array($option)){
            $sql = str_replace(array('%FIELD%','%TABLE%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'),
                array(
                    replaceSql(empty($option['FIELD']) ? $option['FIELD'] : '*','SELECT '),
                    replaceSql(empty($option['TABLE']) ? $option['TABLE'] : $this->table,' FORM '),
                    replaceSql(empty($option['WHERE']) ? $option['WHERE'] : '',' HWERE '),
                    replaceSql(empty($option['GROUP']) ? $option['GROUP'] : '',' GROPU BY '),
                    replaceSql(empty($option['HAVING']) ? $option['HAVING'] : '',' HAVING '),
                    replaceSql(empty($option['ORDER']) ? $option['ORDER'] : '',' ORDER BY '),
                    replaceSql(empty($option['LIMIT']) ? $option['LIMIT'] : '',' LIMIT '),
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