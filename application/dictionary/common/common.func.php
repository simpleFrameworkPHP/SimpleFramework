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
function getSqlInfo($data_list,$column_start = 0){
    if(count($data_list)){
        foreach($data_list as $value){
            $value['columns'] = isset($value['columns']) ? $value['columns'] : array();
            $value['rule'] = isset($value['rule']) ? $value['rule'] : array();
            $value['data'] = isset($value['data']) ? $value['data'] : array();
            $value['remark'] = isset($value['remark']) ? $value['remark'] : '';
            writeHTMLTable($value['title'],$value['remark'],$value['columns'],$column_start,$value['data'],$value['rule']);
        }
    }
}
function getColumnHTML($columns,$start = 1){
    $count = count($columns);
    $column ="<tr>";
    for($i=$start;$i<$count;$i++)
    {
        $column .= '<th class="column">'. $columns[$i]."</th>";
    }
    $column .= "</tr>";
    return $column;
}

function writeHTMLTable($title,$remark,$columns,$column_start,$data,$data_rule = ''){
    $count = count($columns);
    $html = '<table>';
    if($title <> ''){
        $html .= '<tr><th colspan="'.$count.'">'.$title.'</th></tr>';
    }
    if($remark <> ''){
        $html .='<tr><td colspan="'.$count.'">'.$remark.'</td></tr>';
    }
    $html .= getColumnHTML($columns,$column_start);
    if(is_array($data) && count($data)){
        $html .= getBodyHTML($data,$columns,$data_rule,$column_start);
    }
    $html .= '</table>';
    echo $html;
}

function getBodyHTML($data,$columns,$data_rule,$column_start){
    $str = '';
    $sum_column = count($columns);
    $rule_key = array();
    if(is_array($data_rule)){
        $rule_key = array_keys($data_rule);
    }
    foreach($data as $row)
    {
        $str .=  '<tr';
        $rule_str = '';
        if(count($rule_key)){
            foreach($rule_key as $value){
                switch($data_rule[$value][0]){
                    case '>':if($row[$value] > $data_rule[$value][1]) $rule_str = ' class="'.$data_rule[$value][2].'" ';break;
                    case '<':if($row[$value] < $data_rule[$value][1]) $rule_str = ' class="'.$data_rule[$value][2].'" ';break;
                    case '>=':if($row[$value] >= $data_rule[$value][1]) $rule_str = ' class="'.$data_rule[$value][2].'" ';break;
                    case '<=':if($row[$value] <= $data_rule[$value][1]) $rule_str = ' class="'.$data_rule[$value][2].'" ';break;
                    case '=': if($row[$value] == $data_rule[$value][1]) $rule_str = ' class="'.$data_rule[$value][2].'" ';break;
                }
                if($rule_str <>''){
                    $str .= $rule_str;break;
                }
            }
        }
        $str .= '>';
        for ($i=$column_start; $i<$sum_column; $i++ )
        {
            $str .= '<td>'.$row[$columns[$i]].'</td>';
        }
        $str .= "</tr>";
    }
    return $str;
}
?>