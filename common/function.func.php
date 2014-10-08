<?php
function getMenu($model,$target = ''){
    $str = '';
    switch($model){
        case 'admin_main':
            $html = '<li><a href="menu_url" '.($target == ''?'target="'.$target.'"':'').'>menu_title</a></li>';
            break;
    }
    $data = array(array('name'=>'百度','url'=>'http://www.baidu.com'));
    foreach($data as $i){
        $html = str_replace('menu_url',$i['url'],$html);
        $html = str_replace('menu_title',$i['name'],$html);
        $str .=$html;
    }
    return $str;
}
?>