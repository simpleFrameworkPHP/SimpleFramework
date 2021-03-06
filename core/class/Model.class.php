<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-4
 * Time: 下午3:46
 */
class Model{
    public $db;//数据库链接数组
    /**记录所有sql子句的数组，在执行时输出出来
     * array(
     *  'TABLE'     => FROM子句string
     *  'FIELD'     => SELECT子句string
     *  'WHERE'     => WHERE子句string
     *  'GROUP'     => GROUP子句string
     *  'HAVING'    => HAVING子句string
     *  'ORDER'     => ORDER子句string
     *  'LIMIT'     => LIMIT子句string
     * )
    */
    var $option = array();
    var $var_table = array();//model默认表 array('简称'=>'表名');
    var $link_ID;//数据库链接ID，平时为0，多数据库链接时使用
    var $tables_info;//array('表名'=>array('列名'))
    var $db_name;
    var $column_str = '###column####';//如果值为列名时，需要标明前缀
    var $con_str = '';//数据库链接字符串
    var $cache_str = '';//数据库缓存字符串
    var $update_info = false; //false允许更新缓存数据结构

    /**
     * 创建链接
     * @param string $host      数据库域名或ip
     * @param string $user      用户名
     * @param string $pass      密码
     * @param string $db_name   数据库名
     * @param string $port      端口号
     * @param string $mode      链接模式
     * @param int    $no        链接id
     * @param string $chart_set 编码方式
     */
    public function __construct($host = '',$user = '',$pass = '',$db_name = '',$port = '',$mode = '',$no = 0,$chart_set = 'utf8'){
        $con = $this->initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no,$chart_set);
        if(isset($this->var_table)){
            //默认操作表
            $this->db->table = current($this->var_table);
        }
        $this->db_name = $db_name;
        $this->link_ID = $no;
        return $this;
    }

    /**
     * 初始化数据库链接
     * @param string $host      数据库域名或ip
     * @param string $user      用户名
     * @param string $pass      密码
     * @param string $db_name   数据库名
     * @param string $port      端口号
     * @param string $mode      链接模式
     * @param int    $no        链接id
     * @param string $chart_set 编码方式
     * @return array|DBMysql|DBMysqli
     */
    public function initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no,$chart_set){
        if($host == ''){
            return array('error'=>"Can't connect DB.");
        }
        $this->con_str = "{$host}:{$port}_{$user}@{$pass}_{$db_name}";
        $this->cache_str = "{$host}_{$port}_{$user}_{$pass}_{$db_name}";
            //创建对应的数据库链接；
            return $this->db = Db::initDBCon($host,$user,$pass,$db_name,$port,$mode,$no,$chart_set);
    }

    public function select($sql = ''){
        $data = array();
        if($sql == ''){
            $sql = $this->option;
        }
        $data = $this->db->select($sql);
        return $data;
    }

    public function find($sql = ''){
        $data = array();
        if($sql == ''){
            $sql = $this->option;
        }
        $data = $this->db->select($sql,1);
        return $data;
    }

    /**
     * 获取某一单元的值
     * @param string $sql
     * @return bool|mixed
     */
    public function simple($sql = ''){
        $data = false;
        $result = $this->find($sql);
        if(!empty($result)){
            $data = current($result);
        }
        return $data;
    }

    /**
     * 初始化表信息
     * @param bool $is_cache true使用缓存，false不适用缓存
     */
    function initTableInfo($is_cache = true){
        $cache_str = 'DB_INFO_'.$this->cache_str.'/DB_INFO';
        $this->tables_info = S($cache_str);
        if(!empty($this->var_table)){
            foreach($this->var_table as $table){
                if(!isset($this->tables_info[$table]) || empty($this->tables_info[$table]) || !$is_cache){
                    //按照标出的表进行过滤处理
                    $this->getColumnInfo($table);
                }
            }
            //存储数据结构
            S($cache_str,$this->tables_info);
        } else {
            if((empty($this->tables_info) && empty($this->var_table)) || !$is_cache){
                $sql = 'SHOW TABLES';
                $tables = $this->db->select($sql);
                if(is_array($tables) && isset($this->db_name)){
                    foreach($tables as $value){
                        $this->tables_info[$value['Tables_in_'.$this->db_name]] = array();
                    }
                    //存储数据结构
                    S($cache_str,$this->tables_info);
                }
            }
        }
    }

    /**
     * 获取某表的列信息
     * @param $table_name
     */
    function getColumnInfo($table_name){
        $sql = 'SHOW COLUMNS FROM '.$table_name;
        $columns = $this->db->select($sql);
        if($columns && is_array($columns)){
            foreach($columns as $value){
                $this->tables_info[$table_name][$value['Field']] = substr($value['Type'],0,strpos($value['Type'],'('));
            }
        }
    }

    /** 过滤表名----将不存在的表名滤除
     * @param string $table_name 表名，须填写表全称
     * @return string
     */
    public function filterTable($table_name){
        $this->initTableInfo();
        if(is_array($this->tables_info) && array_key_exists($table_name,$this->tables_info)){
            //该表名有效
            return $table_name;
        } else {
            //该表明无效
            return false;
        }
    }

    /** 过滤列名----将不存在的列名滤除
     * @param $column 列名
     * @param array $table_list 表名数组
     * @return mixed
     */
    public function filterColumn($column,array $table_list){
        $this->initTableInfo();
        $tables = array();
        if(strstr($column,'.')){
            //待优化--带表名的列名未过滤
            $table_array = $this->getAllTable();
            $i_column = explode('.',$column);
            foreach($table_array as $key=>$table_name){
                if(is_int($key)){
                    if($i_column[0] == $table_name){
                        $tables[] = $table_name;break;
                    }
                } else {
                    if($i_column[0] == $key){
                        $tables[$table_name] = $key;break;
                    }
                }
            }
            return $this->filterColumn($i_column[1],$tables);
        }else{
            //通过缓存方式检查字段检查字段
            $t_column = $this->filterColumnNo($column, $table_list);
            if(!$t_column && !$this->update_info){
                //没检查出来的话更新缓存再查一遍
                $this->initTableInfo(false);
                $this->update_info = true;
                $t_column = $this->filterColumnNo($column, $table_list);
            }
            $column = $t_column;
        }
        return $column;
    }

    public function filterColumnNo($column,array $table_list){
        //获取查询的表
        if(!count($table_list)){
            $table_list = $this->getAllTable();
        }
        foreach($table_list as $k =>$v){
            if(is_int($k)){
                $tables[$v] = $this->tables_info[$v];
            } else {
                $tables[$k] = $this->tables_info[$k];
            }
        }
        foreach($tables as $table_name => $value){
            if(array_key_exists($column,$value)){
                //该列名有效
                $column = (isset($table_list[$table_name]) ? $table_list[$table_name] : $table_name).'.'.$column;
                break;
            }
        }
        if(!strstr($column,'.')){
            return false;
        }
        return $column;
    }

    //获取sql语句中的列名
    public function getColumns(){
        if(isset($this->db->columns)){
            return $this->db->columns;
        } else {
            //获取默认表的简单数据结构
            return $this->tables_info[current($this->var_table)];
        }
    }

    /**
     * @param array $tables array('简称'=>'表名')；
     * @return $this
     */
    public function table($tables = array()){
        if(!is_array($tables)){
            $tables = explode(',',$tables);
        }
        $tables = $tables == array() ? $this->var_table : array_merge($tables,$this->var_table);
        foreach($tables as $key=>$value){
            if(!$this->filterTable($value)){
                unset($tables[$key]);
                Log::write('SQL ERROR','form语句中出现的'.$value.'表不存在','sql');
            } else {
                if(is_string($key)){
                    $table_array[] = $value.' AS '.$key;
                } else {
                    $table_array[] = $value;
                }
            }
        }
        $this->var_table = $tables;
        $this->initTableInfo();
        if(isset($table_array)){
            $this->option['TABLE'] = implode(',',$table_array);
        }
        return $this;
    }

    public function join(array $tables, $join_str = ' JOIN '){

            $this->table();

        foreach($tables as $key=>$value){
            if(!$this->filterTable($key)){
                unset($tables[$key]);
                Log::write('SQL ERROR','form语句中出现的'.$key.'表不存在','sql');
            } else {
                if(is_string($key)){
                    $table_array[] = $key.(isset($value['as']) ? ' AS '.$value['as'] : '') . ' ON ' . $value['p'];
                }
            }
        }
        $this->option['TABLE'] .= $join_str . implode('',$table_array);
        return $this;
    }

    public function leftJoin(array $tables){
        return $this->join($tables,' LEFT JOIN ');
    }

    public function rightJoin(array $tables){
        return $this->join($tables,' RIGHT JOIN ');
    }

    public function innerJoin(array $tables){
        return $this->join($tables,' INNER JOIN ');
    }

    /** 获取关联表及简称
     * @return array array('表全名‘=>'表简称')
     */
    public function getAllTable(){
        $table_array = array();
        if(!isset($this->option['TABLE'])){
            $this->table();
        }
        if(!isset($this->option['TABLE'])){
            errorPage('没有表格啦',"你需要先使用table方法或设置Model中的 table 值来设置查询的表格");
        } else {
            if(strstr($this->option['TABLE'],',')){
                $table_list = explode(',',$this->option['TABLE']);
            } elseif(strstr($this->option['TABLE'],' JOIN ')){
                $table_list = explode(' JOIN ',$this->option['TABLE']);
            } else {
                $table_list[] = $this->option['TABLE'];
            }
            if(isset($table_list[0])){
                foreach($table_list as $value){
                    if(strstr($value,' AS ')){
                        $i_table = explode(' ',trim($value,' '));
                        $table_array[$i_table[0]] = $i_table[2];
                    } else {
                        $table_array[] = $value;
                    }
                }
            } else {
                $table_array = $table_list;
            }
        }
        return $table_array;
    }

    /**
     * @param array $fields  array（简称=>列名）
     * @param bool $auto_check  是否检测
     * @return $this
     */
    public function fields($fields = array(),$auto_check = true){
        if($auto_check){
            if(empty($fields) || $fields == '*'){
                $this->option['FIELD'] = '*';
            } else {
                $field_list = array();
                if(!is_array($fields)){
                    if(strstr($fields,',')){
                        $field_list = explode(',',$fields);
                    } else {
                        $field_list[] = $fields;
                    }
                } else {
                    $field_list = $fields;
                }
                //获取所有table
                $table_array = $this->getAllTable();
                $field_array = array();
                foreach($field_list as $key=>$value){
                    if(strstr($value, ' AS ')){
                        //滤出保函AS的列名
                        $i_field = explode(' AS ',$value);
                        $key = $i_field[1];
                        $value = $i_field[0];
                    }
                    if($field_v = $this->filterColumn($value,$table_array)){
                        if(is_string($key)){
                            $field_array[] = $field_v . ' AS ' . $key;
                        } else {
                            $field_array[] = $field_v;
                        }
                    }
                }
                if(count($field_array)){
                    $this->option['FIELD'] = implode(',',$field_array);
                } else {
                    errorPage('没有要用的字段','你需要查看fields方法写的字段是否正确！');
                }
            }
        } else {
            $this->option['FIELD'] = is_array($fields) ? implode(',',$fields) : $fields;
        }
        return $this;
    }

    //优化--列是否有效有待确认
    public function where($where,$type = 'WHERE'){
        if(is_array($where)){
            $table_array = $this->getAllTable();
            $where_array = array();
            foreach($where as $key => $value){
                if(is_int($key)){
                    //该情况说明表达式直接写在$value中
                    $where_array[] = $value;
                } else {
                    if($v_field = $this->filterColumn($key,$table_array)){;
                        if(is_array($value)){
                            $value[0] = strtoupper($value[0]);
                            if(in_array($value[0],array('<>','!=','<','>','<=','>=','LIKE'))){
                                $where_array[] = $v_field .' '.$value[0].' '.$this->replaceValue($value[1]);
                            } else if(in_array($value[0],array('IN','NOT IN'))) {
                                $term = strtoupper($value[0]);
                                unset($value[0]);
                                if(is_array(current($value))){
                                    $value = current($value);
                                }
                                $where_array[] = $v_field.' '.strtoupper($term).' ('.implode(' , ',$this->replaceValue($value)).')';
                            } else if(in_array($value[0],array('IS NULL','NOT NULL'))){
                                $where_array[] = $v_field.' '.strtoupper($value[0]);
                            }
                        } else {
                            $where_array[] = $v_field.' = '.$this->replaceValue($value);
                        }
                    } else {
                        Log::write('SQL ERROR','where语句中出现的'.$key.'不存在','sql');
                    }
                }
            }
            $where_str = " ( " . implode(' ) AND ( ',$where_array) . " ) ";
        } else {
            $where_str = $where;
        }
        $this->option[$type] = $where_str;
        return $this;
    }

    public function group($params){
        $param_array = $this->replaceColumns($params);
        $table_array = $this->getAllTable();
        foreach($param_array as $value){
            if($v_field = $this->filterColumn($value,$table_array)){
                $group_array[] = $v_field;
            } else {
                Log::write('SQL ERROR','group语句中出现的'.$value.'不存在','sql');
            }
        }
        $group_str = implode(',',$group_array);
        $this->option['GROUP'] = $group_str;
        return $this;
    }

    public function having($params){
        return $this->where($params,'HAVING');
    }

    public function order($params){
        $param_array = $this->replaceColumns($params);
        $table_array = $this->getAllTable();
        $order_array = array();
        foreach($param_array as $key => $value){
            $order_str = strtoupper($value) == 'DESC' ? 'DESC' : 'ASC';
            $v_field = $this->filterColumn($key,$table_array);
            if($v_field){
                $order_array[] = $v_field .' '.$order_str;
            } else {
                Log::write('SQL ERROR','order语句中出现的'.$value.'不存在','sql');
            }
        }
        $group_str = implode(',',$order_array);
        $this->option['ORDER'] = $group_str;
        return $this;
    }

    public function limit($count,$start = 0){
        $count = intval($count);
        $start = intval($start);
        $this->option['LIMIT'] = ' '.($start ? $start . ',' : '') . $count . ' ';
        return $this;
    }

    public function add(array $Columns){
        $table_list = $this->getAllTable();
        foreach($Columns as $key=>$value){
            if($this->filterColumn($key,$table_list)){
                $add_columns[] = $key;
                $add_values[] = $this->replaceValue($value);
            }
        }
        if(!isset($this->option['TABLE'])){
            $this->table();
        }
        $table = preg_replace('/ AS .*/','',$this->option['TABLE']);
        $insert_sql = 'INSERT INTO '.$table . ' ('.implode(',',$add_columns) . ') VALUES ('.implode(',',$add_values).')';
        return $this->db->add($insert_sql);
    }

    public function addKeyUp(array $Columns){
        $str = array();
        $table_list = $this->getAllTable();
        foreach($Columns as $key=>$value){
            if($this->filterColumn($key,$table_list)){
                $str[] = $key . "='".$value."'";
            }
        }
        $str = implode(",",$str);
        $table = preg_replace('/ AS .*/','',$this->option['TABLE']);
        $insert_sql = "INSERT INTO " . $table . " set ". $str ." ON DUPLICATE KEY UPDATE ".$str;
        $id = $this->db->add($insert_sql);
        if(!$id){
            $id = $this->fields('id')->where($Columns)->simple();
        }
        return $id;
    }

    public function set(array $Columns){
        $edit_str = array();
        $table_list = $this->getAllTable();
        foreach($Columns as $key=>$value){
            if($this->filterColumn($key,$table_list)){
                $edit_str[] = $key.'='.$this->replaceValue($value);
            }
        }
        if(!isset($this->option['TABLE'])){
            $this->table();
        }
        $edit_sql = 'UPDATE '.$this->option['TABLE'] . ' SET '.implode(',',$edit_str);
        if(isset($this->option['WHERE'])){
            $edit_sql .= ' WHERE '.$this->option['WHERE'];
        }
        return $this->db->set($edit_sql);
    }

    public function delete(){
        if(!isset($this->option['TABLE'])){
            $this->table();
        }
        $table = $this->option['TABLE'];
        if(strstr($table,' AS ')){
            $table = explode(' AS ',$table);
            $table = current($table);
        }
        $delete_sql = 'DELETE FROM ' . $table;
        if(isset($this->option['WHERE'])){
            $tables = array_keys($this->var_table);
            $where = str_replace(current($tables).'.','',$this->option['WHERE']);
            $delete_sql .= ' WHERE ' . $where;
        }
        return $this->db->delete($delete_sql);
    }

    public function replaceColumns($params){
        if(!is_array($params)){
            if(strstr($params,',')){
                $param_array = explode(',',$params);
            } else {
                $param_array[] = $params;
            }
        } else {
            $param_array = $params;
        }
        return $param_array;
    }

    public function replaceValue($value){
        if(is_string($value) && !strstr($value,$this->column_str)){
            $value = '\''. addslashes($value) .'\'';
        } else if(is_array($value)) {
            $value = array_map(array(__CLASS__,'replaceValue'),$value);
        } else if(strstr($value,$this->column_str)){
            $value = str_replace($this->column_str,'',$value);
        }
        return $value;
    }
} 