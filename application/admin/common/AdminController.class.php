<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-7-6
 * Time: 11:21
 */

class AdminController extends Controller {

    public function __construct(){
        parent::__construct();
        //权限管理代码
        if(!$_SESSION['uid']){
            redirect(H('admin/home/login'));
        }
    }

    public function getMenu($model,$target = ''){
        $str = '';
        switch($model){
            case 'admin_main':
                $html = '<li><a id=menu_0 href=menu_url '.($target <> ''?'target="'.$target.'"':'').' onclick="clickMenu(menu_0)">menu_title</a></li>';
                $data = C('ADMIN_MENU');
                foreach($data as $i){
                    $selected = $_REQUEST['c'] == $i['0'] ? ' class="selected" ' : '';
                    $ihtml = str_replace('menu_url','"'.$i['url'].'" '.$selected,$html);
                    $ihtml = str_replace('menu_title',$i['name'],$ihtml);
                    $ihtml = str_replace('menu_0',"'".$i['0']."'",$ihtml);
                    $str .=$ihtml;
                }
                break;
            case 'admin':
                $data =  C('ADMIN_SUB_MENU');
                foreach($data as $k=>$v){
                    foreach($v as $kk=>$vv){
                        $data[$k][$kk]['url'] = H($vv['url']);
                    }
                }
                $str = json_encode($data);
                break;
        }
        return $str;
    }

} 