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
    $html = '';
    if($title <> ''){
        $html .= '<p>'.$title.'</p>';
    }
    if($remark <> ''){
        $html .='<p>'.$remark.'</p>';
    }
    $html .= '<ul class="t_table t100">';
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
                    case '>':if($row[$value] > $data_rule[$value][1]) $rule_str = ' '.$data_rule[$value][2];break;
                    case '<':if($row[$value] < $data_rule[$value][1]) $rule_str = ' '.$data_rule[$value][2];break;
                    case '>=':if($row[$value] >= $data_rule[$value][1]) $rule_str = ' '.$data_rule[$value][2];break;
                    case '<=':if($row[$value] <= $data_rule[$value][1]) $rule_str = ' '.$data_rule[$value][2];break;
                    case '=': if($row[$value] == $data_rule[$value][1]) $rule_str = ' '.$data_rule[$value][2];break;
                }
                if($rule_str <>'')
                    break;
            }
        }
        $str .= '<li class="t_tr">';
        for ($i=$column_start; $i<$sum_column; $i++ )
        {
            $str .= '<span class="t_td '.$rule_str.'">'.$row[$columns[$i]].'</span>';
        }
        $str .= "</li>";
    }
    return $str;
}
function getHtmlData($url){
    return file_get_contents($url);
}
function reIndexArray($array,$key){
    foreach($array as $item){
        if(isset($item[$key]))
            $data[$item[$key]] = $item;
    }
    return $data;
}

function initArray($array1,$array2 = array(),$default = 0){
    $list = array();
    if(!empty($array2))
        foreach($array1 as $k=>$v)
            foreach($array2 as $kk=>$vv)
                $list[$k][$kk] = $default;
    else
        foreach($array1 as $k=>$v)
                $list[$k] = $default;
    return $list;
}
?>
