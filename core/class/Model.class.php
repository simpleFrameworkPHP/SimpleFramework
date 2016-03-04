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
    var $option;
    var $var_table = array();//model默认表 array('简称'=>'表名');
    var $link_ID;//数据库链接ID，平时为0，多数据库链接时使用
    var $tables_info;//array('表名'=>array('列名'))
    var $db_name;
    var $column_str = '###column####';//如果值为列名时，需要标明前缀

    /**
     * @param $host 数据库域名或ip
     * @param $user 用户名
     * @param $pass 密码
     * @param $db_name  数据库名
     * @param $port 端口号
     * @param $mode 链接模式
     * @param int $no   链接id
     */
    public function __construct($host = '',$user = '',$pass = '',$db_name = '',$port = '',$mode = '',$no = 0,$chart_set = 'utf8'){
        $con = $this->initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no,$chart_set);
        if(isset($this->var_table)){
            //默认操作表
            $key = array_keys($this->var_table);
            $con->table = $this->var_table[$key[0]];
        }
        $this->db_name = $db_name;
        $this->link_ID = $no;
        $this->initTableInfo($no);
        return $this;
    }

    public function initDBConnect($host,$user,$pass,$db_name,$port,$mode,$no,$chart_set){
        if($host == ''){
            return array('error'=>"Can't connect DB.");
        }
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


    function initTableInfo($link_ID = 0){
        $cache_str = 'DB_INFO_'.$link_ID.'/DB_INFO';
        $this->tables_info = S($cache_str);
        if(empty($this->tables_info)){
            $sql = 'SHOW TABLES';
            $tables = $this->db->select($sql);
            if(is_array($tables) && isset($this->db_name)){
                foreach($tables as $value){
                    $this->getColumnInfo($value['Tables_in_'.$this->db_name]);
                }
                //存储数据结构
                S($cache_str,$this->tables_info);
            }
        }
    }
    function getColumnInfo($table_name,$link_ID = 0){
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
                //该列名无效
                $column = false;
            }
        }
        return $column;
    }

    //获取sql语句中的列名
    public function getColumns(){
        if(isset($this->db->columns)){
            return $this->db->columns;
        } else {
            return false;
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
            errorPage('没有表格啦','你需要先使用table方法或设置Model中的$table值来设置查询的表格');
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
                    return false;
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
                            } else if(in_array($value[0],array('IN','NOT IN','NOT NULL'))) {
                                $term = strtoupper($value[0]);
                                unset($value[0]);
                                $where_array[] = $v_field.' '.strtoupper($term).' ('.implode(' , ',$this->replaceValue($value)).')';
                            }
                        } else {
                            $where_array[] = $v_field.' = '.$this->replaceValue($value);
                        }
                    } else {
                        Log::write('SQL ERROR','where语句中出现的'.$key.'不存在','sql');
                    }
                }
            }
            $where_str = implode(' AND ',$where_array);
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
        return $this->db->execute($insert_sql);
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
        $insert_sql = "INSERT INTO " . $table . " set ". $str ." on duplicate key update ".$str;
        return $this->db->execute($insert_sql);
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
        return $this->db->execute($edit_sql);
    }

    public function delete(){
        if(!isset($this->option['TABLE'])){
            $this->table();
        }
        $delete_sql = 'DELETE FROM '.$this->option['TABLE'];
        if(isset($this->option['WHERE'])){
            $delete_sql .= ' WHERE '.$this->option['WHERE'];
        }
        return $this->db->execute($delete_sql);
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