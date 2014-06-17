<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-6-13
 * Time: 1:09
 */

class DBMysqli extends Db {

    public function __construct($host,$user,$pass,$db_name,$port,$no){
        if ( !extension_loaded('mysqli') ) {
            errorPage('你没有安装mysqli模块，快去安装吧','系统不支持:mysqli');
        }
        if(!empty($config)) {
            $this->config   =   $config;
        }
        $this->link_ID = $no;
        //处理端口号
        $this->con = new mysqli($host,$user,$pass,$db_name,$port)
            or errorPage('没有连上数据库','错误信息：'.mysql_error());
        //使用UTF8存取数据库
        $this->con->query("SET NAMES '".C('SF_DB_CHARSET')."'");
        $this->select_db = true;
        return $this->con;
    }

    public function query($str){
        $data = array();
        $this->sql_str = $str;
        $result = $this->con->query($str);
        if(!empty($result)){
            $this->result_rows = $result->num_rows;
            $data = array();
            if($this->result_rows>0){
                while($row =$result->fetch_assoc()){
                    $data[] = $row;
                }
                $this->columns = array_keys($data[0]);
            }
            $result->free();
        }
        return $data;
    }
} 