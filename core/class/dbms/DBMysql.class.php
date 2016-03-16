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
        $this->con = mysql_connect( $host, $user, $pass,true,CLIENT_MULTI_RESULTS) or errorPage('没有连上数据库','错误信息：'.mysql_error());
        $dbVersion = mysql_get_server_info($this->con);
        //使用UTF8存取数据库
        mysql_query("SET NAMES '".C('SF_DB_CHARSET')."'", $this->con);
        //设置 sql_model
        if($dbVersion >'5.0.1'){
            mysql_query("SET sql_mode=''",$this->con);
        }
        if(isset($db_name)){
            mysql_select_db($db_name,$this->con) or errorPage('没有数据库',"创建完{$this->config['DB_NAME']}数据库再说吧！");
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
            if($this->result_rows){
                while($row = mysql_fetch_assoc($result)){
                    $data[] = $row;
                }
                $this->columns = array_keys($data[0]);
            }
            mysql_free_result($result);
        }
        //sql日志位置
        if(mysql_errno($this->con)){
            //待优化显示查询中的异常及错误
            Log::write('SQL ERROR',$str."\t[error sql]",'sql');
            Log::write('SQL ERROR',mysql_error($this->con).' ; '.mysql_errno($this->con),'sql');
        } else {
            //正常查询日志
            Log::write('SQL',$str."\t[{$this->result_rows}]",'sql');
        }
        return $data;
    }

    public function get_one($str){
        $data = array();
        $this->sql_str = $str;
        $result = mysql_query($str,$this->con);
        if($result){
            $this->result_rows = mysql_num_rows($result);
            if($this->result_rows){
                $data = mysql_fetch_assoc($result);
                $this->columns = array_keys($data);
            }
            mysql_free_result($result);
        }
        //sql日志位置
        if(mysql_errno($this->con)){
            //待优化显示查询中的异常及错误
            Log::write('SQL ERROR',$str."\t[error sql]",'sql');
            Log::write('SQL ERROR',mysql_error($this->con).' ; '.mysql_errno($this->con),'sql');
        } else {
            //正常查询日志
            Log::write('SQL',$str."\t[{$this->result_rows}]",'sql');
        }
        return $data;
    }

    public function execute($str,$type){
        $data = array();
        $this->sql_str = $str;
        $result = mysql_query($str,$this->con);
        //sql日志位置
        if(mysql_errno($this->con)){
            //待优化显示查询中的异常及错误
            Log::write('SQL ERROR',$str."\t[error sql]",'sql');
            Log::write('SQL ERROR',mysql_error($this->con).' ; '.mysql_errno($this->con),'sql');
        } else {
            switch($type){
                case "add"://返回添加的id
                    $result = mysql_insert_id($this->con);break;
                default://返回影响行数
                    $result = mysql_affected_rows($this->con);
            }
            //正常查询日志
            Log::write('SQL',$str."\t[{$this->result_rows}]",'sql');
        }
        return $result;
    }
} 