<?php
function addSelectType($type,$t){
    $data = "";
    foreach($type as $value){
        $data .= '<option value="'.$value['value'].'" ';
        if($t == $value['value']){
            $data .= 'selected';
        }
        $data .=' >'.$value['name'].'</option>';
    }
    return $data;
}
function getSqlInfo($data_list,$column_start = 0){
    if($data_list['test']){
        echo $data_list['test'];unset($data_list['test']);
    }
    if(count($data_list)){
        foreach($data_list as $value){
            if(!is_array($value)) break;
            $value['columns'] = isset($value['columns']) ? $value['columns'] : '';
            $value['rule'] = isset($value['rule']) ? $value['rule'] : '';
            $value['data'] = isset($value['data']) ? $value['data'] : '';
            $value['remark'] = isset($value['remark']) ? $value['remark'] : '';
            writeHTMLTable($value['title'],$value['remark'],$value['columns'],$column_start,$value['data'],$value['rule']);
        }
    }
}
function getColumnHTML($columns,$start = 1){
    $count = count($columns);
    $column ='<li class="t_tr">';
    for($i=$start;$i<$count;$i++)
    {
        $column .= '<span class="column t_td">'. $columns[$i]."</span>";
    }
    $column .= "</li>";
    return $column;
}

function writeHTMLTable($title,$remark,$columns,$column_start,$data,$data_rule = ''){
    $count = count($columns);
    $html = '<ul class="t_table">';
    if($title <> ''){
        $html .= '<li class="t_tr"><span class="t_tc" colspan="'.$count.'">'.$title.'</span></li>';
    }
    if($remark <> ''){
        $html .='<li class="t_tr"><span class="t_td" colspan="'.$count.'">'.$remark.'</span></li>';
    }
    $html .= getColumnHTML($columns,$column_start);
    if(is_array($data) && count($data)){
        $html .= getBodyHTML($data,$columns,$data_rule,$column_start);
    }
    $html .= '</ul><br/>';
    echo $html;
}

function getBodyHTML($data,$columns,$data_rule,$column_start){
    $str = '';
    $sum_column = count($columns);
    $rule_key = array();
    if(is_array($data_rule)){
        $rule_key = array_keys($data_rule);
    }
    foreach($data as $key=>$row)
    {
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
                if($rule_str <>'')
                    break;
            }
        }
        $str .= '<li class="t_tr" '.$rule_str.'>';
        for ($i=$column_start; $i<$sum_column; $i++ )
        {
            $str .= '<span class="t_td">'.$row[$columns[$i]].'</span>';
        }
        $str .= "</li>";
    }
    return $str;
}
?>
