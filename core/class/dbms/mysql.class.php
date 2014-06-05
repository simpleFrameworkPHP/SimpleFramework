<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午5:03
 */
class mysql extends \db {
    public function __construct($config,$no = 0){
        if ( !extension_loaded('mysql') ) {
            errorPage('你没有安装mysql模块，快去安装吧','系统不支持:mysql');
        }
        if(!empty($config)) {
            $this->config   =   $config;
        } else {
            $db_connect = C('sf_db_connect');
            $this->config = $db_connect[0];
        }
        $this->link_ID = $no;
        //处理端口号
        $host = $config['dbhost'].($config['dbport']?":{$config['dbport']}":'');
        $this->con = mysql_connect( $host, $config['dbuser'], $config['dbpass'],131072);
        $dbVersion = mysql_get_server_info($this->con);
        //使用UTF8存取数据库
        mysql_query("SET NAMES '".C('sf_db_charset')."'", $this->con);
        //设置 sql_model
        if($dbVersion >'5.0.1'){
            mysql_query("SET sql_mode=''",$this->con);
        }

        mysql_select_db($config['dbname'],$this->con);
        return $this->con;
    }

    public function query($str){
        $data = array();
        $this->query_str = $str;
        $result = mysql_query($str,$this->con);
        $this->result_rows = mysql_num_rows($result);
        //预留sql日志位置
        if($this->result_rows){
            while($row = mysql_fetch_assoc($result)){
                $data[] = $row;
            }
            $this->columns = array_keys($data[0]);
        }
        mysql_free_result($result);
        return $data;
    }
} 