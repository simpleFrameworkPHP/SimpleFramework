<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/23
 * Time: 下午3:17
 */

class BaseController extends Controller {

    public function __construct(){
        parent::__construct();
        $menu = C('MENU');
        $url_str = "{$_REQUEST['a']}/{$_REQUEST['c']}/{$_REQUEST['f']}";
        $menu_list = array();
        foreach($menu as $url => $menu_item){
            if($url_str == $url){
                $class = 'cur';
            } else {
                $class = '';
            }
            $menu_list[] = array('class'=>$class,'title'=>$menu_item,'url'=>H($url));
        }
        $this->assign('menu_list',$menu_list);
    }
} 