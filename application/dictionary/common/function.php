<?php
function conectionMysql($host,$user_name,$password){
    $formstr = "<br/><form><table class='tcenter'>
    <tr><td>数据库ip：</td><td><input name='dbhost'/></td></tr>
    <tr><td>用户名：</td><td><input name='dbuser'/></td></tr>
    <tr><td>密码：</td><td><input name='dbpwd'/></td></tr>
    <tr><td>数据库名：</td><td><input name='dbname'/></td></tr>
    <tr><td></td><td><input type='submit' value='提交'></td></tr>
</table></form>";
//数据库连接
    $conn = mysql_connect($host,$user_name,$password, 1)  or exit($LANG['can_not_connect_mysql_server']. mysql_error().$formstr);
}
function addSelectType($type,$t){
    $data = "";
    $count = count($type);
    for($i=0;$i<$count;$i++){
        $data .= '<option value="'.$type[$i]['value'].'" ';
        if($t == $type[$i]['value']){
            $data .= 'selected';
        }
        $data .=' >'.$type[$i]['name'].'</option>';
    }
    return $data;
}
function getTable($remark){
    mysql_query("SET NAMES 'UTF8'");
    $sql = "SELECT  TABLE_NAME as '表名',TABLE_COMMENT as '注释'  FROM information_schema.TABLES where TABLE_SCHEMA='".$_REQUEST['dbname']."' order by TABLE_NAME";
    $result = mysql_query($sql);
// 获取查询结果
    echo "<table>";
    // 显示字段名称
    $colume ="<tr>";
    for ($i=0; $i<2; $i++)
        $colume .= '<th class="colume">'.mysql_field_name($result, $i)."</th>";
    $colume .= "</tr>";
    echo $colume;
    // 循环取出记录
    while ($row=mysql_fetch_row($result))
    {
        echo "<tr>";
        //强制替换注释
        if(!$row[1]){
            $row[1] = $remark[$row[0]][0];
        }
        for ($i=0; $i<2; $i++ )
        {
            echo '<td>';
            echo $row[$i];
            echo '</td>';
        }
        echo "</tr>";
    }

    echo "</table>";
    // 释放资源
    mysql_free_result($result);
}
function getTableInfo($remark,$table_list){
    mysql_query("SET NAMES 'UTF8'");
    $sql = "SELECT  TABLE_NAME,COLUMN_NAME as '列名',IS_NULLABLE as 'null',DATA_TYPE as '类型',COLUMN_KEY,COLUMN_COMMENT as '注释'  FROM information_schema.COLUMNS   where TABLE_SCHEMA='".$_REQUEST['dbname']."' ";
    if(is_array($table_list)){
        $sql .= " and TABLE_NAME in ('".implode("','",$table_list)."') ";
    }elseif($table_list == "*"){
        //全表
    } else {
        switch($table_list){
            case "*":
                break;
            default:
                $sql .= " and TABLE_NAME in (".$table_list.") ";
                break;
        }
    }
    $sql .= " order by TABLE_NAME";
    $result = mysql_query($sql);
// 获取查询结果
    $row=mysql_fetch_row($result);


// 定位到第一条记录
    mysql_data_seek($result, 0);
    $tablename = "";
    $sum_colume = mysql_num_fields($result);
// 显示字段名称
    $colume ="<tr>";
    for ($i=1; $i<$sum_colume; $i++)
    {
        $colume .= '<th class="colume">'.
            mysql_field_name($result, $i);
        $colume .= "</th>";
    }
    $colume .= "</tr>";
// 循环取出记录
    while ($row=mysql_fetch_row($result))
    {

        if($row[0] <> $tablename){
            if($tablename <> ""){
                echo "</table>";
            }
            $tablename = $row[0];
            echo '<br/><table>';
            echo "<tr><th colspan='".($sum_colume-1)."'>".$tablename."(".$remark[$row[0]][0].")</th></tr>";
            // 显示字段名称
            echo $colume;
        }
        echo "<tr>";
        //强制替换注释
        if(!$row[5]){
            $row[5] = $remark[$row[0]][$row[1]];
        }
        for ($i=1; $i<$sum_colume; $i++ )
        {
            echo '<td>';
            echo $row[$i];
            echo '</td>';
        }
        echo "</tr>";
    }

    echo "</table>";
// 释放资源
    mysql_free_result($result);
}
function getSqlInfo($sql_list){
    mysql_query("SET NAMES 'UTF8'");
    if(count($sql_list)){
        foreach($sql_list as $key => $sql){
            $result = mysql_db_query($_REQUEST['dbname'],$sql);
            // 获取查询结果
            $row=mysql_fetch_row($result);
            // 定位到第一条记录
            mysql_data_seek($result, 0);
            $sum_colume = mysql_num_fields($result);
            // 获取查询结果
            echo "<table>";
            $table_name ="<tr><th colspan='".$sum_colume."'>".$key."</th></tr>";
            $table_name .="<tr><td colspan='".$sum_colume."'>".$sql."</td></tr>";
            echo $table_name;
            // 显示字段名称
            $colume ="<tr>";
            for ($i=0; $i<$sum_colume; $i++)
            {
                $colume .= '<th class="colume">'.
                    mysql_field_name($result, $i);
                $colume .= "</th>";
            }
            $colume .= "</tr>";
            echo $colume;
            // 循环取出记录
            while ($row=mysql_fetch_row($result))
            {
                echo "<tr>";
                for ($i=0; $i<$sum_colume; $i++ )
                {
                    echo '<td>';
                    echo $row[$i];
                    echo '</td>';
                }
                echo "</tr>";
            }

            echo "</table>";
            // 释放资源
            mysql_free_result($result);
        }
    }
}
?>