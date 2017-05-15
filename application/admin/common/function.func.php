<?php
function getPage($sum_page,$src = '',$cur_page = 1){
    if($cur_page == 0){
        $cur_page = 1;
    }
    $html = '<div class="div_page">';
    $show_max_page = 4;
    $start = 1;
    $end = $sum_page;
    $str = '<a class="page {cur_page}" href="{url}{i}">{i}</a>';
    if($sum_page > $show_max_page){
        $start = $cur_page - intval($show_max_page / 2);
        if($start <= 1){
            $start = 1;
        }
        $end = $cur_page + $show_max_page / 2;
        if($end > $sum_page){
            $end = $sum_page;
            $start = $sum_page - $show_max_page;
        } else if($start == 1){
            $end = min($start + $show_max_page,$sum_page);
        }
    }
    if($start <> 1){
        $item = str_replace(array('{i}','{url}','{cur_page}'),array(1,$src,''),$str);
        $item .= '...';
        $html .= $item;
    }
    for($i = $start;$i <= $end;$i++){
        $class = '';
        if($i == $cur_page){
            $class = 'cur_page';
        }
        $item = str_replace(array('{i}','{url}','{cur_page}'),array($i,$src,$class),$str);
        $html .= $item;
    }
    if($end <> $sum_page){
        $item = '...';
        $item .= str_replace(array('{i}','{url}','{cur_page}'),array($sum_page,$src,''),$str);
        $html .= $item;
    }
    $html .= '</div>';
    return $html;
}