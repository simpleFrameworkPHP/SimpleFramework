<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午5:03
 */
class DBMysql extends Db {
    public function __construct($host,$user,$pass,$db_name,$port,$no = 0){
        if ( !extension_loaded('mysql') ) {
            errorPage('你没有安装mysql模块，快去安装吧','系统不支持:mysql');
        }
        $this->link_ID = $no;
        //处理端口号
        $host = $host.($port?':'.$port:'');
        $this->con = mysql_connect( $host, $user, $pass,true,CLIENT_MULTI_RESULTS) or die('没连上数据库'.':'.$no.':'.json_encode($host));
        $dbVersion = mysql_get_server_info($this->con);
        //使用UTF8存取数据库
        mysql_query("SET NAMES '".C('SF_DB_CHARSET')."'", $this->con);
        //设置 sql_model
        if($dbVersion >'5.0.1'){
            mysql_query("SET sql_mode=''",$this->con);
        }
        if(isset($db_name)){
            mysql_select_db($db_name,$this->con) or die("创建完{$this->config['DB_NAME']}数据库再说吧！");
            $this->select_db = true;
        }
        return $this->con;
    }

    public function query($str){
        $data = array();
        $this->sql_str = $str;
        $result = mysql_query($str,$this->con);
        if($result){
            $this->result_rows = mysql_num_rows($result);
            //预留--sql日志位置
            if($this->result_rows){
                while($row = mysql_fetch_assoc($result)){
                    $data[] = $row;
                }
                $this->columns = array_keys($data[0]);
            }
            mysql_free_result($result);
        } else {
            $data = false;
        }
        return $data;
    }
} 