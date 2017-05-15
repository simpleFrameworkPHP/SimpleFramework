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
        $title_list = C('ADMIN_MENU');
        $uri = $_REQUEST['a'].'/'.$_REQUEST['c'].'/'.$_REQUEST['f'];
        $admin_title = array();
        foreach($title_list as $i){
            $admin_title[0] = $i;
            foreach($i['children'] as $ii){
                if($uri == $ii['url']){
                    $admin_title[1] = $ii;break;
                }
            }
            if(isset($admin_title[1])){
                break;
            }
        }
        $this->assign('admin_title',$admin_title);
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