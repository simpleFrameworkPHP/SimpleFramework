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
    public $query_str;
    public $result_rows;
    public $columns;
    public static  function initDBCon($connect,$no=0){
        switch($connect['dbmode']){
            case 'mysql':$db = &new mysql($connect,$no=0);break;
            case 'mysqli':$db = &new mysqli($connect,$no=0);break;
            default:$db = &new mysql($connect,$no=0);
        }
        return $db;
    }

    public function sqlLog($str,$type){
        sflog($str,$type,'sql');
    }

    public function errorPage($msg,$info,$error_code){
        errorPage($msg,$info,$error_code);
    }
} 