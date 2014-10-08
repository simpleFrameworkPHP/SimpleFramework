<?php

function getMenu($model,$target = ''){
    $str = '';
    switch($model){
        case 'admin_main':
            $html = '<li><a href=menu_url '.($target <> ''?'target="'.$target.'"':'').' onclick="clickMenu(menu_0,this)">menu_title</a></li>';
            $data = array(array('0'=>'index','name'=>'百度','url'=>'http://www.baidu.com'),array('0'=>'admin','name'=>'百度admin','url'=>'http://www.baidu.com'));
            foreach($data as $i){
                $selected = $_REQUEST['c'] == $i['0'] ? ' class="selected" ' : '';
                $ihtml = str_replace('menu_url','"'.$i['url'].'" '.$selected,$html);
                $ihtml = str_replace('menu_title',$i['name'],$ihtml);
                $ihtml = str_replace('menu_0',"'".$i['0']."'",$ihtml);
                $str .=$ihtml;
            }
            break;
        case 'admin':
            $data = array(
                'index'=>array(
                    array('0'=>'index','name'=>'百度','url'=>'http://www.baidu.com'),
                    array('0'=>'admin','name'=>'百度admin','url'=>'http://www.baidu.com')
                ),
                'admin'=>array(
                    array('0'=>'user','name'=>'用户管理','url'=>'http://www.baidu.com')
                )
            );
            $str = json_encode($data);
            break;
    }
    return $str;
}