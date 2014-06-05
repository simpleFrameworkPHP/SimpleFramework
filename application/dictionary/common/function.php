<?php
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
function getTable($remark,$dbname){
    $sql = "SELECT  TABLE_NAME as '表名',TABLE_COMMENT as '注释'  FROM TABLES where TABLE_SCHEMA='".$dbname."' order by TABLE_NAME";
    $con = M();
    $result = $con->query($sql);
    $columns = $con->getColumns();
// 获取查询结果
    echo "<table>";
    // 显示字段名称
    $column ="<tr>";
    for ($i=0; $i<2; $i++)
        $column .= '<th class="column">'.$columns[$i]."</th>";
    $column .= "</tr>";
    echo $column;
    $j = 0;
    // 循环取出记录
    while ($row=$result[$j++])
    {
        echo "<tr>";
        //强制替换注释
        if(!$row[$columns[1]]){
            $row[$columns[1]] = $remark[$row[0]][0];
        }
        for ($i=0; $i<2; $i++ )
        {
            echo '<td>';
            echo $row[$columns[$i]];
            echo '</td>';
        }
        echo "</tr>";
    }

    echo "</table>";
    // 释放资源
    mysql_free_result($result);
}
function getTableInfo($remark,$table_list,$dbname){
    mysql_query("SET NAMES 'UTF8'");
    $sql = "SELECT  TABLE_NAME,COLUMN_NAME as '列名',IS_NULLABLE as 'null',DATA_TYPE as '类型',COLUMN_KEY,COLUMN_COMMENT as '注释'  FROM COLUMNS   where TABLE_SCHEMA='".$dbname."' ";
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
    $model = M();
    $result = $model->query($sql);
//获取列名
    $columns = $model->getColumns();
    $sum_column = count($columns);
// 显示字段名称
    $column ="<tr>";
    for ($i=1; $i<$sum_column; $i++)
    {
        $column .= '<th class="column">'.
            $columns[$i];
        $column .= "</th>";
    }
    $column .= "</tr>";
    $table_name = '';
// 循环取出记录
    foreach($result as $row)
    {
        if($row['TABLE_NAME'] <> $table_name){
            if($table_name <> ""){
                echo "</table>";
            }
            $table_name = $row['TABLE_NAME'];
            echo '<br/><table>';
            echo "<tr><th colspan='".($sum_column-1)."'>".$table_name."(".$remark[$row['TABLE_NAME']][0].")</th></tr>";
            // 显示字段名称
            echo $column;
        }
        echo "<tr>";
        //强制替换注释
        if(!$row['注释']){
            $row['注释'] = $remark[$row['TABLE_NAME']][$row['列名']];
        }
        for ($i=1; $i<$sum_column; $i++ )
        {
            echo '<td>';
            echo $row[$columns[$i]];
            echo '</td>';
        }
        echo "</tr>";
    }

    echo "</table>";
// 释放资源
    mysql_free_result($result);
}
function getSqlInfo($sql_list,$con){
    mysql_query("SET NAMES 'UTF8'");
    if(count($sql_list)){
        foreach($sql_list as $key => $sql){
            $result = mysql_db_query($_REQUEST['dbname'],$sql);
            // 获取查询结果
            $row=mysql_fetch_row($result);
            // 定位到第一条记录
            mysql_data_seek($result, 0);
            $sum_column = mysql_num_fields($result);
            // 获取查询结果
            echo "<table>";
            $table_name ="<tr><th colspan='".$sum_column."'>".$key."</th></tr>";
            $table_name .="<tr><td colspan='".$sum_column."'>".$sql."</td></tr>";
            echo $table_name;
            // 显示字段名称
            $column ="<tr>";
            for ($i=0; $i<$sum_column; $i++)
            {
                $column .= '<th class="column">'.
                    mysql_field_name($result, $i);
                $column .= "</th>";
            }
            $column .= "</tr>";
            echo $column;
            // 循环取出记录
            while ($row=mysql_fetch_row($result))
            {
                echo "<tr>";
                for ($i=0; $i<$sum_column; $i++ )
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