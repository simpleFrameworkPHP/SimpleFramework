<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */

class Model {
    public static $db;
    public function __construct($connect = '',$no = 0){
        return self::initDBConnect($connect,$no);
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

    public function query($sql,$no = 0){
        $data = array();
        $data = self::$db[$no]->query($sql);
        return $data;
    }

    public function getColumns($no = 0){
        if(isset(self::$db[$no])){
            return self::$db[$no]->columns;
        } else {
            return false;
        }
    }
} 