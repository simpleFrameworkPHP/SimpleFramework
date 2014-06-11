<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */

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

    //获取sql语句中的列名
    public function getColumns(){
        if(isset(self::$db[$this->link_ID])){
            return self::$db[$this->link_ID]->columns;
        } else {
            return false;
        }
    }

    function removeMark($option,$mark = ';'){
        //去掉不能存在的符号——目前仅知道；为不可存在的符号
        if($i = strpos($option,$mark)){
            $option = substr($option,0,$i);
        }
        return $option;
    }
} 