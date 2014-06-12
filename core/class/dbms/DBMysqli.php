<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-6-13
 * Time: 1:09
 */

class DBMysqli extends db {

    public function __construct($config,$no){
        if ( !extension_loaded('mysqli') ) {
            errorPage('你没有安装mysqli模块，快去安装吧','系统不支持:mysqli');
        }
        if(!empty($config)) {
            $this->config   =   $config;
        } else {
            $db_connect = C('SF_DB_CONNECT');
            $this->config = $db_connect[$no];
        }
        $this->link_ID = $no;
        //处理端口号
        $this->con = new mysqli( $this->config['DBHOST'], $this->config['DBUSER'], $this->config['DBPASS'],$this->config['DBNAME'],$this->config['DBPORT']) or exit('没连上数据库'.':'.$no.':'.json_encode($this->config));
        //使用UTF8存取数据库
        $this->con->query("SET NAMES '".C('SF_DB_CHARSET')."'");
        $this->select_db = true;
        return $this->con;
    }

    public function query($str){
        $this->sql_str = $str;
        $result = $this->con->query($str);
        $this->result_rows = $result->num_rows;
        $data = array();
        if ($result) {
            if($this->result_rows>0){                                               //判断结果集中行的数目是否大于0
                while($row =$result->fetch_assoc()){                        //循环输出结果集中的记录
                    $data[] = $row;
                }
                $this->columns = array_keys($data[0]);
            }
        } else {
            $data = false;
        }
        $result->free();
        return $data;
    }
} 